

<div class=" text-black opacity-0 hidden">
    <p class="text-lg  font-semibold mb-4">কালার সিলেক্ট করুন</p>

    <div id="product-list" class="bg-white text-black rounded-lg shadow-md overflow-hidden">
        {{-- @php
             dd($products_inventory);
        @endphp --}}
        @foreach ($products_inventory as $product)
    @php
        $products_data[] = [
            'id' => $product['id'],
            'quantity' => 1
        ];
        $content_ids[] = $product['id'];
    @endphp

    <label class="flex items-center gap-4 p-4 border-b last:border-b-0 cursor-pointer hover:bg-gray-100 transition">
        @if($product['type'] === 'Variable')
            @php
                $variant = $product['variable_product_data'][0] ?? null;
            @endphp

            <input type="checkbox"
                name="products[]"
                value="{{ $variant['id'] }}"
                class="form-checkbox product-checkbox accent-green-600 w-5 h-5 mt-1"
                data-id="{{ $variant['id'] }}"
                data-title="{{ $product['title']}}"
                data-price="{{ $variant['sale_price'] ?? '' }}"
                data-image="{{ $product['img_paths']['small'] }}"
                @if($loop->first) checked @endif
            >

            <img src="{{ $product['img_paths']['small'] }}"
                 alt="product"
                 class="w-12 h-12 rounded-md object-cover">

            <div class="flex-1">
                <div class="font-bold text-sm">{{ $product['title'] }}</div>
            </div>

            <div class="font-semibold whitespace-nowrap">
                ৳{{ number_format($variant['sale_price'] ?? 0, 2) }}
            </div>

        @else
            <input type="checkbox"
                name="products[]"
                value="{{ $product['product_data']['id'] }}"
                class="form-checkbox product-checkbox accent-green-600 w-5 h-5 mt-1"
                data-id="{{ $product['product_data']['id'] }}"
                data-title="{{ $product['title'] }}"
                data-price="{{ $product['product_data']['sale_price'] }}"
                data-image="{{ $product['img_paths']['small'] }}"
                @if($loop->first) checked @endif
            >

            <img src="{{ $product['img_paths']['small'] }}"
                 alt="product"
                 class="w-12 h-12 rounded-md object-cover">

            <div class="flex-1">
                <div class="font-bold text-sm">{{ $product['title'] }}</div>
            </div>

            <div class="font-semibold whitespace-nowrap">
                ৳{{ number_format($product['sale_price'] ?? 0, 2) }}
            </div>
        @endif
    </label>
@endforeach

    </div>

</div>
