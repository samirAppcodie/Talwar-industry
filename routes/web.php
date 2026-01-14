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

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'admin'], function () {
   
    Voyager::routes();

    // API routes for dependent dropdowns
    Route::post('api/dropdown', ['uses' => 'Saidy\VoyagerDependentDropdown\Http\Controllers\Api\V1\DependentDropdownController@dropdown', 'as' => 'voyager.api.dropdown']);
 // Custom login route to use our VoyagerAuthController
 Route::get('login', ['uses' => 'App\Http\Controllers\Voyager\VoyagerAuthCustomController@login', 'as' => 'voyager.login']);
 Route::post('login', ['uses' => 'App\Http\Controllers\Voyager\VoyagerAuthCustomController@postLogin']);

    // Custom routes for transactions with custom controller, matching Voyager pattern
    Route::get('transactions', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@index', 'as' => 'voyager.transactions.index']);
    Route::get('transactions/create', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@create', 'as' => 'voyager.transactions.create']);
    Route::post('transactions', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@store', 'as' => 'voyager.transactions.store']);
    Route::get('transactions/{id}', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@show', 'as' => 'voyager.transactions.show']);
    Route::get('transactions/{id}/edit', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@edit', 'as' => 'voyager.transactions.edit']);
    Route::put('transactions/{id}', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@update', 'as' => 'voyager.transactions.update']);
    Route::delete('transactions/{id}', ['uses' => 'App\Http\Controllers\Voyager\TransactionController@destroy', 'as' => 'voyager.transactions.destroy']);
    //customer price setting
    Route::get('customer-price-setting', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@index', 'as' => 'voyager.customer-price-setting.index']);
    Route::get('customer-price-setting/create', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@create', 'as' => 'voyager.customer-price-setting.create']);
    Route::post('customer-price-setting', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@store', 'as' => 'voyager.customer-price-setting.store']);
    Route::get('customer-price-setting/{id}', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@show', 'as' => 'voyager.customer-price-setting.show']);
    Route::get('customer-price-setting/{id}/edit', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@edit', 'as' => 'voyager.customer-price-setting.edit']);
    Route::put('customer-price-setting/{id}', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@update', 'as' => 'voyager.customer-price-setting.update']);
    Route::delete('customer-price-setting/{id}', ['uses' => 'App\Http\Controllers\Voyager\customer_priceController@destroy', 'as' => 'voyager.customer-price-setting.destroy']);
    Route::get('/users/{id}/info', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        return view('voyager::users.info', compact('user'));
    })->name('voyager.users.info');
    
    
});
