@extends('layouts.app')
@section('title')
    <title>Movie Lists</title>
@endsection
@section('content')
    <div class="container mt-3">
        <div class="d-flex gap-2 justify-content-between align-items-center">
            <h1>Movie Lists</h1>
            <a href="{{ route('movies.create') }}" class="btn btn-primary">Add Movie</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Price($)</th>
                    <th>Author</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movies as $index => $movie)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $movie->name }}</td>
                        <td>{{ $movie->date }}</td>
                        <td>{{ $movie->price }}{{ "$" }}</td>
                        <td>{{ $movie->author }}</td>
                        <td>{{ $movie->description }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#movie{{ $movie->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('movies.edit', $movie->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('movies.destroy', $movie->id) }}" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deletemovie{{ $movie->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </a>

                            @include('movies.show')
                            @include('movies.delete')

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
