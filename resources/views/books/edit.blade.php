@extends('layouts.admin')
@section('title', 'Edit Book')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="edit-2" style="width:24px;height:24px;"></i> Edit Book</h1></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('books.update', $book) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Book Name</label><input type="text" name="name" class="form-control" required value="{{ old('name', $book->name) }}"></div>
            <div class="form-group"><label class="form-label">Book Number</label><input type="text" name="book_no" class="form-control" value="{{ old('book_no', $book->book_no) }}"></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description', $book->description) }}</textarea></div>
        <div class="divider"></div>
        <h4 class="mb-3" style="display:flex;align-items:center;gap:0.35rem;color:var(--text-secondary);"><i data-feather="hash" style="width:16px;height:16px;"></i> Receipt Numbering Series</h4>
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Prefix</label><input type="text" name="receipt_prefix" class="form-control" value="{{ old('receipt_prefix', $book->receipt_prefix) }}"></div>
            <div class="form-group"><label class="form-label">Starting Number</label><input type="number" name="receipt_start_no" class="form-control" min="1" value="{{ old('receipt_start_no', $book->receipt_start_no) }}"></div>
            <div class="form-group"><label class="form-label">Ending Number</label><input type="number" name="receipt_end_no" class="form-control" min="1" value="{{ old('receipt_end_no', $book->receipt_end_no) }}"></div>
        </div>
        @if($book->receipt_current_no > 0)
            <div class="text-sm text-accent mt-2"><strong>Last issued:</strong> {{ $book->receipt_prefix }}{{ str_pad($book->receipt_current_no, 4, '0', STR_PAD_LEFT) }}</div>
        @endif
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button></div>
    </form>
</div>
@endsection
