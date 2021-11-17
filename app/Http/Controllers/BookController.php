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
    public function showAllBook() {
      $data = Book::all();

      return response()->json([
        'success' => true,
        'message' => 'Data ditemukan',
        'data' => [
          'books' => $data
        ]
      ], 200);
    }

    public function getIdBook($id) {
      $dataBuku = Book::find();

      if (!$dataBuku) {
        return response()->json([
          'success' => false,
          'message' => 'Post not found'
        ], 400);
      }
      return $dataBuku;
    }

    public function storeBook(Request $request) {
      if ($request->isMethod('post')) {
        $this->validate($request, [
          'title' => 'required',
          'description' => 'required', 
          'author' => 'required', 
          'year' => 'required', 
          'synopsis' => 'required', 
          'stock' => 'required'
        ]);

        $title = $request->input('title');
        $description = $request->input('description');
        $author = $request->input('author');
        $year = $request->input('year');
        $synopsis = $request->input('synopsis');
        $stock = $request->input('stock');

        $data = [
          'title' => $title,
          'description' => $description,
          'author' => $author,
          'year' => $year,
          'synopsis' => $synopsis,
          'stock' => $stock
        ];

        $insert = Book::create($data);

        if ($insert) {
          return response()->json([
            'success' => true,
            'message' => 'Success Insert Data!',
            'results' => $data,
          ], 201);
        } else {
          return response()->json([
            'success' => false,
            'message' => 'Failed to Insert Data!'
          ], 400);
        }
      }
    }
}
