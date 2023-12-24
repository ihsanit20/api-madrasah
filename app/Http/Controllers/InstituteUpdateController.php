<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstituteUpdateController extends Controller
{
    public function name(Request $request)
    {
        $data = $request->all();

        return response($data, 200);
    }
}
