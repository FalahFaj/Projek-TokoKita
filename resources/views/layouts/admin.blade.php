<!-- resources/views/layouts/admin.blade.php -->
@extends('layouts.app')

@section('styles')
<style>
    .page-title-box {
        padding: 1rem 0;
        border-bottom: 1px solid #dee2e6;
        margin-bottom: 1.5rem;
    }
    .table-actions {
        white-space: nowrap;
    }
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }
    .stock-low {
        color: #dc3545;
        font-weight: bold;
    }
    .stock-ok {
        color: #28a745;
    }
    .badge-out-of-stock {
        background-color: #6c757d;
    }
    .badge-low-stock {
        background-color: #ffc107;
        color: #212529;
    }
</style>
@endsection
