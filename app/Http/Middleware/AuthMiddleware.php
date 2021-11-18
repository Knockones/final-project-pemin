<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class AuthMiddleware
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
                'message' => $e->getMessage(),
            ], 500);
        }

        $user = User::where('email', $payload->sub)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found in token'
            ], 404);
        }

        $request->user = $user;

        return $next($request);
    }
}
