<!-- resources/views/orders/create.blade.php -->
@extends('layouts.app')

@section('title', 'Create New Order')

@section('content')
    <div class="container mt-4">
        <h1>Create New Order</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('orders.store') }}" method="post">
            @csrf

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

            <button type="submit" class="btn btn-primary">Create Order</button>
        </form>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary mt-2">Back to Order List</a>
    </div>
    <script>
        // JavaScript to handle status button clicks
        document.querySelectorAll('.status-btn').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.status-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                document.getElementById('status').value = button.getAttribute('data-status');
            });
        });
    </script>
@endsection
