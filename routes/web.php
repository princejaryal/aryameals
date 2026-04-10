<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\InvoiceController;

/*
|--------------------------------------------------------------------------
| Website Routes (Customer Facing)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // If user is authenticated, show home page as dashboard
    if (Auth::check()) {
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.menuItem.restaurant')->latest()->take(10)->get();
        return view('home', compact('user', 'orders'));
    }

    // If not authenticated, show login form
    return view('auth.login');
})->name('home');

Route::get('/restaurants', function () {
    return view('restaurants');
})->name('restaurants');

Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category.show');

Route::get('/restaurant/{id}', [RestaurantController::class, 'showBySlug'])->name('restaurant.show');

Route::get('/search', [RestaurantController::class, 'search'])->name('search');

Route::get('/api/v1/restaurants', [RestaurantController::class, 'apiIndex'])->name('api.restaurants');

Route::get('/item/{id}', [MenuItemController::class, 'detail'])->name('item.detail');

Route::post('/item/{id}/review', [MenuItemController::class, 'storeReview'])->name('item.review.store');

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/summary', [CartController::class, 'summary'])->name('cart.summary');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [CartController::class, 'processCheckout'])->name('checkout.process');
Route::get('/checkout/success/{orderId}', [CartController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/order/track/{orderId}', function ($orderId) {
    return view('order-tracking', ['orderId' => $orderId]);
})->name('order.track');
Route::get('/orders', [CartController::class, 'userOrders'])->name('orders.index');
Route::get('/invoice/{orderId}/download', [InvoiceController::class, 'download'])->name('invoice.download');

/*
|--------------------------------------------------------------------------
| Address Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('addresses')->name('addresses.')->group(function () {
    Route::get('/', [AddressController::class, 'index'])->name('index');
    Route::post('/', [AddressController::class, 'store'])->name('store');
    Route::put('/{id}', [AddressController::class, 'update'])->name('update');
    Route::delete('/{id}', [AddressController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/set-default', [AddressController::class, 'setDefault'])->name('set-default');
    Route::get('/default', [AddressController::class, 'getDefault'])->name('get-default');
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/api/user', function () {
    if (Auth::check()) {
        return response()->json([
            'id' => Auth::id(),
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? null
        ]);
    }
    return response()->json(['error' => 'Not authenticated'], 401);
})->middleware('auth');

Route::get('/api/cart/items', [CartController::class, 'getCartItemsForApi'])->name('api.cart.items');


Route::get('/menu/{restaurant}', function ($restaurant) {
    return view('menu', compact('restaurant'));
})->name('menu.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/google-oauth-login', [AuthController::class, 'googleOAuthLogin'])->name('google.oauth.login');
    Route::post('/google-login', [AuthController::class, 'googleLogin'])->name('google.login');
    Route::get('/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', function () {
        return redirect()->route('login');
    });
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/password', [AuthController::class, 'changePassword'])->name('password.change');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

/*
|--------------------------------------------------------------------------
| Admin Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::get('/admin/dashboard-stats', [DashboardController::class, 'dashboardStats'])->name('admin.dashboard.stats');

/*
|--------------------------------------------------------------------------
| Profile Management
|--------------------------------------------------------------------------
*/
Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.update.profile');
Route::post('/admin/profile/upload', [AdminController::class, 'uploadProfilePicture'])->name('admin.upload.profile.picture');
Route::post('/admin/password/update', [AdminController::class, 'updatePassword'])->name('admin.update.password');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Restaurant Management
|--------------------------------------------------------------------------
*/
Route::prefix('/admin/restaurants')->group(function () {
    Route::get('/', [RestaurantController::class, 'index'])->name('admin.restaurants');
    Route::get('/create', [RestaurantController::class, 'create'])->name('admin.restaurants.create');
    Route::post('/', [RestaurantController::class, 'store'])->name('admin.restaurants.store');
    Route::get('/{id}', [RestaurantController::class, 'show'])->name('admin.restaurants.show');
    Route::get('/{id}/edit', [RestaurantController::class, 'edit'])->name('admin.restaurants.edit');
    Route::put('/{id}', [RestaurantController::class, 'update'])->name('admin.restaurants.update');
    Route::delete('/{id}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');
    Route::post('/{id}/toggle-status', [RestaurantController::class, 'toggleStatus'])->name('admin.restaurants.toggle-status');
});

