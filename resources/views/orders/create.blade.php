@extends('layouts.app')

@push('styles')
<style>
    .table th {
        font-weight: 600;
        letter-spacing: 0.05rem;
        font-size: 0.75rem;
        color: #566a7f;
    }
    .total-label {
        font-weight: 700;
        color: #566a7f;
    }
</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <form id="formRegisterSale" method="POST" action="{{ route('sales.store', withLang()) }}">
        @csrf

        <div class="card mb-4">
            <h5 class="card-header text-primary" style="font-size: 1.15rem;">Register Sale</h5>

            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label" for="sale_date">SALE DATE</label>
                        <input type="date" id="sale_date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date', date('Y-m-d')) }}" required>
                        @error('sale_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="customer_id">CUSTOMER</label>
                        <select id="customer_id" name="customer_id" class="form-select @error('customer_id') is-invalid @enderror" required>
                            <option value="">Select Customer</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" {{ old('customer_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('customer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label" for="product_selector">PRODUCT NAME</label>
                        <select id="product_selector" class="form-select">
                            <option value="">Select Order Product</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->id }}"
                                        data-name="{{ $product->product_name }}"
                                        data-imei="{{ $product->product_imei }}"
                                        data-price="{{ $product->selling_price }}"
                                        data-detail="{{ $product->condition == 1 ? 'Used' : 'New' }} &bull; {{ $product->storage?->name ?? 'N/A' }} &bull; {{ $product->color?->name ?? 'N/A' }}">
                                    {{ $product->product_name }} [IMEI: {{ $product->product_imei }}] - ${{ number_format($product->selling_price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="table-responsive border rounded mb-4">
                    <table class="table table-striped align-middle mb-0" id="salesItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 20%;">PRODUCT IMEI</th>
                                <th style="width: 25%;">PRODUCT NAME</th>
                                <th style="width: 30%;">PRODUCT DETAIL</th>
                                <th style="width: 15%;">PRICE ($)</th>
                                <th style="width: 10%; text-align: center;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceTableBody">
                            <tr id="noDataRow">
                                <td colspan="5" class="text-center text-muted py-5 text-uppercase fw-semibold" style="letter-spacing: 0.05rem;">
                                    No Data Available
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row justify-content-end mb-4">
                    <div class="col-md-4 text-end d-flex justify-content-end gap-5 align-items-center pe-4">
                        <span class="total-label text-uppercase">Total :</span>
                        <h4 class="mb-0 fw-bold text-dark" id="grandTotalDisplay">$ 0.00</h4>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <label class="form-label text-uppercase text-xs" for="note">Note</label>
                        <textarea class="form-control" id="note" name="note" rows="4" placeholder="{{ old('note') }}"></textarea>
                    </div>
                </div>
            </div>

            <div class="card-body border-top">
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary me-2" id="submitInvoiceBtn" disabled>Submit Order</button>
                    <a href="{{ route('sales.index', withLang()) }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Dropdown selection rule
    $('#product_selector').change(function() {
        let option = $(this).find('option:selected');
        let id = option.val();
        if (!id) return;

        // Check if row already exists in the DOM directly instead of using arrays
        if ($(`#row-${id}`).length > 0) {
            alert("This item with IMEI: " + option.data('imei') + " is already in the invoice list.");
            $(this).val('');
            return;
        }

        $('#noDataRow').hide();

        // Simplified template input names using [id] avoids complex array reindexing logic entirely
        let newRow = `
            <tr id="row-${id}" class="invoice-item-row">
                <td><strong>${option.data('imei')}</strong></td>
                <td><strong>${option.data('name')}</strong></td>
                <td><span class="text-muted">${option.data('detail')}</span></td>
                <td>
                    <span class="fw-semibold item-price-val" data-raw-price="${option.data('price')}">$ ${parseFloat(option.data('price')).toFixed(2)}</span>
                    <input type="hidden" name="items[${id}][product_id]" value="${id}">
                    <input type="hidden" name="items[${id}][price]" value="${option.data('price')}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger border-0 remove-invoice-item">
                        <i class='bx bx-trash fs-5'></i>
                    </button>
                </td>
            </tr>
        `;

        $('#invoiceTableBody').append(newRow);
        $(this).val('');
        recalculateInvoiceTotals();
    });

    // Cleaned up delete action logic
    $(document).on('click', '.remove-invoice-item', function() {
        $(this).closest('tr').remove();
        recalculateInvoiceTotals();
    });

    // Streamlined loop calculation rule
    function recalculateInvoiceTotals() {
        let runningSum = 0;
        let rows = $('.item-price-val');

        rows.each(function() {
            runningSum += parseFloat($(this).data('raw-price'));
        });

        $('#noDataRow').toggle(rows.length === 0);
        $('#submitInvoiceBtn').prop('disabled', rows.length === 0);
        $('#grandTotalDisplay').text('$ ' + runningSum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    }
});
</script>
@endpush
