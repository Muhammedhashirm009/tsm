@extends('layouts.admin')
@section('title', 'Edit Category')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="edit-2" style="width:24px;height:24px;"></i> Edit Category</h1>
    </div>
</div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" required value="{{ old('name', $category->name) }}">
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">Type</label>
            <select name="type" class="form-control" required>
                <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
            @error('type')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="flex-between mt-4">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button>
        </div>
    </form>
</div>
@endsection
