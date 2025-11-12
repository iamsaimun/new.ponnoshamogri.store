@if(env('APP_SMS_STATUS'))
<div class="bg-black fixed left-0 top-0 w-full z-20 h-full bg-opacity-60 otp_form" style="display: none">
    <div class="w-96 max-w-full mx-auto bg-black mt-5 rounded">
        <h2 class="border-b p-2 font-semibold text-center">নিচে আপনার OTP টি লিখুন!</h2>

        <div class="p-2">
            <br>
            <label>OTP লিখুন</label>
            <input class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:shadow-outline mb-2 border-gray-300 shadow-lg otp_input" type="number" placeholder="Example: 123456" required>

            <div class="text-center my-4">
                <button type="{{env('APP_SMS_STATUS') ? "button" : 'submit'}}" class="text-center rounded-md border-2 border-[#f16334] bg-[#f16334] px-6 py-2 text-base font-medium text-black shadow-sm inline-block check_otp">সাবমিট</button>
                <br>
                <br>
                <p>আপনার OTP টি দিয়ে সাবমিট বাটনে ক্লিক করুন।</p>
                <a href="#" class="underline inline-block order_button">Resend OTP</a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="container">
    <div class="mt-6 p-5 rounded-md border-2 border-[#048a00] text-black" data-scroll-index="2">
        {{-- <div class="text-center mb-4">
            <h2 class=" text-xl font-semibold rounded-md text-black px-6 py-2">অর্ডার করতে আপনার সঠিক তথ্য দিয়ে নিচের ফর্মটি সম্পূর্ণ পূরন করুন।👇</h2>
        </div> --}}

        <form action="{{route('landing.orderSaas')}}" method="POST" class="checkoutForm disableDoubleClickOnSubmit">
            <input name="quantity" type="hidden" value="1" id="single_cart_quantity">
            @csrf
            <input type="hidden" name="delivery_charge" class="delivery_charge_inpout shipping_charge" value="0">
            <input type="hidden" name="products_data" class="products_data">
            @php
            if(session('uu_id__')){
            $uuid = session('uu_id__');
            }else{
            $uuid = \Str::uuid();
            session(['uu_id__' => $uuid]);
            }
            @endphp
            {{-- <input type="hidden" name="uu_id" class="uu_id" value="{{\Str::uuid()}}"> --}}
            <input type="hidden" name="uu_id" class="uu_id" value="{{$uuid}}">
            <input type="hidden" name="url" value="{{url()->full()}}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <h3 class="text-xl font-medium mb-4">Billing & Shipping</h3>

                    <div class="mb-4">
                        <label class="block text-text_color text-sm mb-2">আপনার নাম লিখুন <span class="text-red-700">*</span></label>
                        <input placeholder="আপনার নাম লিখুন" class="appearance-none border border-[#048a00] rounded w-full py-3 px-3 text-gray-700 leading-tight focus:shadow-outline shipping_name focus:outline-none information_field @error('name') border-red-500 @enderror" type="text" name="name" value="{{old('name')}}" @if(isset($errors) && count($errors->all())) autofocus @endif required>

                        @error('name')
                        <span class="invalid-feedback block text-red-500" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-text_color text-sm mb-2">আপনার মোবাইল নাম্বারটি লিখুন <span class="text-red-700">*</span></label>
                        <input class="appearance-none border border-[#048a00] rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none shipping_mobile_number mobile_number information_field @error('address') border-red-500 @enderror" type="number" name="mobile_number" value="{{old('mobile_number')}}" placeholder="আপনার ১১ ডিজিটের মোবাইল নাম্বারটি লিখুন" required>

                        @error('address')
                        <span class="invalid-feedback block text-red-5" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-text_color text-sm mb-2">আপনার ঠিকানা লিখুন <span class="text-red-700">*</span></label>
                        <input class="appearance-none border border-[#048a00] rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none shipping_address information_field @error('mobile_number') border-red-500 @enderror" type="text" name="address" value="{{old('address')}}" placeholder="ঠিকানা লিখুন" required>

                        @error('mobile_number')
                        <span class="invalid-feedback block text-red-5" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>


                    <div class="mb-4">
                        <label class="block text-text_color text-lg mb-2">Shipping </label>
                        <div class="border rounded-md  border-[#048a00] mb-2">
                            <div class="area  flex justify-between items-center mr-3  ">
                                <label class="text-font-color-dark text-sm font-medium  w-full  px-4 py-3 block cursor-pointer">
                                    <input checked type="radio" name="change_area" class=" opacity-0 change_area information_field" value="Inside Dhaka" data-shipping-charge="70"> <span class="relative left-[-20px]">Inside Dhaka</span>

                                </label>
                                <span>৳70</span>
                            </div>

                        </div>
                        <div class="border rounded-md  border-[#048a00]">
                            <div class="area  flex justify-between items-center mr-3  ">
                                <label class="text-font-color-dark text-sm font-medium  w-full  px-4 py-3 block cursor-pointer">
                                    <input type="radio" name="change_area" class=" opacity-0 change_area information_field" value="Outside Dhaka" data-shipping-charge="120"> <span class="relative left-[-20px]">Outside Dhaka</span>

                                </label>
                                <span>৳120</span>
                            </div>
                        </div>

                    </div>
                    <div class="mt-6">
                        <button type="{{env('APP_SMS_STATUS') ? 'button' : 'submit'}}" class="text-center border-2 text-white bg-[#048a00] rounded-md hind-siliguri-semibold px-6 py-3 text-base font-medium shadow-sm block w-full place_order_btn {{env('APP_SMS_STATUS') ? 'order_button' : ''}}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block">
                                <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                            </svg>

                            Place Order ৳ <span class="grand_total">0</span>
                        </button>
                        @if(env('APP_SMS_STATUS'))
                        <p>আপনি অর্ডারটি সাবমিট করলে আপনার মোবাইল নম্বরে ১ টি OTP যাবে এবং OTP টি সাবমিট করতে হবে।</p>
                        @endif
                    </div>

                    {{-- products start --}}
                    @include('landing.layouts.products_list')
                    {{-- products end --}}
                </div>

                <div>
                    <h3 class="text-xl font-medium mt-8">Your order</h3>
                    <div class="py-2 md-py-6">
                        <div class="">
                            <div class="flex justify-between text-base font-medium pb-3 mb-4" style="border-bottom: 1px solid #000;">
                                <p>Product</p>
                                <p>Sub Total</p>
                            </div>

                            <div class="flow-root">
                                <div id="selected-products" class=" text-black rounded-lg  min-h-[10px]">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 xl:pt-2 pb-0" style="border-top: 1px solid #000;">
                        <div class="flex justify-between text-base font-lite">
                            <p>Subtotal</p>
                            <p>৳<span class="product_price">0</span></p>
                        </div>

                        <div class="flex justify-between text-base font-lite pt-2">
                            <p>Shipping</p>
                            <p>৳<span class="shipping_price">0</span></p>
                        </div>

                        <p class="mt-5">Cash on Delivery</p>
                        <p class="bg-[#eaeaea] text-black px-2 py-2">পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন।</p>


                    </div>
                </div>
            </div>
        </form>
    </div>
</div>