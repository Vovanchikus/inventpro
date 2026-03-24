<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Classes\Api\JwtTokenService;
use Samvol\Inventory\Classes\Api\RefreshTokenService;
use Samvol\Inventory\Classes\OrganizationAccess;
use Samvol\Inventory\Models\Organization;
use Winter\User\Models\User;

class AuthController extends BaseApiController
{
    public function __construct(
        private JwtTokenService $jwtTokenService,
        private RefreshTokenService $refreshTokenService
    ) {
        parent::__construct();
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required_without:email|string',
            'email' => 'required_without:login|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        try {
            $user = \Auth::authenticate([
                'login' => (string) ($request->input('login') ?: $request->input('email')),
                'password' => (string) $request->input('password'),
            ]);
        } catch (\Throwable $e) {
            return $this->apiError('AUTH_TOKEN_INVALID', 'Invalid credentials', 401);
        }

        if (!$user) {
            return $this->apiError('AUTH_TOKEN_INVALID', 'Invalid credentials', 401);
        }

        if (!$this->isAllowedToSignIn($user)) {
            return $this->apiError('AUTH_FORBIDDEN', 'Account is pending project admin approval', 403);
        }

        return $this->ok($this->buildTokenResponse($user, $request));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'organization_id' => 'nullable|integer',
            'organization_code' => 'nullable|string|max:64',
            'create_organization' => 'nullable|boolean',
            'organization_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        try {
            $user = \Auth::register([
                'name' => (string) $request->input('name'),
                'email' => (string) $request->input('email'),
                'login' => (string) $request->input('email'),
                'password' => (string) $request->input('password'),
                'password_confirmation' => (string) $request->input('password_confirmation'),
                'organization_id' => $request->input('organization_id'),
                'organization_code' => $request->input('organization_code'),
                'create_organization' => $request->boolean('create_organization', false),
                'organization_name' => $request->input('organization_name'),
            ], true, true);
        } catch (\Throwable $e) {
            Log::warning('api.auth_register_failed', [
                'request_id' => (string) $request->attributes->get('api_request_id', ''),
                'message' => $e->getMessage(),
            ]);

            return $this->apiError('VALIDATION_ERROR', 'Unable to register account with provided data', 422);
        }

        return $this->ok($this->buildTokenResponse($user, $request), 201);
    }

    public function refresh(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $newTokenId = $this->jwtTokenService->newTokenId();
        $rotatedSession = $this->refreshTokenService->rotate((string) $request->input('refresh_token'), $newTokenId, $request);

        if (!$rotatedSession) {
            return $this->apiError('AUTH_TOKEN_INVALID', 'Refresh token is invalid or expired', 401);
        }

        $user = User::query()->find((int) $rotatedSession->user_id);
        if (!$user) {
            return $this->apiError('RESOURCE_UNAVAILABLE', 'User not found', 404);
        }

        if (!$this->isAllowedToSignIn($user)) {
            return $this->apiError('AUTH_FORBIDDEN', 'Account is pending project admin approval', 403);
        }

        $accessToken = $this->jwtTokenService->issueAccessToken((int) $user->id, $newTokenId, $this->resolveScopes($user));
        $refreshToken = $this->refreshTokenService->issue((int) $user->id, $newTokenId, $request);

        return $this->ok([
            'access_token' => $accessToken['token'],
            'token_type' => 'Bearer',
            'access_token_expires_at' => $accessToken['expires_at'],
            'refresh_token' => $refreshToken['token'],
            'refresh_token_expires_at' => $refreshToken['expires_at'],
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->attributes->get('api_user');
        if (!$user) {
            return $this->apiError('AUTH_TOKEN_INVALID', 'Unauthorized', 401);
        }

        return $this->ok($this->userPayload($user));
    }

    public function logout(Request $request)
    {
        $refreshToken = (string) $request->input('refresh_token', '');
        if ($refreshToken !== '') {
            $this->refreshTokenService->revokeByPlainToken($refreshToken);
            return $this->ok(['revoked' => true]);
        }

        $payload = (array) $request->attributes->get('api_token_payload', []);
        $tokenId = (string) ($payload['jti'] ?? '');
        if ($tokenId !== '') {
            $this->refreshTokenService->revokeByTokenId($tokenId);
        }

        return $this->ok(['revoked' => true]);
    }

    public function organizations()
    {
        $organizations = Organization::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code']);

        return $this->ok([
            'items' => $organizations->map(fn(Organization $organization) => [
                'id' => (int) $organization->id,
                'name' => (string) $organization->name,
                'code' => (string) $organization->code,
            ])->values(),
        ]);
    }

    public function checkOrganizationName(Request $request)
    {
        $name = trim((string) $request->input('name', ''));
        if ($name === '') {
            return $this->apiError('VALIDATION_ERROR', 'Organization name is required', 422);
        }

        $normalized = preg_replace('/\s+/u', ' ', mb_strtolower($name));
        $exists = Organization::query()
            ->whereRaw('LOWER(TRIM(name)) = ?', [trim((string) $normalized)])
            ->exists();

        return $this->ok([
            'name' => $name,
            'available' => !$exists,
        ]);
    }

    private function buildTokenResponse($user, Request $request): array
    {
        $tokenId = $this->jwtTokenService->newTokenId();
        $scopes = $this->resolveScopes($user);
        $accessToken = $this->jwtTokenService->issueAccessToken((int) $user->id, $tokenId, $scopes);
        $refreshToken = $this->refreshTokenService->issue((int) $user->id, $tokenId, $request);

        return [
            'access_token' => $accessToken['token'],
            'token_type' => 'Bearer',
            'access_token_expires_at' => $accessToken['expires_at'],
            'refresh_token' => $refreshToken['token'],
            'refresh_token_expires_at' => $refreshToken['expires_at'],
            'user' => $this->userPayload($user),
            'scopes' => $scopes,
        ];
    }

    public function health(Request $request)
    {
        return $this->ok([
            'status' => 'ok',
            'auth_pipeline' => 'ready',
            'server_time_utc' => now()->utc()->toIso8601String(),
        ]);
    }

    private function resolveScopes($user): array
    {
        return ['inventory.read', 'inventory.write'];
    }

    private function userPayload($user): array
    {
        $organizationName = null;
        if (!empty($user->organization_id)) {
            $organizationName = Organization::query()
                ->where('id', (int) $user->organization_id)
                ->value('name');
        }

        return [
            'id' => (int) $user->id,
            'name' => (string) ($user->name ?? ''),
            'email' => (string) ($user->email ?? ''),
            'organization_id' => $user->organization_id ?? null,
            'organization_name' => $organizationName ? (string) $organizationName : null,
            'organization_role' => $user->organization_role ?? null,
            'organization_status' => $user->organization_status ?? null,
        ];
    }

    private function isAllowedToSignIn($user): bool
    {
        if (!$user) {
            return false;
        }

        if (OrganizationAccess::isProjectAdmin($user)) {
            return true;
        }

        $status = strtolower(trim((string) ($user->organization_status ?? '')));
        $organizationId = (int) ($user->organization_id ?? 0);

        return $status === OrganizationAccess::STATUS_APPROVED && $organizationId > 0;
    }
}
