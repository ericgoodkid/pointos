<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', 'PageController@login')->name('Login');
Route::get('/try', 'PageController@try');

Route::group( ['middleware' => 'AuthUser' ], function()
{
    Route::group( ['prefix' => 'SystemSetup' ], function()
    {
        Route::get('/User', 'PageController@user')->name('User');
        Route::get('/Supplier', 'PageController@supplier')->name('Supplier');
        Route::get('/Brand', 'PageController@brand')->name('Brand');
        Route::get('/Category', 'PageController@category')->name('Category');
        Route::get('/Product', 'PageController@product')->name('Product');
        Route::get('/Discount', 'PageController@discount')->name('Discount');
        Route::get('/BusinessInformation', 'PageController@business')->name('BusinessInformation');
        Route::get('/AdditionalExpenseType', 'PageController@additionalExpenseType')->name('AdditionalExpenseType');
    });

    Route::group( ['prefix' => 'Reports' ], function()
    {
        Route::get('/InventoryHistory', 'PageController@inventoryHistory')->name('InventoryHistory');
        Route::get('/ReturnHistory', 'PageController@returnHistory')->name('ReturnHistory');
        Route::get('/OrderHistory', 'PageController@orderHistory')->name('OrderHistory');
    });

    Route::group( ['prefix' => 'Transaction' ], function()
    {
        Route::get('/POS', 'PageController@pos')->name('PointOfSale');
        Route::get('/AdditionalExpense', 'PageController@additionalExpense')->name('AdditionalExpense');
    });

    Route::get('/Dashboard', 'PageController@dashboard')->name('Dashboard');
    Route::get('/Receipt/{sCode}', 'ReceiptController@getReceipt')->name('Receipt');
    Route::get('/Receipt2/{sCode}', 'ReceiptController@getReceipt2')->name('Receip2t');
    Route::get('/Receipt3/{sCode}', 'ReceiptController@getReceipt3')->name('Receip2t');
    Route::get('/logout', 'PageController@logout')->name('logout');


});


