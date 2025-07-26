<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\TalentProjectController;
use App\Http\Controllers\YoungTalentController;
use App\Http\Controllers\NotificationPreferenceController;

// Nouvelle page d'accueil publique
Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');

// Profil (modification)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/mon-profil', [\App\Http\Controllers\ProfileController::class, 'myProfile'])->name('profile.my');
    
    // Routes pour les préférences de notification
    Route::get('/profile/notifications', [NotificationPreferenceController::class, 'index'])->name('profile.notifications.index');
    Route::patch('/profile/notifications', [NotificationPreferenceController::class, 'update'])->name('profile.notifications.update');
    Route::post('/profile/notifications/test', [NotificationPreferenceController::class, 'test'])->name('profile.notifications.test');
    Route::post('/profile/notifications/toggle-type', [NotificationPreferenceController::class, 'toggleType'])->name('profile.notifications.toggle-type');
    
    // Routes pour les profils publics
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    
    // Routes pour les amis
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
Route::get('/friends/requests', [FriendController::class, 'myRequests'])->name('friends.requests');
Route::post('/friends/{user}', [FriendController::class, 'sendRequest'])->name('friends.send-request');
Route::patch('/friends/{friendship}', [FriendController::class, 'acceptRequest'])->name('friends.accept-request');
Route::delete('/friends/{friendship}', [FriendController::class, 'rejectRequest'])->name('friends.reject-request');
Route::delete('/friends/delete/{user}', [FriendController::class, 'deleteFriend'])->name('friends.delete');
    
    // Routes pour les messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/api/users', [UserController::class, 'apiIndex'])->name('api.users.index');
    Route::delete('/messages/{id}/for-me', [App\Http\Controllers\MessageController::class, 'deleteForMe'])->name('messages.deleteForMe');
    Route::delete('/messages/{id}', [App\Http\Controllers\MessageController::class, 'destroy'])->name('messages.destroy');
});

// Affichage public des enseignements
Route::get('/enseignements', [\App\Http\Controllers\PublicTeachingController::class, 'index'])->name('teachings.index');
Route::get('/enseignements/{id}', [\App\Http\Controllers\PublicTeachingController::class, 'show'])->name('teachings.show');
Route::get('/predications', [\App\Http\Controllers\PublicTeachingController::class, 'predications'])->name('teachings.predications');

// Affichage public des annonces
Route::get('/annonces', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/annonces/{id}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');

// Opportunités & Talents
Route::resource('opportunites', OpportunityController::class)->middleware('auth'); // admin ou membres
Route::resource('projets-talents', TalentProjectController::class)->middleware('auth');
Route::resource('jeunes-talents', YoungTalentController::class)->middleware('auth');

// Page publique Opportunités & Talents
Route::get('/opportunites-talents', [OpportunityController::class, 'publicIndex'])->name('opportunites-talents.public');

// Auth (login/register, etc.)
require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/priere', [\App\Http\Controllers\PrayerController::class, 'index'])->name('prayer.index');
    Route::post('/priere', [\App\Http\Controllers\PrayerController::class, 'send'])->name('prayer.send');
    Route::get('/mes-demandes-priere', [\App\Http\Controllers\PrayerController::class, 'myRequests'])->name('prayer.my-requests');
});

// Routes pour les pasteurs uniquement
Route::middleware(['auth', 'pastor'])->group(function () {
    Route::get('/mes-prieres', [\App\Http\Controllers\PrayerController::class, 'myPrayers'])->name('prayer.my-prayers');
    Route::post('/priere/{prayer}/respond', [\App\Http\Controllers\PrayerController::class, 'respond'])->name('prayer.respond');
    Route::patch('/priere/{prayer}/status', [\App\Http\Controllers\PrayerController::class, 'updateStatus'])->name('prayer.update-status');
});

Route::get('/soutien', [\App\Http\Controllers\SupportInfoController::class, 'index'])->name('support.index');

