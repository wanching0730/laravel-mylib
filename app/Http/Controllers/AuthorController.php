<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->input('name');

        $request->validate([
            'name' => 'max:150'
        ]);

        $authors = Author::with('books')
                    ->when($name, function($query) use ($name) {
                        return $query->where('name', $name);
                    })
                    ->get();

        //return AuthorResource::collection(Author::all());
        // $authors = Author::with('books')->paginate(5);
        return new AuthorCollection($authors);
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
        $author = Author::create($request->all());
            return response()->json([
            'id' => $author->id,
            'created_at' => $author->created_at,
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
        $author = Author::with('books')->find($id);

        if(!$author) {
            return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }
        return new AuthorResource($author);
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
        $author = Author::find($id);

        if(!$author) {
        return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        $author->update($request->all());

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
        $author = Author::find($id);

        if(!$author) {
        return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        $author->delete();

        return response()->json(null, 204);
    }
}
