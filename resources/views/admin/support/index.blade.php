@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Informations de Soutien</h1>
            <p class="mt-1 text-sm text-gray-600">Configurez les informations affichées sur la page "Don de Soutien".</p>
        </div>
    </div>

    <!-- Affichage des informations existantes -->
    @if($supportInfo && $supportInfo->exists)
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Informations actuelles</h3>
                <div class="flex space-x-2">
                    <button type="button" onclick="scrollToForm()" class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Modifier
                    </button>
                    <button type="button" onclick="toggleStatus()" class="inline-flex items-center px-3 py-2 border border-yellow-300 shadow-sm text-sm leading-4 font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        @if($supportInfo->is_active)
                            Désactiver
                        @else
                            Activer
                        @endif
                    </button>
                    <form action="{{ route('admin.support.delete') }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer toutes les informations de soutien ? Cette action est irréversible.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer tout
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900">Statut</h4>
                        <p class="text-sm text-gray-600">
                            @if($supportInfo->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Actif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactif</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Titre</h4>
                        <p class="text-sm text-gray-600">{{ $supportInfo->title ?: 'Non défini' }}</p>
                    </div>
                    @if($supportInfo->bank_name || $supportInfo->bank_account)
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">Coordonnées bancaires</h4>
                                <p class="text-sm text-gray-600">
                                    @if($supportInfo->bank_name) {{ $supportInfo->bank_name }}<br> @endif
                                    @if($supportInfo->bank_account) Compte: {{ $supportInfo->bank_account }} @endif
                                </p>
                            </div>
                            <button type="button" onclick="clearSpecificField('bank')" class="text-red-600 hover:text-red-800 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                    @if($supportInfo->mtn_money_number || $supportInfo->orange_money_number)
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">Mobile Money</h4>
                                <p class="text-sm text-gray-600">
                                    @if($supportInfo->mtn_money_number) MTN: {{ $supportInfo->mtn_money_number }}<br> @endif
                                    @if($supportInfo->orange_money_number) Orange: {{ $supportInfo->orange_money_number }} @endif
                                </p>
                            </div>
                            <button type="button" onclick="clearSpecificField('mobile')" class="text-red-600 hover:text-red-800 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                    @if($supportInfo->btc_address || $supportInfo->eth_address || $supportInfo->usdt_address)
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">Cryptomonnaies</h4>
                                <p class="text-sm text-gray-600">
                                    @if($supportInfo->btc_address) BTC: {{ substr($supportInfo->btc_address, 0, 20) }}...<br> @endif
                                    @if($supportInfo->eth_address) ETH: {{ substr($supportInfo->eth_address, 0, 20) }}...<br> @endif
                                    @if($supportInfo->usdt_address) USDT: {{ substr($supportInfo->usdt_address, 0, 20) }}... @endif
                                </p>
                            </div>
                            <button type="button" onclick="clearSpecificField('crypto')" class="text-red-600 hover:text-red-800 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Message si aucune information n'existe -->
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune information de soutien</h3>
            <p class="mt-1 text-sm text-gray-500">Commencez par ajouter vos coordonnées de paiement ci-dessous.</p>
        </div>
    </div>
    @endif

    <!-- Formulaire de configuration -->
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">
                    @if($supportInfo && $supportInfo->exists)
                        Modifier les informations de soutien
                    @else
                        Ajouter des informations de soutien
                    @endif
                </h3>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.support.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Informations générales -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations générales</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre de la page</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $supportInfo->title ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="1" {{ old('is_active', $supportInfo->is_active ?? 0) ? 'selected' : '' }}>Actif</option>
                                <option value="0" {{ !old('is_active', $supportInfo->is_active ?? 0) ? 'selected' : '' }}>Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description de la page</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description', $supportInfo->description ?? '') }}</textarea>
                    </div>

                    <div class="mt-6">
                        <label for="thank_you_message" class="block text-sm font-medium text-gray-700">Message de remerciement (après un don)</label>
                        <textarea name="thank_you_message" id="thank_you_message" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('thank_you_message', $supportInfo->thank_you_message ?? '') }}</textarea>
                    </div>
                </div>

                <!-- Coordonnées bancaires -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Coordonnées bancaires</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="bank_name" class="block text-sm font-medium text-gray-700">Nom de la banque</label>
                            <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $supportInfo->bank_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="bank_account" class="block text-sm font-medium text-gray-700">Numéro de compte</label>
                            <input type="text" name="bank_account" id="bank_account" value="{{ old('bank_account', $supportInfo->bank_account ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="bank_iban" class="block text-sm font-medium text-gray-700">IBAN</label>
                            <input type="text" name="bank_iban" id="bank_iban" value="{{ old('bank_iban', $supportInfo->bank_iban ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="bank_swift" class="block text-sm font-medium text-gray-700">Code SWIFT/BIC</label>
                            <input type="text" name="bank_swift" id="bank_swift" value="{{ old('bank_swift', $supportInfo->bank_swift ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Mobile Money -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mobile Money</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="mtn_money_number" class="block text-sm font-medium text-gray-700">Numéro MTN Money</label>
                            <input type="text" name="mtn_money_number" id="mtn_money_number" value="{{ old('mtn_money_number', $supportInfo->mtn_money_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="mtn_money_name" class="block text-sm font-medium text-gray-700">Nom MTN Money</label>
                            <input type="text" name="mtn_money_name" id="mtn_money_name" value="{{ old('mtn_money_name', $supportInfo->mtn_money_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="orange_money_number" class="block text-sm font-medium text-gray-700">Numéro Orange Money</label>
                            <input type="text" name="orange_money_number" id="orange_money_number" value="{{ old('orange_money_number', $supportInfo->orange_money_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="orange_money_name" class="block text-sm font-medium text-gray-700">Nom Orange Money</label>
                            <input type="text" name="orange_money_name" id="orange_money_name" value="{{ old('orange_money_name', $supportInfo->orange_money_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Cryptomonnaies -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cryptomonnaies</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="btc_address" class="block text-sm font-medium text-gray-700">Adresse Bitcoin (BTC)</label>
                            <input type="text" name="btc_address" id="btc_address" value="{{ old('btc_address', $supportInfo->btc_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="eth_address" class="block text-sm font-medium text-gray-700">Adresse Ethereum (ETH)</label>
                            <input type="text" name="eth_address" id="eth_address" value="{{ old('eth_address', $supportInfo->eth_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="usdt_address" class="block text-sm font-medium text-gray-700">Adresse USDT (ERC-20)</label>
                            <input type="text" name="usdt_address" id="usdt_address" value="{{ old('usdt_address', $supportInfo->usdt_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="usdt_ton_address" class="block text-sm font-medium text-gray-700">Adresse USDT (TON)</label>
                            <input type="text" name="usdt_ton_address" id="usdt_ton_address" value="{{ old('usdt_ton_address', $supportInfo->usdt_ton_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="usdt_bnb_address" class="block text-sm font-medium text-gray-700">Adresse USDT (BNB)</label>
                            <input type="text" name="usdt_bnb_address" id="usdt_bnb_address" value="{{ old('usdt_bnb_address', $supportInfo->usdt_bnb_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="pi_address" class="block text-sm font-medium text-gray-700">Adresse Pi Network</label>
                            <input type="text" name="pi_address" id="pi_address" value="{{ old('pi_address', $supportInfo->pi_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <!-- Informations de contact -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de contact</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">Téléphone de contact</label>
                            <input type="text" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $supportInfo->contact_phone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">Email de contact</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $supportInfo->contact_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="contact_address" class="block text-sm font-medium text-gray-700">Adresse de contact</label>
                            <input type="text" name="contact_address" id="contact_address" value="{{ old('contact_address', $supportInfo->contact_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <div class="flex space-x-3">
                        <button type="submit" name="action" value="save_draft" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Enregistrer en brouillon
                        </button>
                        <button type="submit" name="action" value="publish" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Publier sur le site
                        </button>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('support.index') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Voir la page publique
                        </a>
                        <button type="button" onclick="resetForm()" class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function resetForm() {
    if (confirm('Êtes-vous sûr de vouloir réinitialiser tous les champs ?')) {
        document.querySelectorAll('input[type="text"], input[type="email"], textarea, select').forEach(function(element) {
            element.value = '';
        });
        // Réinitialiser le statut à "Inactif"
        document.getElementById('is_active').value = '0';
    }
}

