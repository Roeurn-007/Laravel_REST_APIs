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
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    {{-- need to use @csrf for curd --}}
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value="{{ $category->name }}"
                            name="name" placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control" id="dec" name="dec">{{ $category->dec }}</textarea>
                            <label for="dec">Enter description</label>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection
