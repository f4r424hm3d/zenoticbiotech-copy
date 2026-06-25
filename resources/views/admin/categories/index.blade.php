@extends('layouts.admin')
@section('title', 'Categories | Zenotic Admin')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .admin-bootstrap {
        max-width: 980px;
    }

    .admin-bootstrap .form-control {
        border-radius: 0.75rem;
        padding: 0.875rem 1rem;
        font-size: 1rem;
    }

    .admin-bootstrap .btn-add {
        border-radius: 0.75rem;
        padding: 0.875rem 1.5rem;
        font-weight: 700;
        min-width: 120px;
    }

    .admin-bootstrap .category-list {
        border: 1px solid #dee2e6;
        border-radius: 0.9rem;
        overflow: hidden;
        background: #fff;
    }

    .admin-bootstrap .category-row {
        padding: 1.15rem 1.35rem;
        border-bottom: 1px solid #edf0f2;
    }

    .admin-bootstrap .category-row:last-child {
        border-bottom: 0;
    }

    .admin-bootstrap .category-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .admin-bootstrap .category-slug {
        margin: 0.2rem 0 0;
        color: #94a3b8;
        font-size: 0.95rem;
    }

    .admin-bootstrap .icon-btn {
        border: 0;
        background: transparent;
        color: #64748b;
        padding: 0.5rem;
        border-radius: 0.5rem;
    }

    .admin-bootstrap .icon-btn:hover {
        background: #f1f5f9;
        color: #111827;
    }

    .admin-bootstrap .icon-btn.danger:hover {
        background: #fee2e2;
        color: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="admin-bootstrap mx-auto">
    <div class="mb-4">
        <h1 class="h2 fw-bold mb-2 text-dark">Categories</h1>
        <p class="fs-5 text-secondary mb-0">Organize products into categories</p>
    </div>

    <form method="post" action="{{ route('admin.categories.store') }}" class="row g-3 align-items-stretch mb-4">
        @csrf
        <div class="col-12 col-md">
            <input name="name" required class="form-control h-100" placeholder="New category name...">
        </div>
        <div class="col-12 col-md-auto">
            <button class="btn btn-success btn-add h-100 d-inline-flex align-items-center justify-content-center gap-2">
                <i data-lucide="plus" class="h-5 w-5"></i>
                Add
            </button>
        </div>
    </form>

    <div class="category-list">
        @forelse ($categories as $category)
            <div class="category-row">
                <div class="d-flex align-items-center justify-content-between gap-3">
                    <div>
                        <h2 class="category-name">{{ $category->name }}</h2>
                        <p class="category-slug">{{ $category->slug }}</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" data-edit-category="{{ $category->id }}" class="icon-btn" aria-label="Edit {{ $category->name }}">
                            <i data-lucide="pencil" class="h-5 w-5"></i>
                        </button>
                        <form method="post" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf
                            @method('delete')
                            <button class="icon-btn danger" aria-label="Delete {{ $category->name }}">
                                <i data-lucide="trash-2" class="h-5 w-5"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <form id="category-edit-{{ $category->id }}" method="post" action="{{ route('admin.categories.update', $category) }}" class="d-none mt-3 rounded bg-light p-3">
                    @csrf
                    @method('put')
                    <input type="hidden" name="description" value="{{ $category->description }}">
                    <input type="hidden" name="order_index" value="{{ $category->order_index }}">
                    <div class="row g-3">
                        <div class="col-12 col-lg-9">
                            <input name="name" value="{{ old('name', $category->name) }}" class="form-control" aria-label="Category name">
                        </div>
                        <div class="col-12 col-lg-3">
                            <button class="btn btn-dark w-100">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        @empty
            <div class="p-5 text-center text-secondary">No categories yet.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-edit-category]').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.editCategory;
            const form = document.getElementById(`category-edit-${id}`);
            form?.classList.toggle('d-none');
        });
    });
});
</script>
@endpush
