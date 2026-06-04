@extends('layouts.app')

@push('styles')
<style>
    /* POS Fluid Grid Screen Constraint Controls */
    .pos-layout-wrapper {
        display: flex;
        height: calc(100vh - 170px);
        gap: 1rem;
        overflow: hidden;
    }
    .pos-vertical-nav {
        width: 85px;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        flex-shrink: 0;
    }
    .pos-nav-block {
        width: 85px;
        height: 85px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
    }
    .pos-main-showroom {
        flex-grow: 1;
        overflow-y: auto;
        padding-right: 0.25rem;
    }
    .pos-main-showroom > .flex-grow-1 {
        overflow-y: auto;

        /* Hide scrollbar for Chrome, Safari and Opera */
        &::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .pos-device-card {
        cursor: pointer;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .pos-device-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(67, 89, 113, 0.15) !important;
    }
    .pos-img-frame {
        width: 100%;
        height: 150px;
        background-color: #f5f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .pos-img-frame img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .pos-checkout-panel {
        width: 360px;
        display: flex;
        flex-direction: column;
        height: 100%;
        flex-shrink: 0;
    }
    .pos-basket-items {
        flex-grow: 1;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <form action="{{ route('ordercustomers.store', withLang()) }}" method="POST" id="pos-order-form">
        @csrf

        <div class="pos-layout-wrapper">

            <div class="pos-vertical-nav">
                <button type="button" class="pos-nav-block btn btn-primary p-0 d-flex flex-column align-items-center justify-content-center">
                    <i class='bx bx-search fs-3 mb-1'></i>
                    <span>Search</span>
                </button>
                <button type="button" class="pos-nav-block btn btn-primary p-0 d-flex flex-column align-items-center justify-content-center">
                    <i class='bx bx-grid-alt fs-3 mb-1'></i>
                    <span>All Phones</span>
                </button>
                <button type="button" class="pos-nav-block btn btn-outline-secondary bg-white p-0 d-flex flex-column align-items-center justify-content-center text-secondary border">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/Apple_logo_black.svg" style="width: 26px; height: 26px; object-fit: contain;" class="mb-1" alt="Apple">
                    <span>APPLE</span>
                </button>
            </div>

            <div class="pos-main-showroom d-flex flex-column">
                <div class="mb-3">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text text-muted"><i class="bx bx-search fs-5"></i></span>
                        <input type="text" id="catalog-search" class="form-control" placeholder="Search by model, IMEI, description...">
                    </div>
                </div>

                <div class="flex-grow-1">
                    <div class="row g-3" id="catalog-grid">
                        @forelse($products as $product)
                            <div class="col-6 col-sm-4 col-md-3 catalog-item"
                                data-product-id="{{ $product->id }}"
                                data-name="{{ strtolower($product->product_name) }}"
                                data-imei="{{ $product->product_imei }}">

                                <div class="card h-100 pos-device-card border"
                                     onclick="addToCart({ id: {{ $product->id }}, name: '{{ $product->product_name }}', imei: '{{ $product->product_imei }}', price: {{ (float)$product->purchase_price }} })">

                                    <div class="pos-img-frame card-img-top">
                                        <img src="{{ asset($product->image_name) }}" alt="Phone image" onError="this.onerror=null;this.src='{{ asset('/assets/img/blank-product.svg') }}';">
                                    </div>

                                    <div class="card-body p-2">
                                        <h6 class="fw-bold text-dark text-truncate mb-1" style="font-size: 0.85rem;">
                                            {{ $product->product_name }}
                                            <span class="text-secondary font-monospace d-block small mt-0.5">[ {{ $product->product_imei }} ]</span>
                                        </h6>
                                        <p class="text-muted small text-truncate mb-2">
                                            {{ $product->condition == 1 ? 'Used' : 'New' }},
                                            {{ $product->storage?->name ?? 'N/A' }},
                                            {{ $product->color?->name ?? 'N/A' }},
                                            {{ [1 => 'iCloud', 2 => 'Unlock', 3 => 'Original', 4 => 'Sim Lock'][$product->type_of_machine] ?? 'N/A' }}
                                        </p>
                                        <h5 class="fw-bold text-primary mb-0">{{ setToStringDolla($product->purchase_price) }}</h5>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class='bx bx-package fs-1 mb-2 d-block text-light'></i>
                                <p>No stock available on the retail floor showroom right now.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="card pos-checkout-panel bg-white border">
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.05rem;">
                            Order: <span class="text-dark">#{{ str_pad($nextInvoiceId ?? 1, 5, '0', STR_PAD_LEFT) }}</span>
                        </span>
                    </div>
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="bx bx-user text-muted"></i></span>
                        <select class="form-select" name="customer_id" id="customer_id" required>
                            <option value="">Walk-in Customer (លក់រាយ)</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="pos-basket-items p-3" id="cart-container">
                    <div class="text-center text-muted my-5" id="empty-cart-msg">
                        <i class='bx bx-shopping-bag fs-1 mb-2 d-block text-light'></i>
                        <span class="small">Cart is Empty</span>
                    </div>
                </div>

                <div class="border-top p-3 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-secondary text-uppercase small mb-0" style="font-size: 0.75rem;">Total</h6>
                        <h4 class="fw-bold text-dark mb-0">$ <span id="cart-total-display">0.00</span></h4>
                    </div>
                    <input type="hidden" name="total_amount" id="total_amount_input" value="0">

                    <div class="row g-2">
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-secondary w-100 py-2 d-flex flex-column align-items-center justify-content-center h-100" id="btn-bill" disabled>
                                <i class='bx bx-receipt fs-4 mb-0.5'></i>
                                <span class="small" style="font-size: 0.7rem; font-weight: 600;">BILL</span>
                            </button>
                        </div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-primary w-100 py-2 d-flex flex-column align-items-center justify-content-center h-100" id="btn-submit" disabled>
                                <i class='bx bx-cloud-upload fs-4 mb-0.5'></i>
                                <span class="small" style="font-size: 0.75rem; font-weight: 700;">SUBMIT ORDER</span>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('script')
<script>
    let cart = [];

    function hideProductCard(productId) {
        const card = document.querySelector(
            `.catalog-item[data-product-id="${productId}"]`
        );

        if (card) {
            card.classList.add('cart-hidden');
        }
    }

    function showProductCard(productId) {
        const card = document.querySelector(
            `.catalog-item[data-product-id="${productId}"]`
        );

        if (card) {
            card.classList.remove('cart-hidden');
        }
    }

    function addToCart(product) {
        const exists = cart.some(item => item.id === product.id);

        if (exists) {
            alert('This device unit is already in the cart.');
            return;
        }

        cart.push(product);

        hideProductCard(product.id);

        renderCart();
    }

    function removeFromCart(productId) {
        showProductCard(productId);

        cart = cart.filter(item => item.id !== productId);

        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-container');
        const totalDisplay = document.getElementById('cart-total-display');
        const totalInput = document.getElementById('total_amount_input');
        const btnBill = document.getElementById('btn-bill');
        const btnSubmit = document.getElementById('btn-submit');

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted my-5">
                    <i class='bx bx-shopping-bag fs-1 mb-2 d-block text-light'></i>
                    <span class="small">Cart is Empty</span>
                </div>
            `;

            totalDisplay.innerText = '0.00';
            totalInput.value = 0;

            btnBill.disabled = true;
            btnSubmit.disabled = true;

            return;
        }

        let runningTotal = 0;
        let html = '';

        cart.forEach((item, index) => {
            runningTotal += Number(item.price);

            html += `
                <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark" style="font-size:0.85rem;">
                            ${item.name}
                        </h6>

                        <small class="text-muted font-monospace small">
                            IMEI: ${item.imei}
                        </small>

                        <input type="hidden"
                               name="items[${index}][product_id]"
                               value="${item.id}">

                        <input type="hidden"
                               name="items[${index}][price]"
                               value="${item.price}">
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-semibold text-dark small">
                            $${Number(item.price).toFixed(2)}
                        </span>

                        <button
                            type="button"
                            class="btn btn-sm btn-icon btn-outline-danger p-1"
                            onclick="removeFromCart(${item.id})"
                            style="width:28px;height:28px;"
                        >
                            <i class="bx bx-trash text-danger"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;

        totalDisplay.innerText = runningTotal.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        totalInput.value = runningTotal;

        btnBill.disabled = false;
        btnSubmit.disabled = false;
    }

    document.getElementById('catalog-search').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();

        document.querySelectorAll('.catalog-item').forEach(card => {
            const name = card.dataset.name || '';
            const imei = card.dataset.imei || '';

            const matched =
                name.includes(query) ||
                imei.includes(query);

            if (matched) {
                card.classList.remove('d-none');
            } else {
                card.classList.add('d-none');
            }
        });
    });

    document.getElementById('pos-order-form')
        .addEventListener('submit', function() {
            cart = [];
        });
</script>
@endpush
