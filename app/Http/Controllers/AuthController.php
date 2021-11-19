<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    // TODO: Create auth logic
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Empty field',
            ], 400);
        }

        $input = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        $user = User::where('email', $input['email'])->first();

        if ($user) {
            return response()->json([
                'success' => false,
                'message' => 'Email already exist',
            ], 400);
        }

        $newUser = User::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Successfully registered',
            'data' => [
                'token' => $this->jwt($newUser),
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found',
            ], 404);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Credential not match',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged in',
            'data' => [
                'token' => $this->jwt($user),
            ]
        ], 200);
    }

    private function jwt($user)
    {
        return JWT::encode(
            [
                'sub' => $user->email,
                'iss' => 'http://localhost:8080',
                'aud' => 'http://localhost:8080',
                'iat' => time(),
                'exp' => time() + 60 * 60,
                'role' => $user->role,
            ],
            env('JWT_KEY', 'secret'),
            'HS256'
        );
    }
}
