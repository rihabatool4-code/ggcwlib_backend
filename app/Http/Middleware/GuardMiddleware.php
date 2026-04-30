<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class GuardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $tokenGuard = $payload->get('guard');
            if ($tokenGuard !== $guard) {
                return response()->json(["message" => "This request is forbidden"], 403);
            }
        } catch (\Exception $e) {
            return response()->json(["message" => "Unauthorized"], 401);
        }
        return $next($request);
    }
}
