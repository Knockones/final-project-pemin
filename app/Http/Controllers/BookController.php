<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
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

    // TODO: Create book logic
    public function index() {
        $books = Book::all();

        if ($books->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No book listed',
                'data' => [
                    'books' => $books
                ],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'All books grabbed',
            'data' => [
                'books' => $books
            ]
        ], 200);
    }

    public function show($bookId) {
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

      return response()->json([
        'success' => true,
        'message' => 'A book grabbed',
        'data' => [
            'book' => $book,
        ]
      ], 200);
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => ['required'],
            'description' => ['required'],
            'author' => ['required'],
            'year' => ['required'],
            'synopsis' => ['required'],
            'stock' => ['required'],
        ]);

        $body = [
            'title' => $request->title ?? 'Lorem',
            'description' => $request->description ?? 'Lorem, ipsum dolor sit amet',
            'author' => $request->author ?? 'Lorem',
            'year' => $request->year ?? '0000',
            'synopsis' => $request->synopsis ?? 'Lorem, ipsum dolor sit amet',
            'stock' => $request->stock ?? '0',
        ];

        $newBook = Book::create($body);

        if ($validation->fails()) {
            return response()->json([
                'success' => true,
                'message' => 'Incomplete book added',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'New book added',
            'data' => [
                'book' => $newBook,
            ],
        ], 201);
    }

    public function update(Request $request,$bookId)
    {
        $book = Book::find($bookId);

        if (!$book) {
            return response()->json([
                'success' => false,
                'message' => 'Book not found'
            ], 404);
        }

        $book->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'A book updated',
            'data' =>[
                'book' => $book
            ]
        ], 200);
    }

    public function destroy (Request $request, $bookId)
    {
        $book = Book::find($bookId);

        if (!$book){
            return response()->json([
                'success' => false,
                'message' => 'Book not found!',
            ],404);
        }

        $book->delete($bookId);

        return response()->json([
            'success' => true,
            'message' => 'Book is Deleted',
        ], 200);
    }
}
