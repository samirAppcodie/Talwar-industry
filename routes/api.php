<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\WheatPrice;
use App\Models\GrindingCharge;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/prices/{type}', function ($type) {

    if (!in_array($type, ['retail', 'wholesale'])) {
        return response()->json(['error' => 'Invalid type'], 400);
    }

    $wheatPrices = WheatPrice::where('price_type', $type)
        ->where('active', true)
        ->get(['id', 'price_per_kg']);  // get as collection of objects

    $grindingCharges = GrindingCharge::where('customer_type', $type)
        ->where('active', true)
        ->get(['id', 'charge_per_kg']); // get as collection of objects

    return response()->json([
        'wheat_price_per_kg' => $wheatPrices,
        'grinding_charge_per_kg' => $grindingCharges,
    ]);
});

