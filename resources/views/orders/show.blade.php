<!-- resources/views/orders/show.blade.php -->
@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="container mt-4">
        <h1>Order Details</h1>

        <dl class="row">
            <dt class="col-sm-3">ID:</dt>
            <dd class="col-sm-9">{{ $order->id }}</dd>

            <dt class="col-sm-3">User:</dt>
            <dd class="col-sm-9">{{ $order->user->name }}</dd>

            <dt class="col-sm-3">Address:</dt>
            <dd class="col-sm-9">{{ optional($order->address)->address }}</dd>

            <dt class="col-sm-3">Status:</dt>
            <dd class="col-sm-9">{{ $order->status }}</dd>
        </dl>

        <!-- Product Details Section -->
<h2>Product Details</h2>
<table class="table">
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Deal Price</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($order->details as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td>{{ $detail->product->brand ?? 'N/A' }}</td>
                <td>{{ $detail->product->category ?? 'N/A' }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->deal_price }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No product details available for this order.</td>
            </tr>
        @endforelse
    </tbody>
</table>


        <a href="{{ route('orders.index') }}" class="btn btn-primary">Back to Order List</a>
    </div>
@endsection
