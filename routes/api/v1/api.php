<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API v1 Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Restaurant API Routes
|--------------------------------------------------------------------------
*/
Route::get('/restaurants', [RestaurantController::class, 'apiIndex'])->name('api.v1.restaurants');

/*
|--------------------------------------------------------------------------
| Menu Item API Routes
|--------------------------------------------------------------------------
*/
Route::get('/menu-items', [MenuItemController::class, 'apiIndex'])->name('api.v1.menu-items');
Route::get('/menu-items/category/{category}', [MenuItemController::class, 'apiByCategory'])->name('api.v1.menu-items.category');
Route::get('/menu-items/restaurant/{restaurantId}', [MenuItemController::class, 'apiByRestaurant'])->name('api.v1.menu-items.restaurant');

/*
|--------------------------------------------------------------------------
| Categories API Routes
|--------------------------------------------------------------------------
*/
Route::get('/categories', [MenuItemController::class, 'apiCategories'])->name('api.v1.categories');

/*
|--------------------------------------------------------------------------
| Business Info API Routes
|--------------------------------------------------------------------------
*/
Route::get('/business-info', [DashboardController::class, 'apiBusinessInfo'])->name('api.v1.business-info');
