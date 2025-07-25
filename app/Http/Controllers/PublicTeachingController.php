<?php

namespace App\Http\Controllers;

use App\Models\Teaching;
use Illuminate\Http\Request;

class PublicTeachingController extends Controller
{
    public function index()
    {
        $teachings = Teaching::latest()->paginate(10);
        return view('teachings.index', compact('teachings'));
    }

    public function show($id)
    {
        $teaching = Teaching::findOrFail($id);
        return view('teachings.show', compact('teaching'));
    }

    public function predications()
    {
        $teachings = Teaching::where('type', 'predication')->latest()->paginate(10);
        return view('teachings.predications', compact('teachings'));
    }
}
