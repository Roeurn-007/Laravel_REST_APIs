@extends('layouts.app')
@section('title')
    <title>Form For Update</title>
@endsection
@section('content')
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <h1>Form for update</h1>
                <form action="{{ route('movies.update', $movie->id) }}" method="POST">
                    {{-- need to use @csrf for curd --}}
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value="{{ $movie->name }}" name="name"
                            placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" value="{{ $movie->date }}"
                            name="date">
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price($)</label>
                        <input type="number" class="form-control" id="price" value="{{ $movie->price }}"
                            name="price">
                    </div>
                    <div class="mb-3">
                        <label for="author" class="form-label">Author</label>
                        <input type="text" class="form-control" id="author" value="{{ $movie->author }}"
                            name="author" placeholder="Author name">
                    </div>
                    <div class="mb-3">

                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" value="" name="description" placeholder="Enter description...">{{ $movie->description }}</textarea>

                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update movies</button>
                        <a href="{{ route('movies.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection
