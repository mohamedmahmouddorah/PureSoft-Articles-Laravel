@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="container" style="margin-top: 40px;">
    
    @if(isset($stats) && Auth::user()->isAdmin())
        <h2 class="text-center mb-4 text-warning">Admin Dashboard</h2>
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
                <table class="table table-dark table-hover align-middle">
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
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem; display:flex; align-items:center; justify-content:center; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%;">
                                        {{ mb_substr($user->username, 0, 1) }}
                                    </div>
                                    {{ $user->username }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-outline-info">View</a>

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
                                        <span class="text-muted small">Admin</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <h2 class="text-center mb-5 text-white">Our Community</h2>
        
        <div class="row">
            @foreach($users as $user)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="glass-card text-center p-4 h-100">
                        <div class="avatar mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); display: flex; align-items: center; justify-content: center; border-radius: 50%; color: white; font-weight: bold;">
                            {{ mb_substr($user->username, 0, 1) }}
                        </div>
                        <h4 class="mb-1">{{ $user->username }}</h4>
                        <p class="text-muted small mb-3">Joined in {{ $user->created_at->format('M Y') }}</p>
                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info btn-sm w-100">View Profile</a> 
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
