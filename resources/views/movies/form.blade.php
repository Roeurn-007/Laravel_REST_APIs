@extends('layouts.app')
@section('title')
    <title>Add New Movie</title>
@endsection
@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h1>Add New Movie</h1>
                <form action="{{ route('movies.store') }}" method="POST">
                    {{-- need to use @csrf for curd --}}
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Movie name">
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price($)</label>
                        <input type="number" class="form-control" id="price" name="price">
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" name="author" placeholder="Author name">
                    </div>
                    <div class="mb-3">

                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" placeholder="Enter description..."></textarea>

                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Customer</button>
                        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection
