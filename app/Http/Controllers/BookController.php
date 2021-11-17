<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

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

    public function create(Request $request) {
        if ($request->user->role != 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden access',
            ], 401);
        }

        $complete = isset(
            $request->title,
            $request->description,
            $request->author,
            $request->year,
            $request->synopsis,
            $request->stock,
        );

        $body = [
            'title' => $request->title ?? 'Lorem',
            'description' => $request->description ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt doloribus optio molestiae, id reiciendis maxime alias architecto inventore sit corrupti, totam obcaecati enim repudiandae quasi. Labore unde ab ex illum.',
            'author' => $request->author ?? 'Lorem',
            'year' => $request->year ?? '0000',
            'synopsis' => $request->synopsis ?? 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt doloribus optio molestiae, id reiciendis maxime alias architecto inventore sit corrupti, totam obcaecati enim repudiandae quasi. Labore unde ab ex illum.',
            'stock' => $request->stock ?? '0',
        ];

        $newBook = Book::create($body);

        if (!$complete) {
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
}
