<!-- resources/views/orders/edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
    <div class="container mt-4">
        <h1>Edit Order</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.update', $order->id) }}" method="post">
            @csrf
            @method('put')

            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <input type="text" class="form-control" id="User_id" name="user_id" required>
            </div>

            <div class="mb-3">
                <label for="address_id" class="form-label">Address</label>
                <input type="text" class="form-control" id="address_id" name="address_id" required>

            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <div class="btn-group" role="group" aria-label="Status">
                    <button type="button" class="btn btn-primary status-btn" data-status="active">Active</button>
                    <button type="button" class="btn btn-secondary status-btn" data-status="inactive">Inactive</button>
                </div>
                <input type="hidden" id="status" name="status"> <!-- Hidden input to store the selected status -->
            </div>

            <button type="submit" class="btn btn-primary">Update Order</button>
        </form>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-2">Back to Order List</a>
    </div>
@endsection
