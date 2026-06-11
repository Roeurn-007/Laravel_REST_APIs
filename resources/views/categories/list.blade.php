@extends('layouts.app')
@section('title')
    <title>Category Lists</title>
@endsection
@section('content')
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Category Lists</h1>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Create Category</a>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $index => $category)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->dec }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#category{{ $category->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('categories.destroy', $category->id) }}" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deleteCategory{{ $category->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </a>

                            @include('categories.show')
                            @include('categories.delete')

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
