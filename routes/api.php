<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\MyItemsController;
use App\Http\Controllers\OrderController;
use App\Models\Item;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/items', [ItemsController::class, 'index']);//all items
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){

    // User
    Route::post('/logout', [UserController::class, 'logout']);
    
    // Items
    // Route::get('/items/{item}', function(Item $item){
    //     return $item;
    // });//get a single item
    Route::controller(ItemsController::class)->group(function(){

        Route::get('/items/{item}/orders',  'show'); //see interested buyers on items
        Route::post('/items', 'store');//create an item
        Route::delete('/items/{item}', 'destroy');//delete an item
    });
    
    // myItems
    Route::controller(MyItemsController::class)->group(function(){

        Route::get('myItems', 'myIndex');//all myItems
        Route::get('myItems/{item}/orders', 'showMyItem');//orders on myItems
        Route::delete('myItems/{item}', 'destroyMyItem');//destroy myItem
    });
    
    // Order
    Route::controller(OrderController::class)->group(function(){
        
        Route::post('/items/{item}/order',  'orderStore'); //order an item
        Route::get('/items/{item}/orders/{buyer}',  'selectInterestedBuyer');// select interested buyer
    });


});


