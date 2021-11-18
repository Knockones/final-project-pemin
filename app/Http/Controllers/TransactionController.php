<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

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

    public function show(Request $request, $transactionId)
    {
        $transaction = Transaction::find($transactionId);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        if ($request->user->hasRole('admin')) {
            return response()->json([
                'success' => true,
                'message' => 'A transactions grabbed by admin',
                'data' => [
                    'transaction' => [
                        'user' => $transaction->user,
                        'book' => $transaction->book,
                        'deadline' => $transaction->deadline,
                        'created_at' => $transaction->created_at,
                        'updated_at' => $transaction->updated_at,
                    ],
                ]
            ], 200);
        }

        if ($request->user != $transaction->user) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden access of transaction'
            ], 403);
        }

        if ($request->user->hasRole('user')) {
            return response()->json([
                'success' => true,
                'message' => 'A transactions grabbed by admin',
                'data' => [
                    'transaction' => [
                        'book' => $transaction->book,
                        'deadline' => $transaction->deadline,
                        'created_at' => $transaction->created_at,
                        'updated_at' => $transaction->updated_at,
                    ],
                ]
            ], 200);
        }
    }

    public function index(Request $request)
    {
        if ($request->user->hasRole('admin')) {
            $transactions = Transaction::all();
            return response()->json([
                'success' => true,
                'message' => 'All transactions grabbed by admin',
                'data' => [
                    'transactions' => $this->extract($transactions),
                ]
            ], 200);
        }

        if ($request->user->hasRole('user')) {
            $user = $request->user;
            $transactions = Transaction::where('user_id', $user->id)->get();
            return response()->json([
                'success' => true,
                'message' => 'All transactions grabbed by user',
                'data' => [
                    'transactions' => $this->extract($transactions),
                ]
            ], 200);
        }
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

    private function extract($transaction)
    {
        $data = [];

        foreach ($transaction as $transaction) {
            array_push($data, [
                'book' => $transaction->book,
                'deadline' => $transaction->deadline,
                'created_at' => $transaction->created_at,
                'updated_at' => $transaction->updated_at,
            ]);
        }

        return $data;
    }
}
