<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WorldController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminFeedbackController;

/*
|--------------------------------------------------------------------------
| 1. TRANG CHỦ, SẢN PHẨM, GIỚI THIỆU
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('client.products.show');
Route::get('/hang-hoa', [ProductController::class, 'index'])->name('products.index');
Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('about.index');
Route::get('/quoc-te', [WorldController::class, 'index'])->name('world.index');

/*
|--------------------------------------------------------------------------
| 2. ĐĂNG NHẬP / ĐĂNG KÝ / ĐĂNG XUẤT
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post')
        ->middleware('throttle:10,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
    ->name('product.review')
    ->middleware(['auth', 'throttle:10,1']);

/*
|--------------------------------------------------------------------------
| 3. GIỎ HÀNG & VOUCHER
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');

    Route::post('/voucher', [CartController::class, 'applyVoucher'])->name('voucher.apply');
    Route::delete('/voucher', [CartController::class, 'removeVoucher'])->name('voucher.remove');
});

/*
|--------------------------------------------------------------------------
| 4. THANH TOÁN
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])
    ->name('checkout.process')
    ->middleware('throttle:10,1');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])
    ->whereNumber('orderId')
    ->name('checkout.success');

/*
|--------------------------------------------------------------------------
| 5. KHU VỰC ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('feedback.index');
    Route::delete('/feedback/{feedback}', [AdminFeedbackController::class, 'destroy'])->name('feedback.destroy');

    Route::resource('products', AdminProductController::class)->except('show');
});


/*
|--------------------------------------------------------------------------
| 6. LỊCH SỬ ĐƠN HÀNG CỦA KHÁCH
|--------------------------------------------------------------------------
*/
Route::get('/my-orders', [OrderController::class, 'index'])
    ->name('orders.index')
    ->middleware('auth');

Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])
    ->name('orders.cancel')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| 7. PHẢN HỒI / LIÊN HỆ
|--------------------------------------------------------------------------
*/
Route::get('/phan-hoi', [FeedbackController::class, 'index'])->name('feedback.index');
Route::post('/phan-hoi', [FeedbackController::class, 'store'])
    ->name('feedback.store')
    ->middleware('throttle:5,1');
