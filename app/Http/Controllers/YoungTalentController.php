<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class YoungTalentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'cv' => 'nullable|file|max:5120',
            'external_link' => 'nullable|url',
        ]);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('young_talents/photos', 'public');
        }
        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('young_talents/cv', 'public');
        }
        \App\Models\YoungTalent::create($data);
        return redirect()->route('jeunes-talents.index')->with('success', 'Talent ajouté !');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\YoungTalent $talent)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'cv' => 'nullable|file|max:5120',
            'external_link' => 'nullable|url',
        ]);
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('young_talents/photos', 'public');
        }
        if ($request->hasFile('cv')) {
            $data['cv_path'] = $request->file('cv')->store('young_talents/cv', 'public');
        }
        $talent->update($data);
        return redirect()->route('jeunes-talents.index')->with('success', 'Talent modifié !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function publicShow($id)
    {
        $talent = \App\Models\YoungTalent::findOrFail($id);
        return view('opportunites-talents.show_talent', compact('talent'));
    }
}
