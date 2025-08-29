<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Sales page routes
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::get('/sales/{seller}', [SalesController::class, 'index'])->name('sales.seller');

// Follow/unfollow functionality
Route::post('/follow', [SalesController::class, 'follow'])->name('follow.store')->middleware('auth');

// Product purchase
Route::post('/products/{product}/purchase', [SalesController::class, 'purchase'])->name('products.purchase')->middleware('auth');

// Product rating
Route::post('/products/{product}/rate', [SalesController::class, 'rate'])->name('products.rate')->middleware('auth');

// Wishlist functionality
Route::post('/products/{product}/wishlist', [SalesController::class, 'wishlist'])->name('products.wishlist')->middleware('auth');

// Other routes that would be referenced in the layout
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/products/create', function () {
    return view('products.create');
})->name('products.create');

Route::get('/products/{product}/edit', function ($product) {
    return view('products.edit', compact('product'));
})->name('products.edit');

Route::delete('/products/{product}', function ($product) {
    // Delete product logic
    return redirect()->back();
})->name('products.destroy');

Route::get('/products/{product}', function ($product) {
    return view('products.show', compact('product'));
})->name('products.show');

Route::get('/messages', function () {
    return view('messages.index');
})->name('messages.index');

Route::get('/notifications', function () {
    return view('notifications.index');
})->name('notifications.index');

Route::get('/profile', function () {
    return view('profile.show');
})->name('profile.show');

Route::get('/profile/edit', function () {
    return view('profile.edit');
})->name('profile.edit');

Route::get('/analytics', function () {
    return view('analytics.index');
})->name('analytics.index');

Route::get('/affiliate', function () {
    return view('affiliate.index');
})->name('affiliate.index');

Route::get('/creators/explore', function () {
    return view('creators.explore');
})->name('creators.explore');

Route::get('/upgrade/pro', function () {
    return view('upgrade.pro');
})->name('upgrade.pro');

Route::get('/settings', function () {
    return view('settings.index');
})->name('settings.index');

Route::get('/download/app', function () {
    return view('download.app');
})->name('download.app');

Route::get('/customers/overview', function () {
    return view('customers.overview');
})->name('customers.overview');

Route::get('/customers/list', function () {
    return view('customers.list');
})->name('customers.list');

Route::get('/income/earning', function () {
    return view('income.earning');
})->name('income.earning');

Route::get('/income/refunds', function () {
    return view('income.refunds');
})->name('income.refunds');

Route::get('/income/payouts', function () {
    return view('income.payouts');
})->name('income.payouts');

Route::get('/income/statements', function () {
    return view('income.statements');
})->name('income.statements');

Route::get('/products/dashboard', function () {
    return view('products.dashboard');
})->name('products.dashboard');

Route::get('/products/drafts', function () {
    return view('products.drafts');
})->name('products.drafts');

Route::get('/products/released', function () {
    return view('products.released');
})->name('products.released');

Route::get('/products/comments', function () {
    return view('products.comments');
})->name('products.comments');

Route::get('/products/scheduled', function () {
    return view('products.scheduled');
})->name('products.scheduled');

Route::get('/shop', function () {
    return view('shop.index');
})->name('shop.index');

Route::get('/promote', function () {
    return view('promote.index');
})->name('promote.index');

// Authentication routes (these would typically be handled by Laravel Breeze or similar)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    // Logout logic
    return redirect('/');
})->name('logout');