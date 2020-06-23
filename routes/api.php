<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['prefix' => 'admin' ], function () {
    Route::get('categories', 'Admin\CategoryController@getCategories');
    Route::get('suppliers', 'Admin\SupplierController@getSuppliers');
    Route::get('brands', 'Admin\BrandController@getBrands');
    Route::get('products', 'Admin\ProductController@getProducts');
    Route::get('discounts', 'Admin\DiscountController@getDiscounts');
    Route::get('additionalExpenseTypes', 'Admin\AdditionalExpenseController@getAdditionalExpenses');

    Route::post('product', 'Admin\ProductController@createProduct');
    Route::post('product/upload', 'Admin\ProductController@uploadProduct');
    Route::get('product/export', 'Admin\ProductController@exportProduct');
    Route::put('product', 'Admin\ProductController@updateProduct');
    Route::delete('product', 'Admin\ProductController@deleteProduct');
    Route::get('product', 'Admin\ProductController@getProductList');
    Route::get('product/pos', 'Admin\ProductController@getProductForSale');  
    Route::get('product/{sId}', 'Admin\ProductController@getProduct');

    Route::post('inventory', 'Admin\InventoryController@saveInventory');

    Route::put('business', 'Admin\BusinessController@createBusiness');

    Route::get('order', 'Admin\OrderController@getOrderList');
    Route::get('order/{sCode}', 'Admin\OrderController@getOrder');
    Route::put('order/{sCode}', 'Admin\OrderController@updateOrder');
    Route::post('order', 'Admin\OrderController@createOrder');
    Route::delete('order/{sCode}', 'Admin\OrderController@deleteOrder');

    Route::get('user', 'Admin\UserController@getUserList');
    Route::get('user/{sCode}', 'Admin\UserController@getUser');
    Route::put('user/{sCode}', 'Admin\UserController@updateUser');
    Route::post('user', 'Admin\UserController@createUser');
    Route::post('user/changepassword', 'Admin\UserController@changePassword');
    Route::delete('user/{sCode}', 'Admin\UserController@deleteUser');
    Route::post('user/{sCode}/reset', 'Admin\UserController@resetUser');
    
    Route::get('inventoryhistory', 'Admin\InventoryHistoryController@getInventoryHistoryList');
    Route::get('inventoryhistory/{sId}', 'Admin\InventoryHistoryController@getInventoryHistory');  
    Route::delete('inventoryhistory', 'Admin\InventoryHistoryController@deleteInventoryHistory');
    Route::put('inventoryhistory', 'Admin\InventoryHistoryController@updateInventoryHistory');

    Route::get('additionalExpenseType', 'Admin\AdditionalExpenseController@getAdditionalExpenseList');
    Route::get('additionalExpenseType/{sId}', 'Admin\AdditionalExpenseController@getAdditionalExpense');  
    Route::delete('additionalExpenseType', 'Admin\AdditionalExpenseController@deleteAdditionalExpense');
    Route::put('additionalExpenseType', 'Admin\AdditionalExpenseController@updateAdditionalExpense');
    Route::post('additionalExpenseType', 'Admin\AdditionalExpenseController@createAdditionalExpense');

    Route::get('additionalExpense', 'Admin\AdditionalExpenseHistoryController@getAdditionalExpenseHistoryList');
    Route::get('additionalExpense/{sId}', 'Admin\AdditionalExpenseHistoryController@getAdditionalExpenseHistory');  
    Route::delete('additionalExpense', 'Admin\AdditionalExpenseHistoryController@deleteAdditionalExpenseHistory');
    Route::put('additionalExpense', 'Admin\AdditionalExpenseHistoryController@updateAdditionalExpenseHistory');
    Route::post('additionalExpense', 'Admin\AdditionalExpenseHistoryController@createAdditionalExpenseHistory');

   
    Route::post('category', 'Admin\CategoryController@createCategory');
    Route::put('category', 'Admin\CategoryController@updateCategory');
    Route::delete('category', 'Admin\CategoryController@deleteCategory');
    Route::get('category', 'Admin\CategoryController@getCategoryList');  
    Route::get('category/{sId}', 'Admin\CategoryController@getCategory');  

    Route::post('discount', 'Admin\DiscountController@createDiscount');
    Route::put('discount', 'Admin\DiscountController@updateDiscount');
    Route::delete('discount', 'Admin\DiscountController@deleteDiscount');
    Route::get('discount', 'Admin\DiscountController@getDiscountList');  
    Route::get('discount/{sId}', 'Admin\DiscountController@getDiscount'); 

    Route::post('supplier', 'Admin\SupplierController@createSupplier');
    Route::put('supplier', 'Admin\SupplierController@updateSupplier');
    Route::delete('supplier', 'Admin\SupplierController@deleteSupplier');
    Route::get('supplier', 'Admin\SupplierController@getSupplierList');  
    Route::get('supplier/{sId}', 'Admin\SupplierController@getSupplier');  
    
    Route::post('brand', 'Admin\BrandController@createBrand');
    Route::put('brand', 'Admin\BrandController@updateBrand');
    Route::delete('brand', 'Admin\BrandController@deleteBrand');
    Route::get('brand', 'Admin\BrandController@getBrandList');  
    Route::get('brand/{sId}', 'Admin\BrandController@getBrand');  

    Route::post('disposal', 'Admin\DisposalController@disposeProduct');

    Route::post('return', 'Admin\ReturnController@createReturn');
    Route::get('return', 'Admin\ReturnController@getReturnList');  
    Route::put('return/{sProductId}/{sApproval}', 'Admin\ReturnController@approvalReturn');
    Route::delete('return', 'Admin\ReturnController@deleteReturn');


});
Route::group(['prefix' => 'front' ], function () {
    Route::post('login', 'Front\LoginController@loginUser');
    Route::get('redirect', 'Front\LoginController@redirect');
});