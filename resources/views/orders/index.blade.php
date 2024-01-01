<!-- resources/views/orders/index.blade.php -->
@extends('layouts.app')

@section('title', 'Order List')

@section('content')
    <div class="container mt-4">
        <h1>Order List</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('orders.index') }}" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by name" name="search"
                    value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>

            <div class="mb-4">
                <label for="status-filter" class="form-label">Filter by Status:</label>
                <select name="status" id="status-filter" class="form-select">
                    <option value="">All</option>
                    <option value="active"{{ request('status') === 'active' ? ' selected' : '' }}>Active</option>
                    <option value="inactive"{{ request('status') === 'inactive' ? ' selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="submit" class="btn btn-outline-secondary">Search</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->address }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <a href="{{ route('orders.show', ['order' => $order->id]) }}" class="btn btn-info">View</a>
                            <a href="{{ route('orders.edit', ['order' => $order->id]) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('orders.destroy', ['order' => $order->id]) }}" method="post"
                                style="display: inline-block">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