/*
|--------------------------------------------------------------------------
| Menu Management
|--------------------------------------------------------------------------
*/
Route::prefix('/admin/menu')->group(function () {
    Route::get('/', [MenuItemController::class, 'index'])->name('admin.menu');
    Route::get('/create', [MenuItemController::class, 'create'])->name('admin.menu.create');
    Route::get('/{id}/edit', [MenuItemController::class, 'edit'])->name('admin.menu.edit');
    Route::get('/restaurant/{restaurantId}', [MenuItemController::class, 'index'])->name('admin.menu.restaurant');

    Route::post('/items', [MenuItemController::class, 'store'])->name('admin.menu.store');
    Route::get('/items/{id}', [MenuItemController::class, 'show'])->name('admin.menu.show');
    Route::put('/items/{id}', [MenuItemController::class, 'update'])->name('admin.menu.update');
    Route::delete('/items/{id}', [MenuItemController::class, 'destroy'])->name('admin.menu.destroy');
    Route::post('/items/{id}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])->name('admin.menu.toggle-availability');

    Route::get('/items/search', [MenuItemController::class, 'search'])->name('admin.menu.search');
});

/*
|--------------------------------------------------------------------------
| Order Management
|--------------------------------------------------------------------------
*/
Route::prefix('/admin/orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('admin.orders');
    Route::get('/{id}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('admin.orders.edit');
    Route::put('/{id}', [OrderController::class, 'update'])->name('admin.orders.update');
    Route::delete('/{id}', [OrderController::class, 'destroy'])->name('admin.orders.destroy');

    Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::post('/{id}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('admin.orders.update-payment-status');
    Route::get('/dashboard-stats', [OrderController::class, 'dashboardStats'])->name('admin.orders.dashboard-stats');
});

/*
|--------------------------------------------------------------------------
| Customer Management
|--------------------------------------------------------------------------
*/
Route::prefix('/admin/customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('admin.customers');
    Route::get('/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
    Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('admin.customers.update');
    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');

    Route::get('/{id}/orders', [CustomerController::class, 'customerOrders'])->name('admin.customers.orders');
    Route::get('/dashboard-stats', [CustomerController::class, 'dashboardStats'])->name('admin.customers.dashboard-stats');
});

/*
|--------------------------------------------------------------------------
| Review Management
|--------------------------------------------------------------------------
*/
Route::prefix('admin/reviews')->name('admin.reviews.')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('index');
    Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
    Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
    Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reject');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
    Route::post('/bulk-approve', [ReviewController::class, 'bulkApprove'])->name('bulk-approve');
    Route::post('/bulk-reject', [ReviewController::class, 'bulkReject'])->name('bulk-reject');
    Route::post('/bulk-delete', [ReviewController::class, 'bulkDelete'])->name('bulk-delete');
});


/*
|--------------------------------------------------------------------------
| Platform Fee Management
|--------------------------------------------------------------------------
*/
Route::prefix('admin/platform-fees')->name('admin.platform-fees.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PlatformFeeController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\PlatformFeeController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\PlatformFeeController::class, 'store'])->name('store');
    Route::get('/{platformFee}/edit', [App\Http\Controllers\Admin\PlatformFeeController::class, 'edit'])->name('edit');
    Route::put('/{platformFee}', [App\Http\Controllers\Admin\PlatformFeeController::class, 'update'])->name('update');
    Route::delete('/{platformFee}', [App\Http\Controllers\Admin\PlatformFeeController::class, 'destroy'])->name('destroy');
    Route::get('/{platformFee}', [App\Http\Controllers\Admin\PlatformFeeController::class, 'show'])->name('show');
    Route::post('/{platformFee}/toggle', [App\Http\Controllers\Admin\PlatformFeeController::class, 'toggleStatus'])->name('toggle');
    Route::post('/bulk-action', [App\Http\Controllers\Admin\PlatformFeeController::class, 'bulkAction'])->name('bulk-action');
    Route::post('/reorder', [App\Http\Controllers\Admin\PlatformFeeController::class, 'reorder'])->name('reorder');
});

/*
|--------------------------------------------------------------------------
| Serve Storage Files
|--------------------------------------------------------------------------
*/
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    $mimeType = mime_content_type($fullPath);
    $headers = [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=31536000',
    ];
    return response()->file($fullPath, $headers);
})->where('path', '.*');
