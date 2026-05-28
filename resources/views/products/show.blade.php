@extends('layouts.app')

@push('styles')
<style>
    /* Mimicking the clean, subtle styling from the image */
    .product-info-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: #566a7f; /* Standard Bootstrap muted text color */
        text-transform: uppercase;
    }
    .product-info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #212529;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="card mb-4">
            <h5 class="card-header text-primary" style="font-size: 1.15rem;">Product Information</h5>

            <div class="card-body">
                <div class="mb-4">
                    <img src="{{ $product->image_name }}"
                         alt="product-image"
                         class="rounded"
                         style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #d9dee3;"
                         onError="this.onerror=null;this.src='{{ asset('/assets/img/blank-product.svg') }}';">
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="product-info-label">Product Name :</span>
                            <span class="product-info-value">{{ $product->product_name ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Product Code :</span>
                            <span class="product-info-value">{{ $product->product_code ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Brand :</span>
                            <span class="product-info-value">{{ $product->brand->name ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Color :</span>
                            <span class="product-info-value">{{ $product->color->name ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Storage :</span>
                            <span class="product-info-value">{{ $product->storage->name ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Battery Percentage :</span>
                            <span class="product-info-value">{{ $product->battery_percentage ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Purchase Date :</span>
                            <span class="product-info-value">{{ $product->purchase_date ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Selling Price :</span>
                            <span class="product-info-value">{{ $product->selling_price ?? '' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <span class="product-info-label">Product IMEI :</span>
                            <span class="product-info-value">{{ $product->product_imei ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Condition :</span>
                              @if ($product->condition == 1)
                                <span class="product-info-value">Used</span>
                              @else
                                <span class="product-info-value">New</span>
                              @endif
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Series :</span>
                            <span class="product-info-value">{{ $product->series->name ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Model :</span>
                            <span class="product-info-value">{{ $product->modelType->name ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Type of Machine :</span>
                            @if ($product->type_of_machine == 1)
                              <span class="product-info-value">iCloud</span>
                            @elseif ($product->type_of_machine == 2)
                              <span class="product-info-value">Unlock</span>
                            @elseif ($product->type_of_machine == 3)
                              <span class="product-info-value">Original</span>
                            @else
                              <span class="product-info-value">Sim Lock</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Product Percentage :</span>
                            <span class="product-info-value">{{ $product->percentage ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Purchase Price :</span>
                            <span class="product-info-value">{{ $product->purchase_price ?? '' }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="product-info-label">Product Status :</span>
                            @if ($product->status == 1)
                              <span class="product-info-value text-capitalize">Instock</span>
                            @elseif ($product->status == 2)
                              <span class="product-info-value text-capitalize">Sold</span>
                            @elseif ($product->status == 3)
                              <span class="product-info-value text-capitalize">Load</span>
                            @else
                              <span class="product-info-value text-capitalize">Broken</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 mt-2">
                        <div class="mb-3">
                            <span class="product-info-label">Product Note :</span>
                            <span class="product-info-value">{{ $product->notes ?? '' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body border-top">
                <div class="d-flex gap-2">
                    <a href="{{ route('products.index', withLang()) }}" class="btn btn-outline-secondary">
                        Product Lists
                    </a>
                    <a href="{{ route('products.edit', array_merge(['product' => $product->id], withLang())) }}" class="btn btn-primary" style="background-color: #696cff; border-color: #696cff;">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
