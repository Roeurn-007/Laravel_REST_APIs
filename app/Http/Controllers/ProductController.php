<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::latest('id','desc')->get();
        return view('products.list', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.form', compact('categories'));
    }

    public function store()
    {
        Product::create(
            [
                'name' => request()->name,
                'price' => request()->price,
                'qty' => request()->qty,
                'category_id' => request()->category_id,
            ]
        );
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $categories = Category::all();
        return view('products.edit', compact('product','categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id)
    {
        $product = Product::find($id);
        $product->update(
            [
                'name'=>request()->name,
                'price'=>request()->price,
                'qty'=>request()->qty,
                'category_id'=>request()->category_id,
            ]
        );
        return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return redirect('products');
    }
}
