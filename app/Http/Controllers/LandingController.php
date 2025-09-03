<?php

namespace App\Http\Controllers;

use App\Models\MissingOrder;
use App\Models\MissingOrderItem;
use App\Repositories\FBConversionRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LandingController extends Controller
{
    public function index()
    {
        // Use different product IDs based on the environment
        $productIds = env('APP_ENV') === 'production'
            ? [16998]
            : [22631];

        $products_inventory = [];
        $defaultVariations = [];

        foreach ($productIds as $id) {
            $productResponse = $this->getCachedProductData($id);

            if (!$productResponse || !$productResponse['success']) {
                Cache::forget('products_inventory_' . env('SAAS_USER_ID') . '_' . $id);
                continue;
            }

            $productData = $productResponse['data'];
            $products_inventory[] = $productData;
        }

        // Optional: fail if no valid product data found
        if (empty($products_inventory)) {
            return response()->json(['error' => 'No valid product data found from API']);
        }

        return view('landing.index', compact('products_inventory'));
    }

    private function getCachedProductData($productId)
    {
        $cacheKey = 'products_inventory_' . env('SAAS_USER_ID') . '_' . $productId;

        return Cache::remember($cacheKey, now()->addDays(30), function () use ($productId) {
            $url = env('SAAS_API_BASE_PATH') . 'products/' . $productId . '?inventory_id=' . env('SAAS_USER_ID');
            $response = Http::get($url);

            return $response->successful() ? $response->json() : null;
        });
    }

    public function orderSaas(Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:25',
            'address' => 'required|max:255'
        ];

        $request->validate($v_data);

        $body_data = $request->all();
        $body_data['check_duplicate_order'] = 'Yes';

        $body_data['u_id'] = $request->uu_id;
        $body_data['_fbc'] = request()->cookie('_fbc');
        $body_data['_fbp'] = request()->cookie('_fbp');
        $body_data['user_ip'] = $request->header('CF-Connecting-IP') ?? $request->ip();
        $body_data['source_web'] = $request->host();
        $body_data['user_agent'] = $request->header('User-Agent');
        $products_data = json_decode($request->products_data);
        $body_data['order_items'] = $products_data;

        try {
            $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory?inventory_id=' . env('SAAS_USER_ID')), $body_data);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['success']) {
                    $order = $data['data'];

                    return redirect()->route('landing.orderComDetailsSaas', $order['id'])->with('success-alert', 'Order created success.');
                }

                return redirect()->back()->withInput()->with('error-alert', $data['message']);
            } else {
                return $this->storeMIssingOrder($request, 1);
            }
        } catch (\Exception $e) {
            return $this->storeMIssingOrder($request, 1);
        }
    }

    public function orderComDetailsSaas($id)
    {
        $response = Http::get(env('SAAS_API_BASE_PATH') . 'orders/al-amin-inventory/' . $id . '?inventory_id=' . env('SAAS_USER_ID'));
        if ($response->successful()) {
            $data = $response->json();
            if ($data['success']) {
                $order = $data['data'];
                if (($data['others']['pixel_track'] ?? 'false') == 'true') {
                    $track = true;
                } else {
                    $track = false;
                }
                // $track = true;

                return view('landing.orderComDetailsSaas', compact('order', 'track'));
            }

            abort(404);
        } else {
            return response()->json(['error' => 'Error from API!'], $response->status());
        }
    }



    public function failedTrackSaas(Request $request)
    {
        $request_data = $request->all();
        $request_data['_fbc'] = request()->cookie('_fbc');
        $request_data['_fbp'] = request()->cookie('_fbp');
        $request_data['user_ip'] = $request->header('CF-Connecting-IP') ?? $request->ip();
        $request_data['user_agent'] = $request->header('User-Agent');
        $request_data['source_web'] = $request->host();
        $response = Http::post((env('SAAS_API_BASE_PATH') . 'orders/failed-track'), $request_data);

        if ($response->successful()) {
            $data = $response->json();

            if ($data['success']) {
                return $data['message'];
            }

            return 'false';
        } else {
            return 'false';
        }
    }

    public function orderComDetailsMissing($id)
    {
        $order = MissingOrder::where('id', $id)->first();
        return view('landing.orderComDetailsMissing', compact('order'));
    }

    public function fbTrackLanding(Request $request)
    {
        if (env('PIXEL_ID') && env('PIXEL_ACCESS_TOKEN')) {
            $additinal_data = array();

            if ($request->currency) {
                $additinal_data['currency'] = $request->currency;
            }
            if ($request->content_type) {
                $additinal_data['content_type'] = $request->content_type;
            }
            if ($request->content_ids) {
                $additinal_data['content_ids'] = $request->content_ids;
            }
            if ($request->contents) {
                $additinal_data['contents'] = $request->contents;
            }
            if ($request->value) {
                $additinal_data['value'] = $request->value;
            }

            if (!count($additinal_data)) {
                $additinal_data = null;
            }
            $phone = $request->phone ?? null;
            $name = $request->name ?? null;
            $external_id = $request->external_id ?? null;
            $event_id = $request->event_id ?? null;

            return FBConversionRepo::track($request->track_type, $additinal_data, $phone, $name, $external_id, $event_id);
        }

        return 'false';
    }

    public function storeMIssingOrder($request, $quantity)
    {
        $order = MissingOrder::where('mobile_number', $request->mobile_number)->where('created_at', '>', now()->subHour(24))->first();
        if ($order) {
            return redirect()->back()->withInput()->with('error-alert', 'Your order already submitted!');
        }

        $missing_order = new MissingOrder;
        $missing_order->name = $request->name;
        $missing_order->mobile_number = $request->mobile_number;
        $missing_order->address = $request->address;
        $missing_order->shipping_charge = $request->delivery_charge ?? 0;
        $missing_order->ip = $request->header('CF-Connecting-IP') ?? $request->ip();
        $missing_order->user_agent = $request->header('User-Agent');
        $missing_order->uid = $request->uu_id;
        $missing_order->save();

        $products_data = json_decode($request->products_data);
        $products = collect($products_data)->map(function ($item) {
            return [
                'product_title' => $item->product_data_id,
                'price' => $item->selling_price,
                'quantity' => $item->quantity,
            ];
        })->toArray();
        foreach ($products as $product) {
            $missing_order_item = new MissingOrderItem;
            $missing_order_item->missing_order_id = $missing_order->id;
            $missing_order_item->product_title = $product['product_title'];
            $missing_order_item->price = $product['price'];
            $missing_order_item->quantity = $product['quantity'];
            $missing_order_item->save();
        }

        return redirect()->route('landing.orderComDetailsMissing', $missing_order->id)->with('success-alert', 'Order created success.');
    }
}
