<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TalentProjectController extends Controller
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'owner_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'external_link' => 'nullable|url',
            'attachment' => 'nullable|file|max:5120',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('talent_projects', 'public');
        }
        \App\Models\TalentProject::create($data);
        return redirect()->route('projets-talents.index')->with('success', 'Projet ajouté !');
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
    public function update(Request $request, \App\Models\TalentProject $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'owner_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'external_link' => 'nullable|url',
            'attachment' => 'nullable|file|max:5120',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('talent_projects', 'public');
        }
        $project->update($data);
        return redirect()->route('projets-talents.index')->with('success', 'Projet modifié !');
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
        $project = \App\Models\TalentProject::findOrFail($id);
        return view('opportunites-talents.show_project', compact('project'));
    }
}
