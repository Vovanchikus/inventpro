<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Samvol\Inventory\Classes\Api\ApiResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiExceptionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (ValidationException $e) {
            return ApiResponse::error($request, 'VALIDATION_ERROR', 'Validation failed', 422, $e->errors());
        } catch (AuthenticationException $e) {
            return ApiResponse::error($request, 'AUTH_TOKEN_INVALID', 'Unauthorized', 401);
        } catch (AuthorizationException $e) {
            return ApiResponse::error($request, 'AUTH_FORBIDDEN', 'Forbidden', 403);
        } catch (NotFoundHttpException $e) {
            return ApiResponse::error($request, 'RESOURCE_UNAVAILABLE', 'Resource not found', 404);
        } catch (Throwable $e) {
            Log::error('api.unhandled_exception', [
                'request_id' => (string) $request->attributes->get('api_request_id', ''),
                'path' => '/' . ltrim((string) $request->path(), '/'),
                'method' => strtoupper((string) $request->method()),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return ApiResponse::error($request, 'SERVER_ERROR', 'Internal server error', 500);
        }
    }
}
