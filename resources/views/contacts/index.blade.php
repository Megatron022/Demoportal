<!-- resources/views/categories/index.blade.php -->

@extends('layouts.app')

@section('content')

<div class="container mt-5">
    <div class="d-flex align-items-center mb-4">
        <img src="{{ URL('images/contact-list.png') }}" class="img-fluid mr-3" width="50" height="50" alt="Contact List Image">
        <h1 class="text-primary">Contacts</h1>
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



        <!-- categories.blade.php -->

        <!-- categories.blade.php -->

        <form action="{{ route('contacts.index') }}" method="get" class="mb-3">
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

        <!-- Display contacts here -->


        <!-- Display contacts here -->


        <!-- Button to trigger create modal -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createContactModal">
            Create Contact
        </button>

        <!-- Create Contact Modal -->
        <div class="modal fade" id="createContactModal" tabindex="-1" aria-labelledby="createContactModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createContactModalLabel">Create Contact</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Your create form goes here -->
                        <form method="post" action="{{ route('contacts.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone:</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Contact Photo</label>
                                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="website" class="form-label">Website:</label>
                                <input type="text" class="form-control" id="website" name="website">
                            </div>
                            <button type="submit" class="btn btn-primary">Save Contact</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display the list of contacts in a table view -->
        <table class="table table-striped table-hover table-hover rounded">
         <thead class="table-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Description</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->description }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->status }}</td>
                        <td>
                            <!-- Edit button to trigger edit modal -->
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editContactModal{{ $contact->id }}">
                                Edit
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#viewContactModal{{ $contact->id }}">
                                    View
                                </button>
                        </td>
                    </tr>
                    <!-- View Category Modal -->
                    <div class="modal fade" id="viewContactModal{{ $contact->id }}" tabindex="-1"
                        aria-labelledby="viewContactModalLabel{{ $contact->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="viewContactModalLabel{{ $contact->id }}">View Contacts
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Name:</strong> {{ $contact->name }}</p>
                                    <p><strong>Phone:</strong> {{ $contact->phone }}</p>
                                    <p><strong>Email:</strong> {{ $contact->email }}</p>
                                    <p><strong>Description:</strong> {{ $contact->description }}</p>
                                    <p><strong>Status:</strong> {{ $contact->status }}</p>
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
                    @if ($contacts->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $contacts->previousPageUrl() }}" rel="prev"
                                aria-label="@lang('pagination.previous')">&laquo;</a>
                        </li>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($contacts->getUrlRange(1, $contacts->lastPage()) as $page => $url)
                        <li class="page-item {{ $contacts->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($contacts->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $contacts->nextPageUrl() }}" rel="next"
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






        <!-- Edit Contact Modals -->
        @foreach ($contacts as $contact)
            <div class="modal fade" id="editContactModal{{ $contact->id }}" tabindex="-1"
                aria-labelledby="editContactModalLabel{{ $contact->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editContactModalLabel{{ $contact->id }}">Edit Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Your edit form goes here -->
                            <form method="post" action="{{ route('contacts.update', $contact->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="editName" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="editName" name="name"
                                        value="{{ $contact->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="editEmail" name="email"
                                        value="{{ $contact->email }}">
                                </div>
                                <div class="mb-3">
                                    <label for="editDescription" class="form-label">Description:</label>
                                    <textarea class="form-control" id="editDescription" name="description" rows="3">{{ $contact->description }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="editPhone" class="form-label">Phone:</label>
                                    <input type="text" class="form-control" id="editPhone" name="phone"
                                        value="{{ $contact->phone }}">
                                </div>
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Contact Photo</label>
                                    <input type="file" class="form-control" id="photo" name="photo"
                                        accept="image/*">
                                </div>
                                <div class="mb-3">
                                    <label for="editWebsite" class="form-label">Website:</label>
                                    <input type="text" class="form-control" id="editWebsite" name="website"
                                        value="{{ $contact->website }}">
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label">Status:</label>
                                    <select class="form-select" id="editStatus" name="status">
                                        <option value="ACTIVE" {{ $contact->status == 'ACTIVE' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="INACTIVE" {{ $contact->status == 'INACTIVE' ? 'selected' : '' }}>
                                            Inactive</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Contact</button>
                            </form>
                        </div>
                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="post"
                            style="display:inline;">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this contact?')">Delete</button>
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
