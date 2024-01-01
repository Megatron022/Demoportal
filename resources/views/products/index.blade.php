<!-- resources/views/categories/index.blade.php -->

@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="d-flex align-items-center mb-4">
        <img src="{{ URL('images/dairy-products.png') }}" class="img-fluid mr-3" width="50" height="50" alt="Contact List Image">
        <h1 class="text-primary">Products</h1>
    </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('failure'))
            <div class="alert alert-danger">
                <ul>
                    @foreach (session('failure')->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(Session::has('uploadedProducts'))
    <h3>Uploaded Products:</h3>
    <ul>
        @foreach(Session::get('uploadedProducts') as $product)
            <li>{{ $product->name }}</li>
        @endforeach
    </ul>
@endif



        <!-- categories.blade.php -->

        <!-- categories.blade.php -->

        <form action="{{ route('products.index') }}" method="get" class="mb-3">
           <div class="input-group">
             <input type="text" class="form-control" placeholder="Search by name" name="search"value="{{ old('search', request('search')) }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
            </div>
            <!-- Dropdown for Status Tabs -->
            <div class="mb-3">
                <label for="statusFilter" class="form-label"></label>
                <select class="form-select" id="statusFilter" name="status">
                <option value="" disabled selected>Filter by Status</option>
                    <option value="all" {{ old('status', request('status')) == 'all' ? 'selected' : '' }}>All</option>
                    <option value="ACTIVE" {{ old('status', request('status')) == 'ACTIVE' ? 'selected' : '' }}>Active
                    </option>
                    <option value="INACTIVE" {{ old('status', request('status')) == 'INACTIVE' ? 'selected' : '' }}>Inactive
                    </option>
                </select>
            </div>

        </form>
        <!-- Button to trigger create modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createProductModal">
            Create Product
        </button>
        <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal">
     Import
</button>

        <!-- Create Product Modal -->
        <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createProductModalLabel">Create Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Your create form goes here -->
                        <form method="post" action="{{ route('products.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <select class="form-select" id="brand_id" name="brand_id">
                                    <!-- Populate the options dynamically based on your brands data -->
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <!-- Populate the options dynamically based on your categories data -->
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Product Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Product Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="model" class="form-label">Product Model</label>
                                <input type="text" class="form-control" id="model" name="model">
                            </div>


                            <div class="mb-3">
                                <label for="purchase_price" class="form-label">Purchase Price</label>
                                <input type="number" class="form-control" id="purchase_price" name="purchase_price"
                                    step="0.01" required>
                            </div>

                            <div class="mb-3">
                                <label for="retail_price" class="form-label">Retail Price:</label>
                                <input type="text" class="form-control" id="retail_price" name="retail_price" required>
                            </div>

                            <div class="mb-3">
                                <label for="current_price" class="form-label">Current Price:</label>
                                <input type="text" class="form-control" id="current_price" name="current_price"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status:</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="ACTIVE">Active</option>
                                    <option value="INACTIVE">Inactive</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Product</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add this code where you want to display the modal button in your products.index blade file -->

<!-- Bulk Upload Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Bulk Import Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for Bulk Upload -->
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Choose CSV File</label>
                        <input type="file" class="form-control-file" id="file" name="file" accept=".csv">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

        <!-- Display the list of products in a table view -->
        <table class="table table-striped table-hover table-hover rounded">
         <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Purchase Price</th>
                    <th>Retail Price</th>
                    <th>Current Price</th>
                    <th>Discount (%)</th> <!-- New column for Discount Percentage -->
                    <th>Quantity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                        <td>{{ $product->purchase_price }}</td>
                        <td>{{ $product->retail_price }}</td>
                        <td>{{ $product->current_price }}</td>
                        <td>
                    @php
                        $discountPercentage = (($product->retail_price - $product->current_price) / max($product->retail_price, 1)) * 100;
                        $formattedDiscount = ($discountPercentage > 0) ? number_format($discountPercentage, 2) . '%' : 'N/A';
                    @endphp
                    {{ $formattedDiscount }}
                </td> <!-- Calculate and display Discount Percentage or 'No Discount' -->
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <!-- Edit button to trigger edit modal -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editProductModal{{ $product->id }}">
                                Edit
                            </button>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#viewProductModal{{ $product->id }}">
                                View
                            </button>
                        </td>
                    </tr>
                    <!-- View Product Modal -->
                    <div class="modal fade" id="viewProductModal{{ $product->id }}" tabindex="-1"
                        aria-labelledby="viewProductModalLabel{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewProductModalLabel{{ $product->id }}">View Product
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Name:</strong> {{ $product->name }}</p>
                                    <p><strong>Brand:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                                    <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                                    <p><strong>Discription:</strong> {{ $product->description }}</p>
                                    <p><strong>Model:</strong> {{ $product->model }}</p>
                                    <p><strong>Purchase Price:</strong> {{ $product->purchase_price }}</p>
                                    <p><strong>Retail Price:</strong> {{ $product->retail_price }}</p>
                                    <p><strong>current Price:</strong> {{ $product->current_price }}</p>
                                    <p><strong>Quantity:</strong> {{ $product->quantity }}</p>
                                    <p><strong>Status:</strong> {{ $product->status }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">

                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev"
                                aria-label="@lang('pagination.previous')">&laquo;</a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        <li class="page-item {{ $products->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next"
                                aria-label="@lang('pagination.next')">&raquo;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&raquo;</span>
                        </li>
                    @endif

                </ul>
            </nav>
        </div>

        <!-- Edit Product Modals -->
        @foreach ($products as $product)
            <div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1"
                aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Your edit form goes here -->
                            <form method="post" action="{{ route('products.update', $product->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select" id="brand_id" name="brand_id">
                                        <!-- Populate the options dynamically based on your brands data -->
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select" id="category_id" name="category_id">
                                        <!-- Populate the options dynamically based on your categories data -->
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $product->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Product Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo">
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control" id="model" name="model"
                                        value="{{ $product->model }}">
                                </div>

                                <div class="mb-3">
                                    <label for="purchase_price" class="form-label">Purchase Price</label>
                                    <input type="number" class="form-control" id="purchase_price" name="purchase_price"
                                        value="{{ $product->purchase_price }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="retail_price" class="form-label">Retail Price:</label>
                                    <input type="text" class="form-control" id="retail_price" name="retail_price"
                                        value="{{ $product->retail_price }}"required>
                                </div>

                                <div class="mb-3">
                                    <label for="current_price" class="form-label">Current Price:</label>
                                    <input type="text" class="form-control" id="current_price" name="current_price"
                                        value="{{ $product->current_price }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        value="{{ $product->quantity }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status:</label>
                                    <select class="form-select" id="editStatus" name="status">
                                        <option value="ACTIVE" {{ $product->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="INACTIVE" {{ $product->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </form>
                        </div>
                        <form action="{{ route('products.destroy', $product->id) }}" method="post"
                            style="display:inline;">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
