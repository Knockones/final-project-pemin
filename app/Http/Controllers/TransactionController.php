<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request;

class TransactionController extends Controller
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

    // TODO: Create transaction logic
    public function create(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint reached'
        ], 200);
    }

    public function show(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint reached'
        ], 200);
    }

    public function index(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint reached'
        ], 200);
    }

    public function update(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint reached'
        ], 200);
    }

    public function destroy(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Endpoint reached'
        ], 200);
    }
}
