@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-5">Admin Dashboard</h1>

    <div class="row mb-5">
        <div class="col-md-4">
            <div class="glass-card text-center p-4">
                <h3>Users</h3>
                <p class="display-4 fw-bold text-info">{{ $stats['users'] }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card text-center p-4">
                <h3>Articles</h3>
                <p class="display-4 fw-bold text-warning">{{ $stats['articles'] }}</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="glass-card text-center p-4">
                <h3>Comments</h3>
                <p class="display-4 fw-bold text-success">{{ $stats['comments'] }}</p>
            </div>
        </div>
    </div>

    <div class="glass-card p-4 mb-5">
        <h2 class="mb-4">Manage Users</h2>
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning">
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            @else
                                <span class="text-muted">Admin</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            table>
        </div>
    </div>
</div>
@endsection
