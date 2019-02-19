<?php

namespace App\Http\Controllers;

use App\Publisher;
use Illuminate\Http\Request;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\PublisherCollection;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $publishers = Publisher::with('books')->paginate(5);
        return new PublisherCollection($publishers);
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
        $publisher = Publisher::create($request->all());
            return response()->json([
            'id' => $publisher->id,
            'created_at' => $publisher->created_at,
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
        $publisher = Publisher::find($id);

        if(!$publisher) {
            return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }
        return new PublisherResource($publisher);
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
        $publisher = Publisher::find($id);

        if(!$publisher) {
        return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        $publisher->update($request->all());

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
        $publisher = Publisher::find($id);

        if(!$publisher) {
        return response()->json([
            'error' => 404,
            'message' => 'Not found' ], 404);
        }

        $publisher->delete();

        return response()->json(null, 204);
    }
}
