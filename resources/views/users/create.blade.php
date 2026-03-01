@extends('layouts.admin')
@section('title', 'Add User')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="user-plus" style="width:24px;height:24px;color:var(--accent);"></i> Add User</h1>
        <p>Create a new user account</p>
    </div>
</div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('users.store') }}" method="POST">@csrf
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name') }}" placeholder="Enter full name">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email') }}" placeholder="user@example.com">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Min 6 characters">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" required placeholder="Re-enter password">
            </div>
        </div>
        <div class="divider"></div>
        <div class="flex-between">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Create User</button>
        </div>
    </form>
</div>
@endsection
