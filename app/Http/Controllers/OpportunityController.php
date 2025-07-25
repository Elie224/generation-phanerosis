<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Opportunity;
use App\Models\TalentProject;
use App\Models\YoungTalent;

class OpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opportunities = Opportunity::latest()->get();
        $projects = \App\Models\TalentProject::latest()->get();
        $talents = \App\Models\YoungTalent::latest()->get();
        return view('opportunites-talents.index', compact('opportunities', 'projects', 'talents'));
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
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'external_link' => 'nullable|url',
            'attachment' => 'nullable|file|max:5120',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('opportunities', 'public');
        }
        Opportunity::create($data);
        return redirect()->route('opportunites.index')->with('success', 'Offre ajoutée !');
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
    public function update(Request $request, Opportunity $opportunity)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'company' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'external_link' => 'nullable|url',
            'attachment' => 'nullable|file|max:5120',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('opportunities', 'public');
        }
        $opportunity->update($data);
        return redirect()->route('opportunites.index')->with('success', 'Offre modifiée !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Affiche la page publique Opportunités & Talents.
     */
    public function publicIndex()
    {
        $opportunities = Opportunity::where('is_active', true)->latest()->get();
        $projects = TalentProject::latest()->get();
        $talents = YoungTalent::latest()->get();
        return view('opportunites-talents.public', compact('opportunities', 'projects', 'talents'));
    }

    public function publicShow($id)
    {
        $opportunity = \App\Models\Opportunity::findOrFail($id);
        return view('opportunites-talents.show_opportunity', compact('opportunity'));
    }

    public function publicSubmit()
    {
        return view('opportunites-talents.submit');
    }
}
