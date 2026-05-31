@extends('layouts.app')

@push('styles')
{{-- Custom CSS removed to utilize default system styling --}}
@endpush

@section('content')
<div class="content-wrapper">
    <div class="container-fluid flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">

                {{-- Global Error Alert --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Unified route passing using array_merge to combine model id with language helper arrays --}}
                <form id="formEditProduct" method="POST" action="{{ route('products.update', array_merge(['product' => $product->id], withLang())) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="card mb-4">
                        <h5 class="card-header text-primary" style="font-size: 1.15rem;">Edit Product</h5>

                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                                <div class="text-center">
                                    {{-- Displays saved image path via Storage or falls back to your generic asset fallback placeholder --}}
                                    <img src="{{ $product->image_name }}"
                                      alt="product-image"
                                      id="productImagePreview"
                                      class="rounded"
                                      style="width: 100px; height: 100px; object-fit: cover;"
                                      onError="this.onerror=null;this.src='{{ asset('/assets/img/blank-product.svg') }}';">
                                </div>
                                <div class="button-wrapper">
                                    <label for="upload_image" class="btn btn-primary me-2 mb-2" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload_image" name="product_image" class="account-file-input" hidden accept="image/png, image/jpeg, image/gif">
                                    </label>
                                    <button type="reset" class="btn btn-outline-secondary account-image-reset mb-2" id="resetImage">
                                        <span>Reset</span>
                                    </button>
                                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Allowed JPG, GIF or PNG.</p>
                                    @error('product_image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="product_name">Product Name</label>
                                    <input type="text" id="product_name" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name', $product->product_name) }}" autofocus>
                                    @error('product_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="product_imei">Product IMEI</label>
                                    <input type="text" id="product_imei" name="product_imei" class="form-control @error('product_imei') is-invalid @enderror" value="{{ old('product_imei', $product->product_imei) }}">
                                    @error('product_imei') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="product_code">Product Code</label>
                                    <input type="text" id="product_code" name="product_code" class="form-control @error('product_code') is-invalid @enderror" value="{{ old('product_code', $product->product_code) }}">
                                    @error('product_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="condition">Condition</label>
                                    <select id="condition" name="condition" class="form-select @error('condition') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @foreach ($condition ?? [] as $id => $name)
                                          <option value="{{ $id }}" {{ old('condition', $product->condition) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="brand">Brand</label>
                                    <select id="brand" name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @foreach($brands ?? [] as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('brand_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="series">Series</label>
                                    <select id="series" name="series_id" class="form-select @error('series_id') is-invalid @enderror" disabled>
                                        <option value="">Select an option</option>
                                        @foreach($all_series ?? [] as $item)
                                            <option value="{{ $item->id }}" data-brand="{{ $item->brand_id }}" class="series-option" style="display: none;" {{ old('series_id', $product->series_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('series_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="color">Color</label>
                                    <select id="color" name="color_id" class="form-select @error('color_id') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @foreach($colors ?? [] as $color)
                                            <option value="{{ $color->id }}" {{ old('color_id', $product->color_id) == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('color_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="model_type_id">Model</label>
                                    <select id="model_type_id" name="model_type_id" class="form-select @error('model_type_id') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @foreach($models ?? [] as $model)
                                            <option value="{{ $model->id }}" {{ old('model_type_id', $product->model_type_id) == $model->id ? 'selected' : '' }}>{{ $model->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('model_type_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="storage">Storage</label>
                                    <select id="storage" name="storage_id" class="form-select @error('storage_id') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @foreach($storages ?? [] as $storage)
                                            <option value="{{ $storage->id }}" {{ old('storage_id', $product->storage_id) == $storage->id ? 'selected' : '' }}>{{ $storage->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('storage_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label" for="type_of_machine">Type of Machine</label>
                                            <select id="type_of_machine" name="type_of_machine" class="form-select @error('type_of_machine') is-invalid @enderror">
                                              <option value="">Select an option</option>
                                              @foreach ($type_of_machines ?? [] as $id => $name)
                                                <option value="{{ $id }}" {{ old('type_of_machine', $product->type_of_machine) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                              @endforeach
                                            </select>
                                            @error('type_of_machine') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label" for="network_id">Lock By</label>
                                            <select id="network_id" name="network_id" class="form-select @error('network_id') is-invalid @enderror" disabled>
                                                <option value="">Select an option</option>
                                                @foreach ($lock_types ?? [] as $lock_type)
                                                <option value="{{ $lock_type->id }}" class="lock-option" style="display: none;" {{ old('network_id', $product->network_id) == $lock_type->id ? 'selected' : '' }}>
                                                  {{ $lock_type->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('network_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="battery_percentage">Battery Percentage</label>
                                    <div class="input-group input-group-merge">
                                        <input type="number" id="battery_percentage" name="battery_percentage" class="form-control @error('battery_percentage') is-invalid @enderror" value="{{ old('battery_percentage', $product->battery_percentage) }}">
                                        <span class="input-group-text">%</span>
                                        @error('battery_percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="percentage">Product Percentage</label>
                                    <div class="input-group input-group-merge">
                                        <input type="number" id="percentage" name="percentage" class="form-control @error('percentage') is-invalid @enderror" value="{{ old('percentage', $product->percentage) }}">
                                        <span class="input-group-text">%</span>
                                        @error('percentage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="purchase_price">Purchase Price</label>
                                    <div class="input-group input-group-merge">
                                        <input type="number" step="0.01" id="purchase_price" name="purchase_price" class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price', $product->purchase_price) }}">
                                        <span class="input-group-text">$</span>
                                        @error('purchase_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="selling_price">Selling Price</label>
                                    <div class="input-group input-group-merge">
                                        <input type="number" step="0.01" id="selling_price" name="selling_price" class="form-control @error('selling_price') is-invalid @enderror" value="{{ old('selling_price', $product->selling_price) }}">
                                        <span class="input-group-text">$</span>
                                        @error('selling_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="purchase_date">Purchase Date</label>
                                    <input type="date" id="purchase_date" name="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" value="{{ old('purchase_date', $product->purchase_date) }}">
                                    @error('purchase_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="status">Product Status</label>
                                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="">Select an option</option>
                                        @foreach ($product_statuses ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ old('status', $product->status) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-body border-top">
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">{{__('button.update') ?? 'Update'}}</button>
                                <a href="{{ route('products.index', withLang()) }}" class="btn btn-outline-secondary">{{__('button.cancel') ?? 'Cancel'}}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        const defaultImg = $('#productImagePreview').attr('src');

        // Initial setup for populated Edit forms
        function initializeDependentFields() {
            var brandID = $('#brand').val();
            var series = $('#series');
            var selectedSeriesId = "{{ old('series_id', $product->series_id) }}";

            if (brandID !== '') {
                series.prop("disabled", false);
                $.ajax({
                    type: 'GET',
                    url: '/en/series/brand/' + brandID,
                    dataType: 'json',
                    success: function(data) {
                        series.empty().append('<option value="">Select an option</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                var isSelected = (value.id == selectedSeriesId) ? 'selected' : '';
                                series.append('<option value="' + value.id + '" ' + isSelected + '>' + value.name + '</option>');
                            });
                        }
                    }
                });
            }

            var selectedType = $('#type_of_machine').val();
            var lockDropdown = $('#network_id');
            if (selectedType !== '') {
                lockDropdown.prop('disabled', false);
                $('.lock-option').show();
            }
        }

        // Run checking filters on initialization load
        initializeDependentFields();

        // Handling live client-side image preview
        $("#upload_image").on("change", function(e){
            var file = e.target.files[0];
            if (file) {
                getBase64(file).then(base64Data => {
                    $('#productImagePreview').attr('src', base64Data);
                });
            }
        });

        // Form reset handler back to database stored file path
        $('#resetImage').click(function(){
            $('#upload_image').val('');
            $('#productImagePreview').attr('src', defaultImg);
        });

        // Dynamic pairing for Brand -> Series changes
        $('#brand').change(function() {
            var brandID = $(this).val();
            var series = $('#series');

            if (brandID !== '') {
                series.prop("disabled", false);
                $.ajax({
                    type: 'GET',
                    url: '/en/series/brand/' + brandID,
                    dataType: 'json',
                    success: function(data) {
                        series.empty().append('<option value="">Select an option</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                series.append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    }
                });
            } else {
                series.empty().append('<option value="">Select an option</option>');
                series.prop("disabled", true);
            }
        });

        $('#type_of_machine').change(function() {
            var selectedType = $(this).val();
            var lockDropdown = $('#network_id');

            if (selectedType !== '') {
                lockDropdown.prop('disabled', false).val('');
                $('.lock-option').show();
            } else {
                $('.lock-option').hide();
                lockDropdown.prop('disabled', true).val('');
            }
        });
    });

    function getBase64(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = () => resolve(reader.result);
            reader.onerror = error => reject(error);
        });
    }
</script>
@endpush
