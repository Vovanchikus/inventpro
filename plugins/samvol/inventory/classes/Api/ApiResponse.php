<?php namespace Samvol\Inventory\Classes\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiResponse
{
    public static function success(Request $request, $data, int $status = 200, array $meta = []): JsonResponse
    {
        $payload = [
            'success' => true,
            'data' => $data,
            'meta' => self::buildMeta($request, $meta),
            'error' => null,
        ];

        return self::json($request, $payload, $status);
    }

    public static function error(Request $request, string $code, string $message, int $status = 400, array $details = [], array $meta = []): JsonResponse
    {
        $payload = [
            'success' => false,
            'data' => null,
            'meta' => self::buildMeta($request, $meta),
            'error' => [
                'code' => $code,
                'message' => $message,
                'details' => (object) $details,
            ],
        ];

        return self::json($request, $payload, $status);
    }

    public static function json(Request $request, array $payload, int $status = 200): JsonResponse
    {
        $response = response()->json($payload, $status, [], JSON_UNESCAPED_SLASHES);

        $requestId = (string) $request->attributes->get('api_request_id', '');
        if ($requestId !== '') {
            $response->headers->set('X-Request-Id', $requestId);
        }

        return $response;
    }

    private static function buildMeta(Request $request, array $meta = []): array
    {
        $requestId = (string) $request->attributes->get('api_request_id', '');
        if ($requestId !== '') {
            $meta['request_id'] = $requestId;
        }

        return $meta;
    }
}
