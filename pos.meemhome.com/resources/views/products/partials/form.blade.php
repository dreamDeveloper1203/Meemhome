<form action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}" method="POST"
    enctype="multipart/form-data" role="form" onkeydown="return event.key != 'Enter';">
    @csrf
    @isset($product)
        @method('PUT')
    @endisset
    <div class="row">
        <div class="col-md-6 d-flex align-items-stretch mb-3">
            <x-card>

                <x-select label="Category" name="category" :searchable="true">
                    @isset($product)
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @else
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    @endisset
                </x-select>

                <x-input label="Item Name" name="name"
                    value="{{ old('name', isset($product) ? $product->name : '') }}" />


                <x-textarea label="Description" name="description"
                    value="{{ old('description', isset($product) ? $product->description : null) }}" />
            </x-card>
        </div>
        <div class="col-md-6 d-flex align-items-stretch mb-3">
            <x-card>
                <x-select label="status.text" name="status">
                    @isset($product)
                        <option value="available" @if ($product->is_active) selected @endif>
                            @lang('For Sale')
                        </option>
                        <option value="unavailable" @if (!$product->is_active) selected @endif>
                            @lang('Hidden')
                        </option>
                    @else
                        <option value="available">@lang('For Sale')</option>
                        <option value="unavailable">@lang('Hidden')</option>
                    @endisset
                </x-select>
                <x-number-input label="Sort Order" name="sort_order"
                    value="{{ old('sort_order', isset($product) ? $product->sort_order : '') }}" />
            </x-card>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 d-flex align-items-stretch">
            <x-card class="mb-3">
                <div class="card-title h4 text-muted">@lang('Pricing')</div>

                <x-currency-input label="Cost" name="cost"
                    value="{{ old('cost', isset($product) ? $product->cost : '') }}" />
                <!--
                <x-currency-input label="Sale Price" name="sale_price"
                    value="{{ old('sale_price', isset($product) ? $product->sale_price : '') }}" /> -->

                <x-currency-input label="Retailsale Price" name="retailsale_price"
                    value="{{ old('retailsale_price', isset($product) ? $product->retailsale_price : '') }}" />

                <x-currency-input label="Wholesale Price" name="wholesale_price"
                    value="{{ old('wholesale_price', isset($product) ? $product->wholesale_price : '') }}" />


            </x-card>
        </div>
        <div class="col-md-6 d-flex align-items-stretch">
            <x-card class="mb-3">
                <div class="card-title h4 text-muted">@lang('Stock Management')</div>

                <x-stock-input label="In Stock" name="in_stock"
                    value="{{ old('in_stock', isset($product) ? $product->in_stock : '') }}" />

                <x-checkbox label="Track Stock" name="track_stock"
                    checked="{{ isset($product) ? $product->track_stock : true }}" />

                <x-checkbox label="Keep selling when out of stock" name="continue_selling_when_out_of_stock"
                    checked="{{ isset($product) ? $product->continue_selling_when_out_of_stock : true }}" />

                <div class="row">
                    <div class="col-md-6">
                        <x-input label="Retail Barcode" name="retail_barcode"
                            value="{{ old('retail_barcode', isset($product) ? $product->retail_barcode : '') }}"
                            formText="You can also use a scanner" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Retail SKU" name="retail_sku"
                            value="{{ old('retail_sku', isset($product) ? $product->retail_sku : '') }}" />
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <x-input label="Wholesale Barcode" name="wholesale_barcode"
                            value="{{ old('wholesale_barcode', isset($product) ? $product->wholesale_barcode : '') }}"
                            formText="You can also use a scanner" />
                    </div>
                    <div class="col-md-6">
                        <x-input label="Wholesale SKU" name="wholesale_sku"
                            value="{{ old('wholesale_sku', isset($product) ? $product->wholesale_sku : '') }}" />
                    </div>
                </div>

                {{-- add bar code print button --}}
                <div class="row">
                    <div class="col-md-6">
                        <button 
                            type="button" 
                            class="btn btn-primary px-4 d-flex align-items-center mx-3" 
                            {{-- {{ isset($product->unit_barcode ) ? '' : 'disabled' }}  --}}
                            onclick="printBarCode('retail_barcode')"
                        >
                            @lang('Retail Barcode')
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button 
                            type="button" 
                            class="btn btn-primary px-4 d-flex align-items-center mx-3" 
                            {{-- {{ isset($product->box_barcode ) ? '' : 'disabled' }}   --}}
                            onclick="printBarCode('wholesale_barcode')"
                        >
                            @lang('Wholesale Barcode')
                        </button>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
    <x-card class="mb-3">
        <div class="mb-5">
            <label for="image" class="form-label">@lang('Image')</label>
            <input class="form-control @error('image') is-invalid @enderror" name="image" type="file"
                id="image-input" accept="image/png, image/jpeg"
                onchange="previewImage(this, document.getElementById('image-preview-main'))">
            @error('image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @else
                <div id="imageHelp" class="form-text">@lang('Choose an image')</div>
            @enderror
        </div>

        <div class="mb-5 text-center">
            <div class="mb-3">
                @isset($product)
                    <img src="{{ $product->image_url }}" height="250"
                        class="object-fit-cover border rounded  @if (!$product->image_path) d-none @endif"
                        alt="{{ $product->name }}" id="image-preview-main">
                @else
                    <img src="#" height="250" class="object-fit-cover border rounded  d-none" alt="image"
                        id="image-preview-main">
                @endisset
            </div>
            @isset($product)
                @if ($product->image_path)
                    <div class="mb-3">
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#removeCategoryImageModal" data-product-id="{{ $product->id }}">
                            @lang('Remove Image')
                        </button>
                    </div>
                @endif
            @endisset
        </div>
    </x-card>

    <div class="col-md-12 d-flex align-items-stretch mb-3">
        <x-card>
            <div class="card-title h4 text-muted">@lang('Product Variants')</div>

            <div id="variants-section">
                @if (isset($product) && count($product->variants) > 0)
                    @foreach ($product->variants as $index => $variant)
                        <div class="row mb-3" id="variant-{{ $index }}">
                            <input type="hidden" name="variants[{{ $index }}][id]"
                                value="{{ $variant->id }}">
                            <div class="row">
                                <div class="col-md-6 d-flex align-items-stretch">
                                    <x-card class="mb-3">
                                        <div class="card-title h4 text-muted">@lang('Pricing')</div>

                                        <x-currency-input label="Cost" name="variants[{{ $index }}][cost]"
                                            value="{{ old('variants[' . $index . '][cost]', $variant->cost) }}" />

                                        <x-currency-input label="Retailsale Price"
                                            name="variants[{{ $index }}][retailsale_price]"
                                            value="{{ old('variants[' . $index . '][retailsale_price]', $variant->retailsale_price ?? '') }}" />

                                        <x-currency-input label="Wholesale Price"
                                            name="variants[{{ $index }}][wholesale_price]"
                                            value="{{ old('variants[' . $index . '][wholesale_price]', $variant->wholesale_price ?? '') }}" />

                                    </x-card>
                                </div>
                                <div class="col-md-6 d-flex align-items-stretch">
                                    <x-card class="mb-3">
                                        <div class="card-title h4 text-muted">@lang('Stock Management')</div>

                                        <x-stock-input label="In Stock"
                                            name="variants[{{ $index }}][in_stock]"
                                            value="{{ old('variants[' . $index . '][in_stock]', $variant->in_stock ?? '') }}" />

                                        <x-checkbox label="Track Stock"
                                            name="variants[{{ $index }}][track_stock]"
                                            checked="{{ isset($variant) ? $variant->track_stock : true }}" />

                                        <x-checkbox label="Keep selling when out of stock"
                                            name="variants[{{ $index }}][continue_selling_when_out_of_stock]"
                                            checked="{{ isset($variant) ? $variant->continue_selling_when_out_of_stock : true }}" />

                                        <div class="row">
                                            <div class="col-md-6">
                                                <x-input label="Retail Barcode"
                                                    name="variants[{{ $index }}][retail_barcode]"
                                                    value="{{ old('variants[' . $index . '][retail_barcode]', $variant->retail_barcode ?? '') }}"
                                                    formText="You can also use a scanner" />
                                            </div>
                                            <div class="col-md-6">
                                                <x-input label="Retail SKU"
                                                    name="variants[{{ $index }}][retail_sku]"
                                                    value="{{ old('variants[' . $index . '][retail_sku]', $variant->retail_sku ?? '') }}" />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <x-input label="Wholesale Barcode"
                                                    name="variants[{{ $index }}][wholesale_barcode]"
                                                    value="{{ old('variants[' . $index . '][wholesale_barcode]', $variant->wholesale_barcode ?? '') }}"
                                                    formText="You can also use a scanner" />
                                            </div>

                                            <div class="col-md-6">
                                                <x-input label="Wholesale SKU"
                                                    name="variants[{{ $index }}][wholesale_sku]"
                                                    value="{{ old('variants[' . $index . '][wholesale_sku]', $variant->wholesale_sku ?? '') }}" />
                                            </div>
                                        </div>
                                    </x-card>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 d-flex align-items-stretch">
                                    <x-input label="color" name="variants[{{ $index }}][color]"
                                        value="{{ old('variants[' . $index . '][color]', $variant->color ?? '') }}" />
                                </div>
                                <div class="col-md-3 d-flex align-items-stretch">
                                    <x-input label="size" name="variants[{{ $index }}][size]"
                                        value="{{ old('variants[' . $index . '][size]', $variant->size ?? '') }}" />
                                </div>
                            </div>
                            <x-card class="mb-3">
                                <div class="mb-5">
                                    <label for="image" class="form-label">@lang('Image')</label>
                                    <input
                                        class="form-control @error('variants[{{ $index }}][image]') is-invalid @enderror"
                                        name="variants[{{ $index }}][image]" type="file" id="image-input"
                                        onchange="previewImage(this, document.getElementById('image-preview-{{ $index }}'))"
                                        accept="image/png, image/jpeg">
                                    @error('variants[{{ $index }}][image]')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @else
                                        <div id="imageHelp" class="form-text">@lang('Choose an image')</div>
                                    @enderror
                                </div>
                                <div class="mb-5 text-center">
                                    <div class="mb-3">
                                        @isset($variant)
                                            <img src="{{ $variant->image_url }}" height="250"
                                                class="object-fit-cover border rounded  @if (!$variant->image_path) d-none @endif"
                                                alt="{{ $variant->name }}" id="image-preview-{{ $index }}">
                                        @else
                                            <img src="#" height="250"
                                                class="object-fit-cover border rounded  d-none" alt="image"
                                                id="image-preview-{{ $index }}">
                                        @endisset
                                    </div>
                                    @isset($variant)
                                        @if ($variant->image_path)
                                            {{-- <div class="mb-3">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#removeCategoryImageModal{{ $variant->id }}">
                                                    @lang('Remove Image')
                                                </button>
                                            </div> --}}

                                            <div class="mb-3">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal" data-bs-target="#removeCategoryImageModal"
                                                    data-product-id="{{ $variant->id }}">
                                                    @lang('Remove Image')
                                                </button>
                                            </div>
                                        @endif
                                    @endisset
                                </div>
                            </x-card>

                            <div id="variant-{{ $index }}" class="col-md-12">
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="removeVariant({{ json_encode($index) }}, {{ json_encode($variant->id) }})">
                                    @lang('Remove Variant')
                                </button>
                            </div>
                            {{-- <form action="{{ route('products.destroy', $variant->id) }}" method="POST"
                                id="form-{{ $variant->id }}">`
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn btn-link p-0 m-0 w-100 text-start text-decoration-none text-danger align-items-center btn-sm"
                                    onclick="submitDeleteForm({{ json_encode($variant->id) }})">
                                    <x-heroicon-o-trash class="hero-icon-sm me-2 text-gray-400" />
                                    @lang('Delete')
                                </button>
                            </form> --}}
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="text-end">
                <button type="button" class="btn btn-primary btn-sm" onclick="addVariant()">
                    @lang('Add Variant')
                </button>
            </div>
        </x-card>
    </div>

    <div class="mb-3">
        <x-save-btn>
            @lang(isset($product) ? 'Update Item' : 'Save Item')
        </x-save-btn>
    </div>
</form>


{{-- @isset($product)
    <div class="modal" id="removeCategoryImageModal" tabindex="-1" aria-labelledby="removeCategoryImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="removeCategoryImageModalLabel">@lang('Are you sure?')</h5>
                    <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('products.image.destroy', $product) }}" method="POST" role="form">
                    <div class="modal-body">
                        @csrf
                        @method('DELETE')
                        @lang('You cannot undo this action!')
                    </div>
                    <div class="row p-0 m-0 border-top">
                        <div class="col-6 p-0">
                            <button type="button"
                                class="btn btn-link w-100 m-0 text-danger btn-lg text-decoration-none rounded-0 border-end"
                                data-bs-dismiss="modal">@lang('Cancel')</button>
                        </div>
                        <div class="col-6 p-0">
                            <button type="submit"
                                class="btn btn-link w-100 m-0 text-black btn-lg text-decoration-none rounded-0 border-start">
                                @lang('Remove Image')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endisset --}}



<div class="modal" id="removeCategoryImageModal" tabindex="-1" aria-labelledby="removeCategoryImageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="removeCategoryImageModalLabel">@lang('Are you sure?')</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="removeImageForm" method="POST" role="form">
                <div class="modal-body">
                    @csrf
                    @method('DELETE')
                    @lang('You cannot undo this action!')
                </div>
                <div class="row p-0 m-0 border-top">
                    <div class="col-6 p-0">
                        <button type="button"
                            class="btn btn-link w-100 m-0 text-danger btn-lg text-decoration-none rounded-0 border-end"
                            data-bs-dismiss="modal">@lang('Cancel')</button>
                    </div>
                    <div class="col-6 p-0">
                        <button type="submit"
                            class="btn btn-link w-100 m-0 text-black btn-lg text-decoration-none rounded-0 border-start">
                            @lang('Remove Image')
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


@push('script')
    <script>
        let variantIndex = {{ isset($product->variants) ? count($product->variants) : 0 }};

        function addVariant() {
            const variantsSection = document.getElementById('variants-section');
            const newVariantHTML = `
             <div class="row mb-3" id="variant-${variantIndex}">
             <div class="row">
                        <div class="col-md-6 d-flex align-items-stretch">
                            <x-card class="mb-3">
                                <div class="card-title h4 text-muted">@lang('Pricing')</div>

                                <x-currency-input label="Cost" name="variants[${variantIndex}][cost]"
                                    value="{{ old('variants[${variantIndex}][cost]') }}" />

                                <x-currency-input label="Retailsale Price" name="variants[${variantIndex}][retailsale_price]"
                                    value="{{ old('variants[${variantIndex}][retailsale_price]' ?? '') }}" />

                                <x-currency-input label="Wholesale Price" name="variants[${variantIndex}][wholesale_price]"
                                    value="{{ old('variants[${variantIndex}][wholesale_price]' ?? '') }}" />

                            </x-card>
                        </div>
                        <div class="col-md-6 d-flex align-items-stretch">
                            <x-card class="mb-3">
                                <div class="card-title h4 text-muted">@lang('Stock Management')</div>

                                <x-stock-input label="In Stock" name="variants[${variantIndex}][in_stock]"
                                    value="{{ old('variants[${variantIndex}][in_stock]' ?? '') }}" />

                                <x-checkbox label="Track Stock" name="variants[${variantIndex}][track_stock]" />

                                <x-checkbox label="Keep selling when out of stock"
                                    name="variants[${variantIndex}][continue_selling_when_out_of_stock]" />

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input label="Retail Barcode" name="variants[${variantIndex}][retail_barcode]"
                                            value="{{ old('variants[${variantIndex}][retail_barcode]' ?? '') }}"
                                            formText="You can also use a scanner" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-input label="Retail SKU" name="variants[${variantIndex}][retail_sku]"
                                            value="{{ old('variants[${variantIndex}][retail_sku]' ?? '') }}" />
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-input label="Wholesale Barcode" name="variants[${variantIndex}][wholesale_barcode]"
                                            value="{{ old('variants[${variantIndex}][wholesale_barcode]' ?? '') }}"
                                            formText="You can also use a scanner" />
                                    </div>

                                    <div class="col-md-6">
                                        <x-input label="Wholesale SKU" name="variants[${variantIndex}][wholesale_sku]"
                                            value="{{ old('variants[${variantIndex}][wholesale_sku]' ?? '') }}" />
                                    </div>
                                </div>
                            </x-card>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 d-flex align-items-stretch">
                            <x-input label="color" name="variants[${variantIndex}][color]"
                                value="{{ old('variants[${variantIndex}][color]', $variant->color ?? '') }}" />
                        </div>
                        <div class="col-md-3 d-flex align-items-stretch">
                            <x-input label="size" name="variants[${variantIndex}][size]"
                                value="{{ old('variants[${variantIndex}][size]', $variant->size ?? '') }}" />
                        </div>
                    </div>
                    <x-card class="mb-3">
                        <div class="mb-5">
                            <label for="image" class="form-label">@lang('Image')</label>
                            <input class="form-control @error('variants[${variantIndex}][image]') is-invalid @enderror"
                                name="variants[${variantIndex}][image]" type="file" id="image-input" onchange="previewImage(this, document.getElementById('image-preview-${variantIndex}'))"
                                accept="image/png, image/jpeg">
                            @error('variants[${variantIndex}][image]')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @else
                                <div id="imageHelp" class="form-text">@lang('Choose an image')</div>
                            @enderror
                        </div>
                        <div class="mb-5 text-center">
                            <div class="mb-3">                               
                                <img src="#" height="250" class="object-fit-cover border rounded  d-none" alt="image" id="image-preview-${variantIndex}">                                
                            </div>                          
                        </div>
                    </x-card>

                    <div class="col-md-12">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeVariant(${variantIndex})">
                            @lang('Remove Variant')
                        </button>
                    </div>
            `;

            variantsSection.insertAdjacentHTML('beforeend', newVariantHTML);
            variantIndex++;
        }


        function removeVariant(index, variant_id = null) {

            if (variant_id) {
                var deleteUrl = "{{ route('products.destroy', ':id') }}";
                deleteUrl = deleteUrl.replace(':id', variant_id);
                alert(deleteUrl);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });
            }
            const variantElement = document.getElementById(`variant-${index}`);
            if (variantElement) {
                variantElement.remove();
            }
        }

        function submitDeleteForm(id) {
            const form = document.querySelector(`#form-${id}`);
            Swal.fire(swalConfig()).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                } else {
                    topbar.hide();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const removeCategoryImageModal = document.getElementById('removeCategoryImageModal');
            removeCategoryImageModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const productId = button.getAttribute(
                    'data-product-id'); // Extract product ID from data-* attribute

                const form = removeCategoryImageModal.querySelector('#removeImageForm');
                form.action = `/products/${productId}/image`;
            });
        });

        function printBarCode(value) {
            value = $(`Input[name=${value}]`).val();

            let htmlContent = ''
            htmlContent += `
                <image 
                    loading="lazy"
                    src="https://barcode.orcascan.com/?type=code128&format=png&data=${value}" 
                    width="100%"
                    height="50px"
                />
                <h6 style="text-align: center;">${value}</h6>
            `
            let myWindow = window.open("", "BarCodeWindow2", "width=600px; height=800px;");
            myWindow.document.write(htmlContent);
        }


        // document.addEventListener("DOMContentLoaded", function() {
        //     document.getElementById("image-input").onchange = function() {
        //         previewImage(this, document.getElementById("-${variantIndex}"))
        //     };
        // });
    </script>
@endpush
