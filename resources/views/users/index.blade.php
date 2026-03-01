@extends('layouts.admin')
@section('title', 'Users')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="users" style="width:24px;height:24px;color:var(--accent);"></i> User Management</h1>
        <p>Manage users and their roles</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary"><i data-feather="user-plus"></i> Add User</a>
</div>

<div class="transaction-list animate-in" style="animation-delay:0.05s;">
    @foreach($users as $user)
        <div class="transaction-item">
            <div class="transaction-icon" style="background:var(--accent-bg);color:var(--accent);"><i data-feather="user"></i></div>
            <div class="transaction-details">
                <div class="transaction-title">
                    {{ $user->name }}
                    @if($user->id === Auth::id())<span class="badge" style="background:var(--income-bg);color:var(--income);font-size:0.55rem;">You</span>@endif
                </div>
                <div class="transaction-meta">
                    <span class="transaction-meta-item"><i data-feather="mail"></i> {{ $user->email }}</span>
                    <span class="badge" style="background:var(--accent-bg);color:var(--accent);font-size:0.6rem;padding:0.15rem 0.4rem;">{{ $user->role_label }}</span>
                </div>
            </div>
            <div class="transaction-actions">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:15px;height:15px;"></i></a>
                @if($user->id !== Auth::id())
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:15px;height:15px;"></i></button>
                </form>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
