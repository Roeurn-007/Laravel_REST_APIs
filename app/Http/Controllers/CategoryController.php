<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        // 1. row sql
        // $categories = DB::select('SELECT * FROM categories');

        // 2. query  builder 
        // $categories = DB::table('categories')->get();


        // 3. Eloquent ORM 
        // use all()
        $categories = Category::orderBy('id','desc')->get();


        // dd($categories);
        return view('categories.list', compact('categories'));
    }

    public function create()
    {
        return view('categories.form');
    }

    public function store()
    {
        Category::create([
            'name' => request()->name,
            'dec' => request()->dec,
        ]);
        return redirect('categories');
    }

    public function edit($id)
    {
        // dd($id);
        $category = Category::find($id);

        return view('categories.edit', compact('category'));
    }

    public function update($id)
    {
        // dd(request()->all());
        
        $category = Category::find($id);

        // make sure update doesn't has "d"
        $category->update(
            [
                'name' => request()->name,
                'dec' => request()->dec,
            ]
        );

        return redirect('/categories');
    }

    public function destroy($id){
        Category::destroy($id);
        return redirect('/categories');

    }
}
