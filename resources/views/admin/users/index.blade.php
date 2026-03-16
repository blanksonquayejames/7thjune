@extends('admin.layouts.app')
@section('page-title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Users</h4>
</div>

<div class="card admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:0.8rem;font-weight:700">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3">Admin</span>
                            @else
                                <span class="badge bg-light text-dark rounded-pill px-3">Customer</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.toggleRole', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm {{ $user->role === 'admin' ? 'btn-outline-warning' : 'btn-outline-primary' }}" style="border-radius:8px"
                                        onclick="return confirm('Change role to {{ $user->role === 'admin' ? 'customer' : 'admin' }}?')">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    {{ $user->role === 'admin' ? 'Make Customer' : 'Make Admin' }}
                                </button>
                            </form>
                            @else
                                <span class="text-muted small">Current user</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="d-flex justify-content-center mt-4">{{ $users->links() }}</div>
@endsection
