@extends('layouts.admin')
@section('title', 'Add Book')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="plus" style="width:24px;height:24px;"></i> New Book</h1><p>Create a financial book with receipt series</p></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('books.store') }}" method="POST">@csrf
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Book Name</label><input type="text" name="name" class="form-control" placeholder="e.g. General Fund" required value="{{ old('name') }}">@error('name')<div class="form-error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label class="form-label">Book Number</label><input type="text" name="book_no" class="form-control" placeholder="e.g. 001" value="{{ old('book_no') }}">@error('book_no')<div class="form-error">{{ $message }}</div>@enderror</div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2" placeholder="Optional">{{ old('description') }}</textarea></div>
        <div class="divider"></div>
        <h4 class="mb-3" style="display:flex;align-items:center;gap:0.35rem;color:var(--text-secondary);"><i data-feather="hash" style="width:16px;height:16px;"></i> Receipt Numbering Series</h4>
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Prefix</label><input type="text" name="receipt_prefix" class="form-control" placeholder="e.g. RCP-" value="{{ old('receipt_prefix') }}"><div class="text-xs text-tertiary mt-1">Added before the number, e.g. RCP-0001</div></div>
            <div class="form-group"><label class="form-label">Starting Number</label><input type="number" name="receipt_start_no" class="form-control" placeholder="1" min="1" value="{{ old('receipt_start_no', 1) }}"></div>
            <div class="form-group"><label class="form-label">Ending Number (Optional)</label><input type="number" name="receipt_end_no" class="form-control" placeholder="e.g. 500" min="1" value="{{ old('receipt_end_no') }}"><div class="text-xs text-tertiary mt-1">Leave blank for unlimited</div></div>
        </div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('books.index') }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Save Book</button></div>
    </form>
</div>
@endsection
