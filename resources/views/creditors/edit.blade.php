@extends('layouts.admin')
@section('title', 'Edit Creditor')
@section('content')
<div class="page-header animate-in"><div class="page-header-left"><h1><i data-feather="edit-2" style="width:24px;height:24px;"></i> Edit Creditor</h1></div></div>
<div class="form-card animate-in" style="animation-delay:0.05s;">
    <form action="{{ route('creditors.update', $creditor) }}" method="POST">@csrf @method('PUT')
        <div class="grid-2">
            <div class="form-group"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required value="{{ old('name', $creditor->name) }}">@error('name')<div class="form-error">{{ $message }}</div>@enderror</div>
            <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $creditor->phone) }}" placeholder="Optional"></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2">{{ old('description', $creditor->description) }}</textarea></div>
        <div class="divider"></div>
        <div class="flex-between"><a href="{{ route('creditors.show', $creditor) }}" class="btn btn-secondary">Cancel</a><button type="submit" class="btn btn-primary"><i data-feather="check"></i> Update</button></div>
    </form>
</div>
@endsection
