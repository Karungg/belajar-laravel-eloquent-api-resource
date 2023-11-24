<?php

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories/{id}', function ($id) {
    $category = Category::findOrFail($id);
    return new CategoryResource($category);
});

Route::get('/categories', function () {
    $categories = Category::all();
    return CategoryResource::collection($categories);
});

Route::get('/categories-custom', function () {
    $categories = Category::all();
    return new \App\Http\Resources\CategoryCollection($categories);
});

Route::get('/products/{id}', function ($id) {
    $products = \App\Models\Product::find($id);
    return new \App\Http\Resources\ProductResource($products);
});

Route::get('/products', function () {
    $products = \App\Models\Product::all();
    return new \App\Http\Resources\ProductCollection($products);
});

Route::get('/products-paging', function (Request $request) {
    $page = $request->get('page', 1);
    $products = \App\Models\Product::paginate(perPage: 2, page: $page);
    return new App\Http\Resources\ProductCollection($products);
});
