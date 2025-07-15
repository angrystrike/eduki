<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestApiController extends Controller
{
    public function hello()
    {
        return response()->json(['message' => 'Hello World from API!']);
    }
}
