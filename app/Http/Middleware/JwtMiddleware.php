<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtMiddleware
{
    public function handle($request, Closure $next): Response
    {
        try {
        } catch (TokenExpiredException) {
            return response()->json(['status' => 'error', 'message' => 'Token expired'], 401);
        } catch (TokenInvalidException) {
            return response()->json(['status' => 'error', 'message' => 'Token invalid'], 401);
        } catch (JWTException) {
            return response()->json(['status' => 'error', 'message' => 'Token not provided'], 401);
        }

        return $next($request);
    }
}
