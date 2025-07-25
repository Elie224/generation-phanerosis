<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Resource;
use App\Models\Event;
use App\Models\ResourceCategory;
use App\Models\Teaching;
use App\Models\SupportInfo;
use App\Models\Announcement;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Accès réservé aux administrateurs.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $stats = [
            'users_count' => User::count(),
            'resources_count' => Resource::count(),
            'events_count' => Event::count(),
            'categories_count' => ResourceCategory::count(),
            'teachings_count' => Teaching::count(),
            'announcements_count' => Announcement::count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $recent_resources = Resource::with('category')->latest()->take(5)->get();
        $recent_events = Event::latest()->take(5)->get();
        $recent_teachings = Teaching::latest()->take(5)->get();
        $recent_announcements = Announcement::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_resources', 'recent_events', 'recent_teachings', 'recent_announcements'));
    }



    public function resources()
    {
        $resources = Resource::with('category', 'user')->latest()->paginate(15);
        return view('admin.resources.index', compact('resources'));
    }

    public function events()
    {
        $events = Event::latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function teachings()
    {
        $teachings = Teaching::latest()->paginate(15);
        return view('admin.teachings.index', compact('teachings'));
    }

    public function announcements()
    {
        $announcements = Announcement::with('user')->latest()->paginate(15);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function support()
    {
        $supportInfo = SupportInfo::first();
        return view('admin.support.index', compact('supportInfo'));
    }

    public function updateSupport(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'bank_info' => 'nullable|string',
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_address' => 'nullable|string|max:255',
            'thank_you_message' => 'nullable|string',
            'is_active' => 'boolean',
            
            // Coordonnées bancaires
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'bank_iban' => 'nullable|string|max:255',
            'bank_swift' => 'nullable|string|max:255',
            
            // Mobile Money
            'mtn_money_number' => 'nullable|string|max:255',
            'mtn_money_name' => 'nullable|string|max:255',
            'orange_money_number' => 'nullable|string|max:255',
            'orange_money_name' => 'nullable|string|max:255',
            
            // Cryptomonnaies
            'btc_address' => 'nullable|string',
            'eth_address' => 'nullable|string',
            'usdt_address' => 'nullable|string',
            'usdt_ton_address' => 'nullable|string',
            'usdt_bnb_address' => 'nullable|string',
            'pi_address' => 'nullable|string',
        ]);

        $supportInfo = SupportInfo::firstOrNew([]);
        $supportInfo->title = $request->input('title');
        $supportInfo->description = $request->input('description');
        $supportInfo->bank_info = $request->input('bank_info');
        $supportInfo->contact_phone = $request->input('contact_phone');
        $supportInfo->contact_email = $request->input('contact_email');
        $supportInfo->contact_address = $request->input('contact_address');
        $supportInfo->thank_you_message = $request->input('thank_you_message');
        
        // Gérer l'action (brouillon ou publication)
        $action = $request->input('action');
        if ($action === 'publish') {
            $supportInfo->is_active = true;
            $message = 'Informations de soutien publiées avec succès sur le site !';
        } else {
            // Action par défaut ou 'save_draft'
            $supportInfo->is_active = $request->boolean('is_active');
            $message = 'Informations de soutien enregistrées en brouillon.';
        }
        
        // Coordonnées bancaires
        $supportInfo->bank_name = $request->input('bank_name');
        $supportInfo->bank_account = $request->input('bank_account');
        $supportInfo->bank_iban = $request->input('bank_iban');
        $supportInfo->bank_swift = $request->input('bank_swift');
        
        // Mobile Money
        $supportInfo->mtn_money_number = $request->input('mtn_money_number');
        $supportInfo->mtn_money_name = $request->input('mtn_money_name');
        $supportInfo->orange_money_number = $request->input('orange_money_number');
        $supportInfo->orange_money_name = $request->input('orange_money_name');
        
        // Cryptomonnaies
        $supportInfo->btc_address = $request->input('btc_address');
        $supportInfo->eth_address = $request->input('eth_address');
        $supportInfo->usdt_address = $request->input('usdt_address');
        $supportInfo->usdt_ton_address = $request->input('usdt_ton_address');
        $supportInfo->usdt_bnb_address = $request->input('usdt_bnb_address');
        $supportInfo->pi_address = $request->input('pi_address');
        
        $supportInfo->save();

        return redirect()->back()->with('success', $message);
    }

    public function deleteSupport()
    {
        $supportInfo = SupportInfo::first();
        if ($supportInfo) {
            $supportInfo->delete();
            return redirect()->back()->with('success', 'Informations de soutien supprimées avec succès.');
        }
        
        return redirect()->back()->with('error', 'Aucune information de soutien à supprimer.');
    }
} 