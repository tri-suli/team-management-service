<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        $token = $request->header('authorization');

        if (! str_contains($token, config('api.token.delete'))) {
            return response()->json([
                'message' => sprintf('Invalid value for bearer token: %s', str_replace('Bearer ', '', $token))
            ], 403);
        }

        return $next($request);
    }
}
