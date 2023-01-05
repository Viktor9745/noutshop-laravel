<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth2\RegisterController;
use App\Http\Controllers\Auth2\LoginController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Adm\UserController;
use App\Http\Controllers\Adm\CategoryController;
use App\Http\Controllers\Adm\ManufacturerController;
use App\Http\Controllers\Adm\RoleController;
use App\Http\Controllers\LangController;
use App\Http\Controllers\StripePaymentController;

Route::get('/', function(){
    return redirect()->route('items.index');
});

Route::get('lang/{lang}', [LangController::class, 'switchLang'])->name('switch.lang');

//Route::resource('items', ItemController::class)->only('index','show');
Route::get('items/category/{category}', [ItemController::class, 'itemsByCategory'])->name('items.category');
Route::get('items/manufacturer/{manufacturer}', [ItemController::class, 'itemsByManufacturer'])->name('items.manufacturer');
Route::get('/items', [ItemController::class,'index'])->name('items.index');

Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

// Route::get('adm/roles/create', [RoleController::class,'create'])->name('adm.roles.create');
// Route::post('adm/roles', [RoleController::class,'store'])->name('adm.roles.store');
// Route::get('adm/roles', [RoleController::class, 'index'])->name('adm.roles.index');
// Route::get('adm/roles/search', [RoleController::class, 'index'])->name('adm.roles.search');
// Route::put('adm/roles/{role}', [RoleController::class, 'update'])->name('adm.roles.update');
// Route::get('adm/roles/{role}/edit', [RoleController::class, 'edit'])->name('adm.roles.edit');
// Route::delete('adm/roles/{role}',[RoleController::class, 'destroy'])->name('adm.roles.destroy');

Route::middleware('auth')->group(function(){
    Route::get('stripe', [StripePaymentController::class, 'paymentStripe'])->name('addmoney.paymentstripe');
    Route::post('add-money-stripe', [StripePaymentController::class, 'postPaymentStripe'])->name('addmoney.stripe');
    Route::post('/items/{item}/addcart', [ItemController::class, 'addcart'])->name('items.addcart');
    Route::post('/items/{item}/uncart', [ItemController::class, 'uncart'])->name('items.uncart');
    Route::get('/items/cart', [ItemController::class,'cart'])->name('items.cart');
    Route::post('/items/cart', [ItemController::class, 'buy'])->name('items.buy');

    Route::get('/items/create', [ItemController::class,'create'])->name('items.create');
    Route::post('/items', [ItemController::class,'store'])->name('items.store');
    Route::match(['put', 'patch'],'/items/{item}',[ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}',[ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('/items/{item}/edit',[ItemController::class, 'edit'])->name('items.edit');

    Route::prefix('adm')->as('adm.')->middleware('hasrole:admin')->group(function(){
        Route::put('/cart/{cart}/confirm', [UserController::class, 'confirm'])->name('cart.confirm');
        Route::get('/cart', [UserController::class, 'cart'])->name('cart.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/search', [UserController::class, 'index'])->name('users.search');
    //    Route::resource('items', ItemController::class)->except('index','show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}',[UserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/ban', [UserController::class,'ban'])->name('users.ban');
        Route::put('/users/{user}/unban', [UserController::class,'unban'])->name('users.unban');
        Route::delete('/users/{user}',[UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/roles/create', [RoleController::class,'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class,'store'])->name('roles.store');
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/search', [RoleController::class, 'index'])->name('roles.search');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::delete('/roles/{role}',[RoleController::class, 'destroy'])->name('roles.destroy');
    });
    Route::prefix('adm')->as('adm.')->middleware('hasrole:moderator')->group(function(){
        Route::get('/categories/create', [CategoryController::class,'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class,'store'])->name('categories.store');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/search', [CategoryController::class, 'index'])->name('categories.search');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/manufacturers/create', [ManufacturerController::class,'create'])->name('manufacturers.create');
        Route::post('/manufacturers', [ManufacturerController::class,'store'])->name('manufacturers.store');
        Route::get('/manufacturers', [ManufacturerController::class, 'index'])->name('manufacturers.index');
        Route::get('/manufacturers/search', [ManufacturerController::class, 'index'])->name('manufacturers.search');
        Route::get('/manufacturers/{manufacturer}/edit', [ManufacturerController::class, 'edit'])->name('manufacturers.edit');
        Route::put('/manufacturers/{manufacturer}', [ManufacturerController::class, 'update'])->name('manufacturers.update');
        Route::delete('/manufacturers/{manufacturer}',[ManufactureryController::class, 'destroy'])->name('manufacturers.destroy');
    });
    Route::resource('/reviews', ReviewController::class)->only('store', 'destroy', 'update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});

Route::get('/register', [RegisterController::class,  'create'])->name('register.form');
Route::post('/register',[RegisterController::class, 'register'])->name('register');


Route::get('/login', [LoginController::class,  'create'])->name('login.form');
Route::post('/login',[LoginController::class, 'login'])->name('login');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



