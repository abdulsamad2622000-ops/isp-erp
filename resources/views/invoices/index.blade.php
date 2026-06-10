@extends('layouts.app')

@section('title', 'Invoices')

@section('content')

<form action="{{ route('invoices.bulkPaid') }}" method="POST" id="bulkForm">
@csrf

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span><i class="bi bi-receipt me-2"></i>All Invoices</span>
        <div class="d-flex gap-2 flex-wrap">
            <button type="submit" class="btn btn-success btn-sm" id="bulkPaidBtn" style="display:none!important;">
                <i class="bi bi-check-all me-1"></i> Bulk Paid
            </button>
            <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg me-1"></i> Add Invoice
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible m-3 mb-0" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-3">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th>#</th>
                        <th>Invoice No</th>
                        <th>Customer</th>
                        <th>Package</th>
                        <th>Amount</th>
                        <th>Issue Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td class="ps-3">
                            @if($invoice->status != 'paid')
                            <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}"
                                class="form-check-input invoice-check">
                            @endif
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td><span class="fw-semibold">{{ $invoice->invoice_number }}</span></td>
                        <td>{{ $invoice->customer->name ?? 'N/A' }}</td>
                        <td>{{ $invoice->package->name ?? 'N/A' }}</td>
                        <td>Rs. {{ number_format($invoice->total_amount, 0) }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-{{
                                $invoice->status == 'paid' ? 'success' :
                                ($invoice->status == 'unpaid' ? 'danger' :
                                ($invoice->status == 'partial' ? 'warning' : 'secondary')) }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if($invoice->status != 'paid')
                                <form action="{{ route('invoices.markPaid', $invoice) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"
                                        title="Mark as Paid"
                                        onclick="return confirm('Mark this invoice as paid?')">
                                        <i class="bi bi-check-lg"></i>