<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class performaController extends Controller
{
    public function index()
    {
        return view('performa.performa');
    }
}
