<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container mt-5 home">

<h1 class="admin"><img src="{{ URL('images/manager.png') }}" class="img-fluid mr-3" width="50" height="50" alt="Contact List Image">Admin Pannel</h1>

        <div class="mt-4">
            <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg">Users</a>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Products</a>
            <a href="{{ route('brands.index') }}" class="btn btn-success btn-lg">Brands</a>
            <a href="{{ route('categories.index') }}" class="btn btn-success btn-lg">Categories</a>
           <a href="{{ route('contacts.index') }}" class="btn btn-warning btn-lg">Contacts</a>
            <a href="{{ route('orders.index') }}" class="btn btn-warning btn-lg">Orders</a>
            <a href="{{ route('dashboard.index') }}" class="btn btn-warning btn-lg">Dashboard</a>

        </div>
    </div>
@endsection