// Fiches détaillées publiques Opportunités & Talents
Route::get('/opportunites-talents/emploi/{opportunity}', [OpportunityController::class, 'publicShow'])->name('opportunites-talents.opportunity');
Route::get('/opportunites-talents/projet/{project}', [TalentProjectController::class, 'publicShow'])->name('opportunites-talents.project');
Route::get('/opportunites-talents/talent/{talent}', [YoungTalentController::class, 'publicShow'])->name('opportunites-talents.talent');

// Soumission publique d'une fiche Opportunités & Talents
Route::get('/opportunites-talents/soumettre', [OpportunityController::class, 'publicSubmit'])->name('opportunites-talents.submit');

// Routes pour les événements
Route::get('/evenements', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/evenements/calendrier', [\App\Http\Controllers\EventController::class, 'calendar'])->name('events.calendar');

Route::middleware('auth')->group(function () {
    Route::get('/evenements/create', [\App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/evenements', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/evenements/{event}/edit', [\App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
    Route::put('/evenements/{event}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::patch('/evenements/{event}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/evenements/{event}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/evenements/{event}/inscription', [\App\Http\Controllers\EventController::class, 'register'])->name('events.register');
    Route::delete('/evenements/{event}/desinscription', [\App\Http\Controllers\EventController::class, 'unregister'])->name('events.unregister');
});

Route::get('/evenements/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');

// Routes pour la bibliothèque de ressources
Route::get('/ressources', [\App\Http\Controllers\ResourceController::class, 'index'])->name('resources.index');
Route::get('/ressources/recherche', [\App\Http\Controllers\ResourceController::class, 'search'])->name('resources.search');
Route::get('/ressources/categorie/{category:slug}', [\App\Http\Controllers\ResourceController::class, 'category'])->name('resources.category');

Route::middleware('auth')->group(function () {
    Route::get('/ressources/create', [\App\Http\Controllers\ResourceController::class, 'create'])->name('resources.create');
    Route::post('/ressources', [\App\Http\Controllers\ResourceController::class, 'store'])->name('resources.store');
    Route::get('/ressources/{resource}/edit', [\App\Http\Controllers\ResourceController::class, 'edit'])->name('resources.edit');
    Route::put('/ressources/{resource}', [\App\Http\Controllers\ResourceController::class, 'update'])->name('resources.update');
    Route::patch('/ressources/{resource}', [\App\Http\Controllers\ResourceController::class, 'update'])->name('resources.update');
    Route::delete('/ressources/{resource}', [\App\Http\Controllers\ResourceController::class, 'destroy'])->name('resources.destroy');
});

Route::get('/ressources/{resource:slug}', [\App\Http\Controllers\ResourceController::class, 'show'])->name('resources.show');
Route::get('/ressources/{resource}/telecharger', [\App\Http\Controllers\ResourceController::class, 'download'])->name('resources.download');

// Routes d'administration
Route::middleware(['auth', \App\Http\Middleware\MainAdminSecurityMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [\App\Http\Controllers\AdminUserController::class, 'index'])->name('users');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [\App\Http\Controllers\AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::get('/resources', [\App\Http\Controllers\AdminController::class, 'resources'])->name('resources');
    Route::get('/events', [\App\Http\Controllers\AdminController::class, 'events'])->name('events');
    Route::get('/teachings', [\App\Http\Controllers\AdminController::class, 'teachings'])->name('teachings');
    Route::get('/announcements', [\App\Http\Controllers\AdminController::class, 'announcements'])->name('announcements');
    Route::get('/support', [\App\Http\Controllers\AdminController::class, 'support'])->name('support');
    Route::put('/support', [\App\Http\Controllers\AdminController::class, 'updateSupport'])->name('support.update');
    Route::delete('/support', [\App\Http\Controllers\AdminController::class, 'deleteSupport'])->name('support.delete');
    
    // Routes CRUD pour les enseignements
    Route::resource('teachings', \App\Http\Controllers\TeachingController::class)->except(['index', 'show']);
    
    // Routes CRUD pour les annonces
    Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->except(['index', 'show']);
});
