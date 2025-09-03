<?php

namespace App\Http\Controllers;

use App\Models\Order\OrderProduct;
use App\Models\Product\Product;
use App\Models\Product\ProductData;
use App\Models\PurchaseItem;
use App\Models\User;
use App\Repositories\PathaoRepo;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function test(Request $request)
    {
        dd($request->ip());
    }

    public function cacheClear()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize:clear');

        return redirect()->route('homepage');
    }
}
