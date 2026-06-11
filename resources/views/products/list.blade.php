@extends('layouts.app')
@section('title')
    <title>Product Lists</title>
@endsection
@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Product Lists</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->qty }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#product{{ $product->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('products.destroy', $product->id) }}" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteproduct{{ $product->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                            @include('products.show')
                            @include('products.delete')
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
