<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (Exception $error) {

            if ($error instanceof TokenInvalidException) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Token invalido'
                ], Response::HTTP_FORBIDDEN);
            }

            if ($error instanceof TokenExpiredException) {
                return response()->json([
                    'status' => 'error',
                    'error' => 'Token expirado'
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'status' => 'error',
                'error' => 'Token no encontrado',
                'message' => $error->getMessage()
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
