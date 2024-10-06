<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LairageController extends Controller
{
    /**
     * Display the purchase order / purchase receipt for received orders
     */

     public function index(Request $request)
     {
        return inertia('Lairage/Index',[]);
     }
}
