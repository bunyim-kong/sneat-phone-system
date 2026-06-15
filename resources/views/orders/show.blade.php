@extends('layouts.app')

@push('styles')
<style>
    /* Invoice Specific Printable Styling Formatting */
    .invoice-container {
        background: #ffffff;
        border-radius: 8px;
        padding: 2.5rem;
        box-shadow: 0 2px 6px rgba(67, 89, 113, 0.1);
    }
    .invoice-header-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #566a7f;
    }
    .khmer-subtext {
        font-size: 0.9rem;
        color: #697a8d;
    }
    .meta-label {
        font-weight: 500;
        color: #a1acb8;
        width: 110px;
        display: inline-block;
    }
    .meta-value {
        color: #566a7f;
        font-weight: 600;
    }
    .invoice-divider {
        border-top: 1px solid #e4e6eb;
        margin: 2rem 0;
    }
    .invoice-table th {
        background-color: transparent !important;
        border-bottom: 1px solid #d9dee3 !important;
        color: #566a7f !important;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.05rem;
        text-transform: uppercase;
    }
    .invoice-table td {
        border-bottom: 1px solid #f5f5f9;
        padding: 1rem 0.75rem;
        color: #566a7f;
    }
    .total-section {
        border-top: 1px solid #d9dee3;
        padding-top: 1rem;
    }
    .footnote {
        font-size: 0.8rem;
        color: #8592a3;
    }
    @media print {
        body * { visibility: hidden; }
        .invoice-container, .invoice-container * { visibility: visible; }
        .invoice-container { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; padding: 0; }
        .btn-print-wrapper { display: none !important; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">

    <div class="d-flex justify-content-between align-items-center mb-4 btn-print-wrapper">
        <a href="{{ route('sales.index', withLang()) }}" class="btn btn-outline-secondary">
            <i class='bx bx-arrow-back me-1'></i> Back to List
        </a>
        <button type="button" class="btn btn-primary" onclick="window.print();">
            <i class='bx bx-printer me-1'></i> Print Invoice
        </button>
    </div>

    <div class="invoice-container">

        <div class="text-center mb-4">
            <h4 class="invoice-header-title text-uppercase mb-1">{{ $company->name ?? ''}}</h4>
            <p class="khmer-subtext mb-0">មានលក់ទូរស័ព្ទ iPhone មកពីអាមេរិក</p>
        </div>

        <div class="row pt-2 g-3">
            <div class="col-sm-7">
                <div class="d-flex align-items-start gap-2 text-muted small mb-1">
                    <i class='bx bx-phone fs-5 text-secondary'></i>
                    <span>011 699 952</span>
                </div>
                <div class="d-flex align-items-start gap-2 text-muted small">
                    <i class='bx bx-map fs-5 text-secondary'></i>
                    <span>#44 មហាវិថីព្រះមុនីវង្ស សង្កាត់ស្រះចក ខណ្ឌដូនពេញ រាជធានីភ្នំពេញ</span>
                </div>
            </div>
            <div class="col-sm-5 text-sm-end">
                <div class="mb-1">
                    <span class="meta-label text-sm-start text-uppercase">Invoice:</span>
                    <span class="meta-value">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="mb-1">
                    <span class="meta-label text-sm-start text-uppercase">Issued Date:</span>
                    <span class="meta-value">{{ setToStringDateFormat($order->created_at ?? $order->order_date) }}</span>
                </div>
                <div>
                    <span class="meta-label text-sm-start text-uppercase">Order Date:</span>
                    <span class="meta-value">{{ setToStringDateFormat($order->order_date) }}</span>
                </div>
            </div>
        </div>

        <div class="invoice-divider"></div>

        <div class="mb-4">
            <h6 class="text-muted text-uppercase fw-bold mb-2" style="font-size: 0.8rem; letter-spacing: 0.05rem;">Customer:</h6>
            <h5 class="fw-bold text-dark mb-1">{{ $order->customer?->name ?? 'Walk in Customer' }}</h5>
            <p class="text-muted small mb-0">{{ $order->customer?->phone ?? '000000000' }}</p>
        </div>

        <div class="text-center my-4 py-2">
            <h5 class="fw-bold text-muted text-uppercase mb-0" style="letter-spacing: 0.2rem; font-size: 1rem;">Invoice</h5>
        </div>

        <div class="table-responsive text-nowrap mb-4">
            <table class="table invoice-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">Items</th>
                        <th style="width: 35%;">Description</th>
                        <th style="width: 15%;">Cost</th>
                        <th style="width: 10%;">Qty</th>
                        <th style="width: 15%; text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderDetails ?? [] as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->product?->product_name ?? 'N/A' }}</strong>
                                <span class="text-muted d-block small">[ IMEI: {{ $item->product?->product_imei ?? 'N/A' }} ]</span>
                            </td>
                            <td>
                                <span class="text-secondary text-wrap">
                                    {{ $item->product?->condition == 1 ? 'Used' : 'New' }},
                                    {{ $item->product?->storage?->name ?? 'N/A' }},
                                    {{ $item->product?->color?->name ?? 'N/A' }}, Original
                                </span>
                            </td>
                            <td>{{ setToStringDolla($item->product?->selling_price) }}</td>
                            <td>1</td>
                            <td class="text-end fw-semibold text-dark">{{ setToStringDolla($item->product?->purchase_price) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">No line items associated with this invoice.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row justify-content-between align-items-start mt-4 pt-2">
            <div class="col-md-6 mb-3 mb-md-0">
                <span class="text-muted text-uppercase small fw-bold d-block mb-1">The Seller</span>
                <span class="fw-semibold text-dark">{{ $order->employee?->name ?? $order->employee_name ?? 'Authorized Personnel' }}</span>

                @if(!empty($order->note))
                    <div class="mt-3 p-2 bg-light border rounded">
                        <small class="text-muted text-uppercase d-block fw-bold" style="font-size: 0.7rem;">Internal Note:</small>
                        <p class="mb-0 small text-secondary text-wrap">{{ $order->note }}</p>
                    </div>
                @endif
            </div>

            <div class="col-md-5">
                <div class="d-flex justify-content-between align-items-center total-section px-2">
                    <span class="fw-bold text-secondary text-uppercase" style="font-size: 0.85rem;">Total:</span>
                    <h5 class="fw-bold text-dark mb-0">{{ setToStringDolla($order->total_amount) }}</h5>
                </div>
            </div>
        </div>

        <div class="invoice-divider" style="margin-top: 3rem;"></div>

        <div class="text-center footnote mt-4">
            <p class="mb-0">Note: សូមពិនិត្យទំនិញអោយបានត្រឹមត្រូវមុននឹង ចាកចេញពីហាង ទំនិញទិញហើយមិនអាចប្តូរវិញបានទេ</p>
        </div>

    </div>
</div>
@endsection
