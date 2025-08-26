@extends('landing.layouts.master')

@section('head')
    @include('meta::manager', [
        'title' => 'উন্নত মানের ইলেকট্রিক বিল রিডিউসার',
    ])
    <link rel="stylesheet" href="{{ asset('OwlCarousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('OwlCarousel/dist/assets/owl.theme.default.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('landing/output.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('landing/input.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/style.css') }}">
    @vite('resources/landing/css/app.css', 'build-landing')
    @include('landing.animate')
@endsection

@php
    $product = (object) $products_inventory[0];
    $fb_contents = [];
@endphp

@foreach ($products_inventory as $product)
    @php
        $fb_contents[] = [
            'id' => $product['id'],
            'quantity' => 1,
        ];
    @endphp
@endforeach

@section('master')
    @include('landing.content.index')

    <div class="order_form bg-[#f4f8fa] py-10">
        @include('landing.layouts.order-form-saas')
    </div>
@endsection
@section('footer')
    @if (env('APP_FB_TRACK'))
        <script>
            fbq('track', 'InitiateCheckout', {
                value: {{ round($products_inventory[0]['product_data']['sale_price'] / 109, 2) }},
                currency: 'USD',
                contents: @json([
                    'id' => $products_inventory[0]['product_data']['id'],
                    'quantity' => 1,
                ]),
                content_ids: @json([$products_inventory[0]['product_data']['id']]),
            });
        </script>
    @endif
    @if (env('APP_FB_TRACK') && env('PIXEL_ID'))
        <script>
            $(window).on('load', function() {
                $.ajax({
                    type: "POST",
                    url: "{{ route('fbTrackLanding') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        track_type: 'InitiateCheckout',
                        currency: 'BDT',
                        content_type: 'product',
                        content_ids: @json([$products_inventory[0]['product_data']['id']]),
                        contents: @json($fb_contents),
                        value: '{{ $products_inventory[0]['product_data']['sale_price'] }}'
                    },
                    success: function(response) {},
                    error: function() {}
                });
            });

            setTimeout(function() {
                tCI('25_sec_onpage');
            }, 25000);

            $(window).on("scroll", function() {
                var scrollTop = $(window).scrollTop();
                var windowHeight = $(window).height();
                var docHeight = $(document).height();

                var scrollPercent = (scrollTop / (docHeight - windowHeight)) * 100;

                if (scrollPercent >= 50 && !scrolled_50) {
                    scrolled_50 = true;
                    tCI('scroll_50');
                }

                if (scrollPercent >= 90 && !scrolled_90) {
                    scrolled_90 = true;
                    tCI('scroll_90');
                }
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const $button = $(".shakeBtn");
            setInterval(function() {
                $button.addClass("shake");
                setTimeout(function() {
                    $button.removeClass("shake");
                }, 500); // Match the duration of the shake animation
            }, 5000); // Trigger every 5 seconds

            $('.animated_text').addClass('active');
            setInterval(function() {
                setTimeout(function() {
                    $('.animated_text').removeClass('active');
                    $('.custom-headline').addClass('e-hide-highlight');
                }, 100);

                setTimeout(function() {
                    $('.animated_text').addClass('active');
                    $('.custom-headline').removeClass('e-hide-highlight');
                }, 3000);
            }, 3500);

            function updateSelectedProducts() {
                const container = $('#selected-products');
                const selected = $('.product-checkbox:checked');
                let delivery_charge = $('.delivery_charge_inpout').val();
                let total_price = 0
                let sub_total_price = 0
                let products = [];

                if (selected.length === 0) {
                    $('.grand_total').text(total_price);
                    container.html('<p class="text-sm text-black">কোনো পণ্য সিলেক্ট করা হয়নি</p>');
                    return;
                }

                let html = '<ul class="space-y-2">';
                selected.each(function() {
                    const id = $(this).data('id');
                    const title = $(this).data('title');
                    const price = parseFloat($(this).data('price')).toFixed(2);
                    const img = $(this).data('image');
                    products.push({
                        product_data_id: id,
                        product_title: title,
                        selling_price: price,
                        quantity: 1,
                        price: price
                    });
                    total_price += parseFloat($(this).data('price'));
                    sub_total_price += parseFloat($(this).data('price'));
                    html += `
                    <li class="flex items-center gap-3 text-black ">
                        <img src="${img}" class="w-10 h-10 rounded object-cover" />
                        <div class="flex justify-between w-full">
                            <p class="text-base font-medium">${title}</p>
                            <p class="text-base">৳${price}</p>
                        </div>
                    </li>`;
                });
                html += '</ul>';
                $('.products_data').val(JSON.stringify(products));

                container.html(html);
                $('.product_price').text(sub_total_price);
                $('.grand_total').text(Number(total_price) + Number(delivery_charge));
            }
            $('.product-checkbox').on('change', updateSelectedProducts);
            updateSelectedProducts();

        });

        $(document).on('change', '.change_area', function() {
            calculateDelivery(this.value);
        });

        function calculateDelivery(area) {
            let sub_total = Number($('.product_price').text());
            let total = 0;

            if (area == 'Inside Dhaka') {
                let delivery_charge = 0;
                $('.delivery_charge_inpout').val(delivery_charge);
                $('.shipping_price').text(delivery_charge);
                total = sub_total + delivery_charge;
            }
            if (area == 'Outside Dhaka') {
                let delivery_charge = 0;
                $('.delivery_charge_inpout').val(delivery_charge);
                $('.shipping_price').text(delivery_charge);
                total = sub_total + delivery_charge;
            }

            $('.grand_total').text(total);
        }
        $(document).ready(function() {
            calculateDelivery('Inside Dhaka');
        });
    </script>
@endsection
