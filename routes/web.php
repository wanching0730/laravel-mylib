<?php

use App\Book;
use App\Publisher;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use App\Http\Resources\BookCollection;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\PublisherResource;
use App\Http\Resources\PublisherCollection;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/books', 'WebController@books');

Route::get('/test', function() {
    // Find authors where name is "Hello" of book with ID 7
    // $book = Book::find($id);
    // $authors = $book->authors()->where('name', 'Hello')->get();

    // if(sizeof($authors) == 0) {
    //     return response()->json([
    //     'error' => 404,
    //     'message' => 'Not found' ], 404);
    // }
    
    // return new AuthorCollection($authors);


    // Find book that have more than one author
    //$books = Book::has('authors')->get();
    //$books = Book::has('authors', '>', 1)->get();


    // Use where condition with whereHas or WhereHas methods
    // $books = Book::whereHas('authors', function($query) {
    //     $query->where('name', 'like', '%ll%');
    // })->get();

    // if(sizeof($books) == 0) {
    //     return response()->json([
    //     'error' => 404,
    //     'message' => 'Not found' ], 404);
    // }


    // Return books that do not have authors
    // $books = Book::doesntHave('authors')->get();
    // return new BookCollection($books);

    // Get authors_count for every book
    //$books = Book::withCount('authors')->get();

    // Get count for authors whose name is hello for every book
    // $books = Book::withCount([
    //     'authors',
    //     'authors as name_hello_count' => function($query) {
    //         $query->where('name', 'hello');
    //     }
    // ])->get();


    // specify which columns of the relationship we would like to retrieve
    //$books = Book::with('publisher:id,name')->get();

    // filter query of "with"
    $books = Book::with(['publisher' => function ($query) {
        //$query->where('name', 'shit');
        $query->orderBy('created_at', 'desc');
    }])->get();

    return $books;
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
