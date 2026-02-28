@extends('layouts.admin')
@section('title', 'Edit User')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="edit-2" style="width:24px;height:24px;color:var(--accent);"></i> Edit User</h1>
        <p>Update {{ $user->name }}'s account</p>
    </div>
</div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('users.update', $user) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name) }}">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email) }}">
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    @foreach($roles as $key => $label)
                        <option value="{{ $key }}" {{ old('role', $user->role) == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('role')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="divider"></div>
        <h3 style="font-size:0.9rem;color:var(--text-secondary);margin-bottom:0.75rem;">Change Password <span class="text-xs text-tertiary">(leave blank to keep current)</span></h3>
        <div class="grid-2">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min 6 characters">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>
        <div class="divider"></div>
        <div class="flex-between">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update User</button>
        </div>
    </form>
</div>
@endsection
