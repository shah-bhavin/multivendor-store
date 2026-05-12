<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Products;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::livewire('/', 'store.home-page');
Route::livewire('/products','store.product-list');
Route::livewire('/products/{slug}','store.product-details');
Route::livewire('/cart', 'store.cart-page');

Route::middleware('auth')->group(function () {
    Route::livewire('/checkout', 'store.checkout-page');
    Route::livewire('/my-orders', 'store.my-orders');
    Route::livewire('/wishlist', 'store.wishlist-page');
    Route::livewire('/my-chats', 'store.my-chats');
    Route::livewire('/orders/{orderId}/return-request', 'store.return-request-form')->name('return.request');

});
Route::livewire('/shop/{slug}', 'store.vendor-store-page');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get(
    '/payment-success/{order}',
    function (Order $order) {

        $order->update([

            'payment_status' =>
                'paid',

            'status' =>
                'processing',
        ]);
        
        if (session()->has('coupon_id')) {

            $coupon = Coupon::find(
                session('coupon_id')
            );

            if ($coupon) {

                $coupon->increment(
                    'used_count'
                );
            }
        }

        session()->forget('cart');

        return redirect('/')
            ->with(
                'success',
                'Payment Successful'
            );
    }
);



Route::livewire(
    '/payment/{order}',
    'store.payment-page'
)->name('payment.page');