@extends('layouts.app')
@section('title')
    <title>Customer Lists</title>
@endsection
@section('title')
    <title>Customer Lists</title>
@endsection
@section('content')
    <div class="center container mt-3">
        <div class="d-flex gap-2 justify-content-between align-items-center">
            <h1>Customer Lists</h1>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $index => $customer)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->gender }}</td>
                        <td>
                            <!-- Button trigger modal -->
                            <a href="" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#customer{{ $customer->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('customers.destroy', $customer->id) }}" class="btn btn-sm btn-danger"
                                data-bs-toggle="modal" data-bs-target="#deletecustomer{{ $customer->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </a>

                            @include('customers.show')
                            @include('customers.delete')

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
