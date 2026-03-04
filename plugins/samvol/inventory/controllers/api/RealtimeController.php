<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;

class RealtimeController extends BaseApiController
{
    public function auth(Request $request)
    {
        $user = $request->attributes->get('api_user');
        if (!$user) {
            return $this->fail('Unauthorized', 401);
        }

        $channelName = (string) $request->input('channel_name', '');
        $socketId = (string) $request->input('socket_id', '');

        if ($channelName === '' || $socketId === '') {
            return $this->fail('channel_name and socket_id are required', 422);
        }

        if (!preg_match('/^private-org\.(\d+)\.inventory$/', $channelName, $matches)) {
            return $this->fail('Unsupported channel', 403);
        }

        $channelOrganizationId = (int) ($matches[1] ?? 0);
        $userOrganizationId = (int) ($user->organization_id ?? 0);
        if ($channelOrganizationId <= 0 || $channelOrganizationId !== $userOrganizationId) {
            return $this->fail('Forbidden channel', 403);
        }

        $request->merge([
            'channel_name' => $channelName,
            'socket_id' => $socketId,
        ]);

        return app('broadcast')->auth($request);
    }

    public function health()
    {
        return $this->ok([
            'ws' => true,
            'driver' => config('broadcasting.default'),
            'channel_pattern' => 'private-org.{organization_id}.inventory',
        ]);
    }
}