function scrollToForm() {
    // Faire défiler vers le formulaire
    const formSection = document.querySelector('.bg-white.shadow-sm.sm\\:rounded-lg:last-of-type');
    if (formSection) {
        formSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function toggleStatus() {
    const isActive = document.getElementById('is_active').value === '1';
    const newStatus = isActive ? '0' : '1';
    document.getElementById('is_active').value = newStatus;
    
    // Mettre à jour le texte du bouton
    const button = event.target.closest('button');
    if (button) {
        const textSpan = button.querySelector('span') || button;
        textSpan.textContent = isActive ? 'Activer' : 'Désactiver';
    }
    
    // Afficher un message de confirmation
    const message = isActive ? 'Page désactivée. Les coordonnées ne seront plus visibles publiquement.' : 'Page activée. Les coordonnées seront visibles publiquement.';
    alert(message);
}

// Fonction pour supprimer des coordonnées spécifiques
function clearSpecificField(fieldType) {
    const fields = {
        'bank': ['bank_name', 'bank_account', 'bank_iban', 'bank_swift'],
        'mobile': ['mtn_money_number', 'mtn_money_name', 'orange_money_number', 'orange_money_name'],
        'crypto': ['btc_address', 'eth_address', 'usdt_address', 'usdt_ton_address', 'usdt_bnb_address', 'pi_address'],
        'contact': ['contact_phone', 'contact_email', 'contact_address']
    };
    
    if (confirm(`Êtes-vous sûr de vouloir supprimer toutes les coordonnées ${fieldType} ?`)) {
        fields[fieldType].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.value = '';
            }
        });
    }
}
</script>
@endsection 
 
 