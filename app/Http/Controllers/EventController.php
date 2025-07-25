<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::public()
            ->upcoming()
            ->orderBy('start_date')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent ajouter des événements.');
        }
        
        return view('events.create');
    }

    public function store(Request $request)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent ajouter des événements.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:general,culte,formation,reunion,priere',
            'color' => 'nullable|string|max:7',
            'is_public' => 'boolean',
            'max_participants' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organizer_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'additional_info' => 'nullable|string',
            'requires_registration' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $validated['color'] = $validated['color'] ?? '#3B82F6';

        Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Événement créé avec succès !');
    }

    public function show(Event $event)
    {
        $isParticipant = false;
        $participantStatus = null;

        if (Auth::check()) {
            $participant = $event->participants()->where('user_id', Auth::id())->first();
            $isParticipant = $participant ? true : false;
            $participantStatus = $participant ? $participant->pivot->status : null;
        }

        return view('events.show', compact('event', 'isParticipant', 'participantStatus'));
    }

    public function edit(Event $event)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent modifier des événements.');
        }
        
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent modifier des événements.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:general,culte,formation,reunion,priere',
            'color' => 'nullable|string|max:7',
            'is_public' => 'boolean',
            'max_participants' => 'nullable|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organizer_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'additional_info' => 'nullable|string',
            'requires_registration' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Événement mis à jour avec succès !');
    }

    public function destroy(Event $event)
    {
        // Vérifier que l'utilisateur est administrateur
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès refusé. Seuls les administrateurs peuvent supprimer des événements.');
        }

        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Événement supprimé avec succès !');
    }

    public function register(Event $event)
    {
        if (!$event->requires_registration) {
            return back()->with('error', 'Cet événement ne nécessite pas d\'inscription.');
        }

        if ($event->isFull) {
            return back()->with('error', 'Cet événement est complet.');
        }

        $event->participants()->attach(Auth::id(), [
            'status' => 'registered',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Inscription réussie !');
    }

    public function unregister(Event $event)
    {
        $event->participants()->detach(Auth::id());

        return back()->with('success', 'Désinscription réussie !');
    }

    public function calendar()
    {
        $events = Event::public()
            ->orderBy('start_date')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date->format('Y-m-d H:i:s'),
                    'end' => $event->end_date->format('Y-m-d H:i:s'),
                    'color' => $event->color,
                    'url' => route('events.show', $event->id),
                    'extendedProps' => [
                        'type' => $event->type,
                        'location' => $event->location,
                    ]
                ];
            });

        return view('events.calendar', compact('events'));
    }
}
