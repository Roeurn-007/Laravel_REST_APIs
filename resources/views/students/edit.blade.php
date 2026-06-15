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
                <form action="{{ route('students.update', $student->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" value="{{ $student->name }}" name="name"
                            placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="{{ $student->email }}"
                            name="email" placeholder="example@gmail.com">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="number" class="form-control" id="phone" value="{{ $student->phone }}"
                            name="phone">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update student</button>
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
@endsection
