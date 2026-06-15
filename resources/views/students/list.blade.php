@extends('layouts.app')
@section('title')
    <title>Student Lists</title>
@endsection
@section('content')
    <div class="center container mt-3">
        <div class="d-flex gap-2 justify-content-between align-items-center">
            <h1>Student Lists</h1>
            <a href="{{ route('students.create') }}" class="btn btn-primary">Add Student</a>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#student{{ $student->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('students.destroy', $student->id) }}" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deletestudent{{ $student->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </a>

                            @include('students.show')
                            @include('students.delete')

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
