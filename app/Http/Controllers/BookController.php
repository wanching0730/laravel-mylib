<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCollection;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;
use App\Http\Requests\SaveBookRequest;
use Illuminate\Validation\ValidationException;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Search function: /api/books?title=Python
        $isbn = $request->input('isbn');
        $title = $request->input('title');
        $year = $request->input('year');
        $name = $request->input('name');

        // when(): to check whether isbn and title exist or not then run the function
        $books = Book::with(['authors','publisher'])
                    ->when($isbn, function($query) use ($isbn) {
                        return $query->where('isbn', $isbn);
                    })
                    ->when($title, function($query) use ($title) {
                        return $query->where('title', 'like', "%$title%");
                    })
                    ->when($year, function($query) use ($year) {
                        return $query->where('year', $year);
                    })
                    ->get();

        // $books = Book::with(['authors' => function ($query) use ($name) {
        //     $query->where('name', 'like', '%$name%');
        // }])->get();

        // use get() when there is with(), use all() when there is no with()
        // $books = Book::with('authors')->with('publisher')->get();
        // $books = Book::with('authors')->with('publisher')->paginate(5);

        return new BookCollection($books);
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
    public function store(SaveBookRequest $request)
    {
        try {
            // $request->validate([
            //     'isbn' => ['required', 'unique:books', 'regex:/^(97(8|9))?\d{9}(\d|X)$/'],
            //     'title' => 'max:200',
            //     'year' => 'required'
            // ]);

            //$validatedBook = $request->validated();
            $book = Book::create($request->all());
            $book->authors()->sync($request->authors);

            return response()->json([
                'id' => $book->id,
                'created_at' => $book->created_at,
            ], 201);  

        } catch (ValidationException $ex) {
            return response()->json(['errors' => $ex->errors()], 422);
        } catch (\Exception $ex) {
            return response()->json(['errors' => $ex->message()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // load both authors and publisher attributes
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
    public function update(SaveBookRequest $request, $id)
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
