@extends('layouts.admin')
@section('title', 'Books')
@section('content')
<div class="page-header animate-in">
    <div class="page-header-left">
        <h1><i data-feather="book-open" style="width:24px;height:24px;"></i> Books</h1>
        <p>Financial books & receipt series</p>
    </div>
    <a href="{{ route('books.create') }}" class="btn btn-primary"><i data-feather="plus"></i> New Book</a>
</div>
<div class="data-list animate-in" style="animation-delay:0.05s;">
    @forelse($books as $book)
        <a href="{{ route('books.show', $book) }}" class="data-item" style="text-decoration:none;color:inherit;">
            <div class="data-item-icon"><i data-feather="book-open"></i></div>
            <div class="data-item-content">
                <div class="data-item-title flex gap-2 items-center">
                    {{ $book->name }}
                    @if($book->book_no)<span class="badge" style="background:var(--bg-body);color:var(--text-secondary);font-size:0.6rem;">No. {{ $book->book_no }}</span>@endif
                </div>
                <div class="data-item-subtitle mt-1 flex gap-3">
                    @if($book->receipt_prefix)
                        <span>Receipt: {{ $book->receipt_prefix }}{{ str_pad($book->receipt_start_no, 4, '0', STR_PAD_LEFT) }}@if($book->receipt_end_no) → {{ $book->receipt_prefix }}{{ str_pad($book->receipt_end_no, 4, '0', STR_PAD_LEFT) }}@endif</span>
                    @endif
                    <span class="text-income">{{ $book->receipts_count ?? 0 }} receipts</span>
                    <span class="text-expense">{{ $book->vouchers_count ?? 0 }} vouchers</span>
                </div>
                @if($book->receipt_current_no > 0)
                    <div class="text-xs text-accent mt-1">Last issued: {{ $book->receipt_prefix }}{{ str_pad($book->receipt_current_no, 4, '0', STR_PAD_LEFT) }}</div>
                @endif
            </div>
            <div class="data-item-actions" onclick="event.stopPropagation();event.preventDefault();">
                <a href="{{ route('books.edit', $book) }}" class="btn btn-ghost btn-icon"><i data-feather="edit-2" style="width:16px;height:16px;"></i></a>
                <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Delete this book?');">@csrf @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-icon" style="color:var(--expense);"><i data-feather="trash-2" style="width:16px;height:16px;"></i></button>
                </form>
            </div>
        </a>
    @empty
        <div class="card">
            <div class="empty-state">
                <div class="empty-state-icon"><i data-feather="book-open"></i></div>
                <h4>No books yet</h4>
                <p class="text-sm text-tertiary mt-1">Create a book to start issuing receipts.</p>
                <a href="{{ route('books.create') }}" class="btn btn-primary mt-3"><i data-feather="plus"></i> Add Book</a>
            </div>
        </div>
    @endforelse
</div>
@endsection
