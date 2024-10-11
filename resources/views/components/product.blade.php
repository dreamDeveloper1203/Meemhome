@props(['product'])

<div class="card w-100 border fw-montserrat shadow-none bg-white"style="border-radius: 1.5rem;">
    <div class="rounded-0 @if ($product->has_discount) position-relative @endif">
        <!--<picture>-->
        <!--    <source type="image/jpg" data-srcset="{{ $product->thumbnail_image_url }}" />-->
        <!--    <img alt="{{ $product->name }}" data-src="{{ $product->thumbnail_image_url }}" aria-hidden="true"-->
        <!--        class="lazy w-100 h-100 loading border-0" style="border-radius: 1.5rem 1.5rem 0 0;"/>-->
        <!--</picture>-->
        <picture>
    <source type="image/jpg" data-srcset="{{ $product->thumbnail_image_url }}" />
    <img alt="{{ $product->name }}" data-src="{{ $product->thumbnail_image_url }}" aria-hidden="true"
         class="lazy w-100 h-100 loading border-0" style="border-radius: 1.5rem 1.5rem 0 0;"
         data-srcset="{{ $product->thumbnail_image_url }}" />
</picture>
        @if ($product->has_discount)
            <span class="badge bg-danger position-absolute top-0 end-0 rounded"> Sale</span>
        @endif
        @if (!$product->has_discount && $product->is_new)
            <span class="badge bg-success position-absolute top-0 end-0 rounded"> New</span>
        @endif
    </div>
    <div class="card-body user-select-none px-3 pb-0">
        <div class="mb-1">
            <div class="d-flex flex-row flex-column flex-sm-row justify-content-between">
            <div class="h4 fw-bold mb-0">
                @if ($product->price)
                    @if ($product->has_discount)
                    <span class="text-decoration-line-through me-2 fw-montserrat">{{ $product->view_original_price }}</span>
                    @endif
                    <span class="@if ($product->has_discount) text-danger fw-montserrat @endif">{{ $product->view_price }}</span>
                @endif
            </div>
            <div class="d-flex justify-content-start align-items-start">
                <div class=" me-2">
                    @for ($i = 0; $i < $product->avg_rating; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            style="width: 1rem; height:1rem;" class="text-warning">
                            <path fill-rule="evenodd"
                                d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z"
                                clip-rule="evenodd" />
                        </svg>
                    @endfor

                    @for ($i = 0; $i < 5 - $product->avg_rating; $i++)
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width: 1rem; height:1rem;" class="text-warning">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                    @endfor
                </div>
                <p class="ff-montserrat text-black"> {{ number_format($product->sum_rating) }}</p>
            </div>
        </div>

            <div class="text-start">
                <a href="{{ $product->url }}"
                    class="card-title m-0 text-black text-decoration-none ff-montserrat link-primary">
                    {{ $product->name }}
                </a>
            </div>
            <!--@if ($product->code)-->
            <!--    <div class="text-muted">-->
            <!--        <span class="small">Product Code: </span>{{ $product->code }}-->
            <!--    </div>-->
            <!--@endif-->

        </div>
    </div>

    <div class="card-footer border-0 bg-body px-0 py-0 text-center d-flex justify-content-end ff-montserrat">
        <!-- Button placed under the price with bottom-right corner radius -->
        <a href="{{ $product->url }}" class="btn btn-primary mt-3 px-4"
            style="border-radius: 1.5rem 0 1.5rem 0; backgroundColor:#282d40;">
           Show Product
        </a>
    </div>
</div>
