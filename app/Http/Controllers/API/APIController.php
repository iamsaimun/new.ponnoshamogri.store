<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\MissingOrder;
use App\Repositories\JsonResponse;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function missingOrder(Request $request)
    {
        $api_1 = $request->api_1;
        $api_2 = $request->api_2;
        if (env('SAAS_API_KEY_1') != $api_1 || env('SAAS_API_KEY_2') != $api_2) {
            return JsonResponse::onlyMessage('Unauthorized', false, 401);
        }

        $skip = $request->skip ?? 0;
        $take = $request->take ?? 30;
        $orders = MissingOrder::with('order_items');
        if ($request->after) {
            $orders->where('created_at', '>=', $request->after);
        }
        if ($request->before) {
            $orders->where('created_at', '<=', $request->before);
        }
        $orders = $orders->skip($skip)->take($take)->get();
        // dd($orders);

        $orders_data = [];
        foreach ($orders as $key => $order) {
            $orders_data[$key]['mobile_number'] = $order->mobile_number;
            $orders_data[$key]['name'] = $order->name;
            $orders_data[$key]['address'] = $order->address;
            $orders_data[$key]['shipping_charge'] = $order->shipping_charge;
            $orders_data[$key]['paid_amount'] = $order->amount;
            $orders_data[$key]['note'] = $order->note;
            $orders_data[$key]['created_at'] = $order->created_at;
            $orders_data[$key]['order_items'] = [];

            $paid_amount = 0;

            foreach ($order->order_items as $op_key => $order_item) {
                $paid_amount += $order_item->price * $order_item->quantity;
                $orders_data[$key]['order_items'][$op_key]['product_title'] = $order_item->product_title;
                $orders_data[$key]['order_items'][$op_key]['quantity'] = $order_item->quantity;
                $orders_data[$key]['order_items'][$op_key]['price'] = $order_item->price;
            }
            $orders_data[$key]['paid_amount'] = $paid_amount;
        }


        return JsonResponse::withData($orders_data);

        return JsonResponse::withData($orders);
    }
}
