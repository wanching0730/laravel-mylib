<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCollection;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new BookCollection(Book::paginate(5));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $book = Book::create($request->all());
        $book->authors()->sync($request->authors);

        return response()->json([
        'id' => $book->id,
        'created_at' => $book->created_at,
        ], 201);  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::with('authors')->with('publisher')->find($id);

        if(!$book) {
            return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        return new BookResource($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if(!$book) {
        return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        $book->update($request->all());
        $book->authors()->sync($request->authors);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if(!$book) {
        return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        $book->delete();

        return response()->json(null, 204);
    }
}
