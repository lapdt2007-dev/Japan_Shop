<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AuthController; // Import AuthController tự tạo
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\WorldController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ChatbotController;

/*
|--------------------------------------------------------------------------
| 1. TRANG CHỦ & CHI TIẾT SẢN PHẨM
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
// Route xem chi tiết sản phẩm theo slug
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('client.products.show');

/*
|--------------------------------------------------------------------------
| 1B. TRANG HÀNG HÓA (danh sách toàn bộ sản phẩm - route + controller riêng)
|--------------------------------------------------------------------------
*/
Route::prefix('hang-hoa')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
});

/*
|--------------------------------------------------------------------------
| 1C. TRANG GIỚI THIỆU
|--------------------------------------------------------------------------
*/
Route::get('/gioi-thieu', [AboutController::class, 'index'])->name('about.index');
Route::get('/quoc-te', [WorldController::class, 'index'])->name('world.index');

/*
|--------------------------------------------------------------------------
| 2. ĐĂNG NHẬP / ĐĂNG KÝ / ĐĂNG XUẤT 
|--------------------------------------------------------------------------
*/
// Bọc các route auth vào middleware('guest')
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])
    ->name('product.review')
    ->middleware('auth');

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
| 4. THANH TOÁN (CHECKOUT)
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [CheckoutController::class, 'success'])->name('checkout.success');

/*
|--------------------------------------------------------------------------
| 5. KHU VỰC ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    Route::resource('products', AdminProductController::class);
});

/*
|--------------------------------------------------------------------------
| 6.Lịch sử đơn hàng của khách hàng
|--------------------------------------------------------------------------
*/
Route::get('/my-orders', [OrderController::class, 'index'])
    ->name('orders.index')
    ->middleware('auth');
/*
|--------------------------------------------------------------------------
| 7. Liên hệ
|--------------------------------------------------------------------------
*/
Route::get('/phan-hoi', [FeedbackController::class, 'index'])
    ->name('feedback.index');

Route::post('/phan-hoi', [FeedbackController::class, 'store'])
    ->name('feedback.store');
/*
|--------------------------------------------------------------------------
| 8.chatbot
|--------------------------------------------------------------------------
*/
Route::post('\chatbot/reply', [ChatbotController::class, 'reply'])->name('chatbot.reply');
