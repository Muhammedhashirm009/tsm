@extends('layouts.admin')
@section('title', 'Categories')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="grid" style="width:24px;height:24px;"></i> Categories</h1>
        <p>Organise your income and expense types</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Category</a>
</div>
<div class="data-list animate-in" style="animation-delay:0.05s;">
    @forelse($categories as $category)
        <div class="data-item">
            <div class="data-item-icon" style="{{ $category->type == 'income' ? 'background:var(--income-bg);color:var(--income);' : 'background:var(--expense-bg);color:var(--expense);' }}">
                <i data-feather="{{ $category->type == 'income' ? 'arrow-down-left' : 'arrow-up-right' }}"></i>
            </div>
            <div class="data-item-content">
                <div class="data-item-title">{{ $category->name }}</div>
                <div class="mt-1">
                    <span class="badge {{ $category->type == 'income' ? 'badge-income' : 'badge-expense' }}">{{ ucfirst($category->type) }}</span>
                </div>
            </div>
            <div class="data-item-actions">
                <a href="{{ route('categories.edit', $category) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:16px;height:16px;"></i></a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:16px;height:16px;"></i></button>
                </form>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon"><i data-feather="grid"></i></div>
                <h4>No categories yet</h4>
                <p class="text-sm text-tertiary mt-1">Add income & expense categories to get started.</p>
            </div>
        </div>
    @endforelse
</div>
@endsection
