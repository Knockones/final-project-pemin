<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class UserController extends Controller
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

    // TODO: Create user logic
    public function index(Request $request)
    {
        if ($request->user->role != "admin") {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden access',
            ], 401);
        }

        $users = User::all();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No user listed',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'All users grabbed',
            'data' => [
                'users' => $users,
            ],
        ], 200);
    }

    public function show(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($request->user->role == 'admin') {
            return response()->json([
                'success' => true,
                'message' => 'A user grabbed',
                'data' => [
                    'user' => $user,
                ],
            ], 200);
        }

        if ($user->email != $request->user->sub) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden get other user',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'A user grabbed',
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }

    public function update(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->email != $request->user->sub) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden get other user',
            ], 403);
        }

        $user->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'A user updated',
            'data' => [
                'user' => $user,
            ],
        ], 200);
    }

    public function destroy(Request $request, $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        if ($user->email != $request->user->sub) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden get other user',
            ], 403);
        }

        $user->delete($userId);

        return response()->json([
            'success' => true,
            'message' => 'A user deleted',
        ], 200);
    }
}
