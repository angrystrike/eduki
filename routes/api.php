<?php

use App\Http\Controllers\Api\TestApiController;
use Illuminate\Support\Facades\Route;

Route::get('/hello', [TestApiController::class, 'hello']);
