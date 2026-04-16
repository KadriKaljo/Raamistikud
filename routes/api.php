<?php

use App\Http\Controllers\Api\TravelDestinationApiController;
use Illuminate\Support\Facades\Route;

Route::get('travel-destinations', [TravelDestinationApiController::class, 'index'])
    ->name('api.travel-destinations.index');
