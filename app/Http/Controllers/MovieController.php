<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::latest('id')->get();
        return view('movies.list', compact('movies'));
    }

    public function create()
    {
        return view('movies.form');
    }
    public function store()
    {
        //    dd(request()->all());
        Movie::create(
            [
                'name' => request()->name,
                'date' => request()->date,
                'price' => request()->price,
                'author' => request()->author,
                'description' => request()->description,
            ]
        );
        return redirect('/movies');
    }

    public function edit($id)
    {
        $movie = Movie::find($id);
        // dd($movie);
        return view('movies.edit', compact('movie'));
    }

    public function update($id)
    {
        $movie = Movie::find($id);
        $movie->update(
            [
                'name'=>request()->name,
                'date'=>request()->date,
                'price'=>request()->price,
                'author'=>request()->author,
                'description'=>request()->description,
            ]
        );
        return redirect('/movies');
    }

    public function destroy($id)
    {
        Movie::destroy($id);
        return redirect('/movies');
    }
}
