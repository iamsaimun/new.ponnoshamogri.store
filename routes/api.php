<?php

use App\Http\Controllers\API\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('missing-orders', [APIController::class, 'missingOrder']);
