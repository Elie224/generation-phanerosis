<?php

namespace App\Http\Controllers;

use App\Models\SupportInfo;

class SupportInfoController extends Controller
{
    public function index()
    {
        $info = SupportInfo::first();
        return view('support.index', compact('info'));
    }
}
