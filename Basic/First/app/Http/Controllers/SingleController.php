<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SingleController extends Controller
{
    public function __invoke()
    {
        return "Invoke Controller";
    }
}
