@extends('landing.layouts.master')

@section('head')
@include('meta::manager', [
    'title' => 'Order Success'
])
@endsection

@section('master')

<div class="bg-[#f8f6f9]">
    <div class="bg-[#ee2261] pt-4 md:pt-12 text-white font-semibold text-xl md:text-4xl pb-32 rounded-b-full text-center">
        <div class="container">
            <p>ধন্যবাদ স্যার,</p>
            <p class="mt-3">আপনার অর্ডারকৃত ফোনটি সফলভাবে অর্ডার হয়েছে।</p>
            <p class="md:text-xl mt-5">আমাদের একজন প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করে বিস্তারিত জানিয়ে দিবে!</p>
        </div>
    </div>

    <div class="container mt-6 pb-16">
        <div class="bg-white rounded-xl shadow md:mx-12 p-5 -mt-32  pb-16">
            <h3 class="text-xl font-medium mb-2">Your order has been received!</h3>
            <div class="bg-[#f8f6f9] p-2 mb-8 rounded">
                <ul class="lg:flex text-gray-700">
                    <li class="w-auto flex-1 md:border-r border-r-gray-400 border-dotted px-2 mb-3 md:mb-0">
                        Order Number
                        <br>
                        <b>{{date('F d, Y', strtotime($order->created_at))}}</b>
                    </li>
                </ul>
            </div>
            <p>Pay with cache on delivery.</p>
        </div>
    </div>
    <div class=" pb-16">
        <div class=" pb-16"></div>
    </div>
</div>
@endsection
