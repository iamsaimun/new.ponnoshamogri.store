@extends('landing.layouts.master')

@section('head')
@include('meta::manager', [
'title' => 'Order Success - ' . ($settings_g['title'] ?? ''),
])
@endsection

@section('master')

@php
$products = [];
$content_ids = [];
@endphp
<div class="bg-white pt-10">
    <div class="container pb-8 md:pb-16 bg-[#FF9C00]">
        <h3 class="text-3xl md:text-5xl text-center font-semibold mb-5">Thank you</h3>
        <p class="text-xl md:text-2xl font-semibold text-center md:px-64 text-gray-500 md:mb-7">আলহামদুলিল্লাহ আপনার অর্ডার কনফার্ম করা হয়েছে, <span class="text-[#048a00]">সেইম প্রোডাক্ট অন্য পেজে অর্ডার করা থেকে বিরত থাকুন,</span> আপনার অর্ডারকৃত প্রোডাক্ট খুব দ্রুত ডেলিভারি করা হবে ইনশাআল্লাহ।
        </p>
    </div>

    <div class="container pb-16">
        <div class="bg-white rounded-xl shadow border md:mx-12 p-5">
            <h3 class="text-xl font-medium mb-2">Your order has been received!</h3>
            <div class="bg-[#f8f6f9] p-2 mb-8 rounded">
                <ul class="lg:flex text-gray-700">
                    <li class="w-auto flex-1 md:border-r border-r-gray-400 border-dotted px-2 mb-3 md:mb-0">
                        Order Date
                        <br>
                        <b>{{date('F d, Y', strtotime($order['created_at']))}}</b>
                    </li>
                    <li class="w-auto flex-1 px-2 mb-3 md:mb-0">
                        Total
                        <br>
                        <b>{{$order['grand_total']}} Tk</b>
                    </li>
                </ul>
            </div>
            <p>পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন।</p>
            <div class="overflow-auto">
                <table class="w-full border text-left mb-8">
                    <thead class="border-b">
                        <tr>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Product
                            </th>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Price
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order['order_products'] as $i => $product)
                        @php

                        $products[] = [
                        'id' => $product['product_id'],
                        'quantity' => $product['quantity']
                        ];
                        $content_ids[] = $product['product_id'];
                        @endphp

                        <tr class="border-b">
                            <td class="px-3 py-2 text-sm text-gray-900 border-r font-light">
                                <p class="text-lg">{{ $product['product']['title'] ?? 'n/a' }}</p>
                                <p class="mb-0"><small>{{$product['product_data']['attribute_items_string']}}</small></p>
                                <p class="text-lg">৳ {{ $product['sale_price'] }} x {{$product['quantity']}}</p>
                            </td>
                            <td class="text-gray-900 font-light px-3 py-2 whitespace-nowrap border-r text-lg">
                                ৳ {{$product['sale_price'] * $product['quantity']}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="border-b">
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Delivery Cost
                            </th>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                ৳ {{$order['shipping_charge']}}
                            </th>
                        </tr>
                        <tr class="border-b">
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Total
                            </th>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                ৳ {{$order['grand_total']}}
                            </th>
                        </tr>
                        <tr>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Payment Method
                            </th>
                            <th scope="col" class="text-sm font-semibold text-gray-900 px-3 py-2 border-r">
                                Cash on Delivery
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@if(env('APP_FB_TRACK') && $track)
<script>
    fbq('track', 'Purchase', {
        value: {
            {
                round($order['grand_total'] / 109, 2)
            }
        },
        currency: 'USD',
        contents: @json($products),
        content_ids: @json($content_ids)
    }, {
        eventID: '{{$order["id"]}}'
    });
</script>

@if(env('PIXEL_ID') && env('PIXEL_ID'))
<script>
    $(window).on('load', function() {
        $.ajax({
            type: "POST",
            url: "{{ route('fbTrackLanding') }}",
            data: {
                _token,
                track_type: 'Purchase',
                currency: 'BDT',
                content_type: 'product',
                content_ids: @json($content_ids),
                contents: @json($products),
                event_id: '{{$order["id"]}}',
                value: '{{$order["grand_total"]}}',
                phone: '{{$order["shipping_mobile_number"]}}',
                name: '{{hash("sha256", $order["shipping_full_name"])}}',
                external_id: '{{hash("sha256", $order["id"])}}'
            },
            success: function(response) {},
            error: function() {}
        });
    });
</script>
@endif
@endif
@endsection