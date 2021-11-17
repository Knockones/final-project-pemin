<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided',
            ], 401);
        }

        $token = Str::of($header)->ltrim('Bearer')->trim();

        try {
            $payload = JWT::decode($token, env('JWT_KEY', 'secret'), ['HS256']);
        } catch(ExpiredException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Provided token is expired.'
            ], 401);
        } catch(Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error while decoding token.'
            ], 500);
        }

        if ($payload->role != "admin") {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        return $next($request);
    }
}
