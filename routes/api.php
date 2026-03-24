<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryBoyController;
use App\Http\Controllers\EventController;

Route::post('login', [AuthController::class, 'login']);
Route::post('adminLogin', [AuthController::class, 'adminLogin']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('getProduct/{categoryId}', [CategoryController::class, 'getProductByCategory']);
    Route::post('/user/profile/{userId}', [ProfileController::class, 'addUserDetails']);
    Route::get('/user/getdata/{userId}', [ProfileController::class, 'getUserDetails']);
    Route::post('/user/change_active_address/{userId}', [ProfileController::class, 'changeActiveAddress']);
    Route::get('/user/active_address/{userId}', [ProfileController::class, 'getActiveAddress']);
    Route::post('/user/favourites',[ProductController::class, 'addFavourite']);
    Route::post('/user/favourites/{user_id}',[ProductController::class, 'getFavourite']);
});

Route::controller(ProductController::class)->middleware('auth:api')->group(function(){
    Route::get('/products/similar/{product_id}','getSimilarProduct');
    Route::get('getSingleProduct/{product_id}','getSingleProduct');
    Route::get('getProductTitle/{product_id}','getProductTitle');
    Route::get('products/getall','getAllProducts');
    Route::get('products/get/{product_id}','getProduct');
    Route::post('products/add','addProduct');
    Route::post('products/delete/{product_id}','deleteProduct');
    Route::post('products/update/{product_id}','updateProduct');
});

Route::controller(CategoryController::class)->middleware('auth:api')->group(function(){
    Route::get('categories/home', 'getCategoryForHome');
    Route::get('categories/allitem', 'getCategoryForAllItem');
    Route::get('categories/getall', 'getAllCategory');
    Route::post('categories/add', 'addCategory');
    Route::post('categories/delete/{category_id}', 'deleteCategory');
    Route::get('categories/get/{category_id}', 'getCategory');
    Route::post('categories/update/{category_id}', 'updateCategory');
});

Route::controller(OrderController::class)->middleware('auth:api')->group(function(){
    Route::post('place_order','storeOrder');
    Route::get('getOrders','getOrder');
    Route::get('orders/getall','getAllOrder');
    Route::post('orders/assign-delivery-boy/{orderId}','assignDeliveryBoy');
    Route::post('order/cancel/{orderId}','orderCancel');
    Route::post('order/get_order/{orderId}','getReorderData');
    Route::post('order/place_reorder/{orderId}','placeReorder');
});

Route::controller(DeliveryBoyController::class)->middleware('auth:api')->group(function(){
    Route::get('deliveryboy/getall','getAllDeliveryBoys');
});

Route::controller(ProfileController::class)->middleware('auth:api')->group(function(){
    Route::get('users/getall','getAllUsers');
});

Route::controller(EventController::class)->group(function(){
    Route::get('events/getall','getAllEvent');
    Route::post('event/addForm', 'addFormData');
});



Route::controller(EventController::class)->middleware('auth:api')->group(function(){
    Route::post('events/add','addEvent');
    Route::post('events/update/{eventid}','updateEvent');
    Route::get('student/getallStudent','getallStudent');
});