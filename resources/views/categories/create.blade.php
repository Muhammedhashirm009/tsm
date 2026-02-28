@extends('layouts.admin')
@section('title', 'Add Category')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="plus" style="width:24px;height:24px;"></i> New Category</h1>
        <p>Add an income or expense category</p>
    </div>
</div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Jumma Collection, Electricity Bill" required value="{{ old('name') }}">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Type</label>
            <select name="type" class="form-control" required>
                <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
            @error('type')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="flex-between mt-4">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save</button>
        </div>
    </form>
</div>
@endsection
