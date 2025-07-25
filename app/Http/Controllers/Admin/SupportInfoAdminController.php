<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportInfo;
use Illuminate\Http\Request;

class SupportInfoAdminController extends Controller
{
    public function index()
    {
        $info = SupportInfo::first();
        return view('admin.support.index', compact('info'));
    }

    public function edit($id)
    {
        $info = SupportInfo::findOrFail($id);
        return view('admin.support.edit', compact('info'));
    }

    public function update(Request $request, $id)
    {
        $info = SupportInfo::findOrFail($id);
        $data = $request->validate([
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'bank_iban' => 'nullable|string|max:255',
            'orange_money_number' => 'nullable|string|max:255',
            'orange_money_name' => 'nullable|string|max:255',
            'mtn_money_number' => 'nullable|string|max:255',
            'mtn_money_name' => 'nullable|string|max:255',
            'usdt_address' => 'nullable|string|max:255',
            'btc_address' => 'nullable|string|max:255',
            'eth_address' => 'nullable|string|max:255',
        ]);
        $info->update($data);
        return redirect()->route('soutien.index')->with('success', 'Coordonnées mises à jour !');
    }
}
