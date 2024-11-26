<?php

use App\Http\Controllers\admin\SubCategoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubProductcategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });

    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

        // Categories route
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/list', [CategoryController::class, 'list'])->name('categories.list');

        // edit categories route
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        // update categories route
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        // delete destroy categories route
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.delete');



        // Route::get('/', [AdminLoginController::class, 'index']);
        Route::post('/upload', [TempImagesController::class, 'create'])->name('dropzone.create');

        // sub-category route
        Route::get('/sub-categories', [SubCategoryController::class, 'index'])->name('sub-categories.index');

        Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
        Route::post('/sub-categories', [SubCategoryController::class, 'store'])->name('sub-categories.store');
        // edit sub category
        Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('sub-categories.edit');
        Route::put('/sub-categories/{subCategory}', [SubCategoryController::class, 'update'])->name('sub-categories.update');
        Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('sub-categories.delete');
        // brands routes
        Route::get('/brands/create', [BrandController::class, 'create'])->name('brands.create');
        Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
        Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
        Route::get('/brands/{Brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{Brand}', [BrandController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{Brand}', [BrandController::class, 'destroy'])->name('brands.delete');
        // Product routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');

        Route::get('/ProductSubcategories', [ProductSubCategoryController::class, 'index'])->name('ProductSubcategories.index');

        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{Product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{Product}', [ProductController::class, 'update'])->name('products.update');
        Route::post('/products-images/update', [ProductImageController::class, 'update'])->name('products-images.update');


















        // Slug route
        Route::get('/getSlug', function (Request $request) {
            $slug = "";
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }
            return response()->json(['status' => true, 'slug' => $slug]);
        })->name('getSlug');
    });
});


