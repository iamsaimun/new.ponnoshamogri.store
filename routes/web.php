<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

// $allowedDomains = [
//     'facebook.com',
//     'm.facebook.com',
//     'web.facebook.com',
//     'l.facebook.com',
//     'lm.facebook.com',
//     'business.facebook.com',
// ];

// $referer = Request::header('referer');

// // Allow direct visits (empty referer)
// if (!empty($referer)) {
//     // Check if referer contains allowed Facebook domains
//     $allowed = false;
//     foreach ($allowedDomains as $domain) {
//         if (stripos($referer, $domain) !== false) {
//             $allowed = true;
//             break;
//         }
//     }

//     if (!$allowed) {
//         abort(403, 'Access denied!');
//     }
// }


// Other Pages
Route::get('/', [LandingController::class, 'index'])->name('homepage');

Route::post('l/order', [LandingController::class, 'orderSaas'])->name('landing.orderSaas');
Route::get('l/thank-you/{id}', [LandingController::class, 'orderComDetailsSaas'])->name('landing.orderComDetailsSaas');
Route::post('l/order-failed-track-saas', [LandingController::class, 'failedTrackSaas'])->name('landing.orderFailedTrackSaas');
Route::post('l/fb-track', [LandingController::class, 'fbTrackLanding'])->name('fbTrackLanding');
Route::get('l/missing-order/{id}', [LandingController::class, 'orderComDetailsMissing'])->name('landing.orderComDetailsMissing');

Auth::routes();

// Test Routes
Route::get('cache-clear',       [TestController::class, 'cacheClear']);
// Route::get('config',            [TestController::class, 'config'])->name('config');
Route::get('test',            [TestController::class, 'test'])->name('config');
