@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if($info && $info->is_active)
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $info->title ?? 'Soutenez notre ministère' }}</h1>
                        @if($info->description)
                            <p class="text-lg text-gray-600 max-w-3xl mx-auto">{{ $info->description }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Coordonnées bancaires -->
                        @if($info->bank_name || $info->bank_account || $info->bank_iban || $info->bank_swift)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Coordonnées bancaires
                            </h3>
                            <div class="space-y-3">
                                @if($info->bank_name)
                                    <div>
                                        <span class="font-medium text-gray-700">Banque :</span>
                                        <span class="text-gray-900">{{ $info->bank_name }}</span>
                                    </div>
                                @endif
                                @if($info->bank_account)
                                    <div>
                                        <span class="font-medium text-gray-700">Compte :</span>
                                        <span class="text-gray-900 font-mono">{{ $info->bank_account }}</span>
                                    </div>
                                @endif
                                @if($info->bank_iban)
                                    <div>
                                        <span class="font-medium text-gray-700">IBAN :</span>
                                        <span class="text-gray-900 font-mono">{{ $info->bank_iban }}</span>
                                    </div>
                                @endif
                                @if($info->bank_swift)
                                    <div>
                                        <span class="font-medium text-gray-700">SWIFT/BIC :</span>
                                        <span class="text-gray-900 font-mono">{{ $info->bank_swift }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Mobile Money -->
                        @if($info->mtn_money_number || $info->orange_money_number)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                Mobile Money
                            </h3>
                            <div class="space-y-4">
                                @if($info->mtn_money_number)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">M</span>
                                        </div>
                                        <span class="font-semibold text-gray-900">MTN Money</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div>
                                            <span class="font-medium text-gray-700">Numéro :</span>
                                            <span class="text-gray-900 font-mono">{{ $info->mtn_money_number }}</span>
                                        </div>
                                        @if($info->mtn_money_name)
                                        <div>
                                            <span class="font-medium text-gray-700">Nom :</span>
                                            <span class="text-gray-900">{{ $info->mtn_money_name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif

                                @if($info->orange_money_number)
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                    <div class="flex items-center mb-2">
                                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-sm">O</span>
                                        </div>
                                        <span class="font-semibold text-gray-900">Orange Money</span>
                                    </div>
                                    <div class="space-y-1">
                                        <div>
                                            <span class="font-medium text-gray-700">Numéro :</span>
                                            <span class="text-gray-900 font-mono">{{ $info->orange_money_number }}</span>
                                        </div>
                                        @if($info->orange_money_name)
                                        <div>
                                            <span class="font-medium text-gray-700">Nom :</span>
                                            <span class="text-gray-900">{{ $info->orange_money_name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Cryptomonnaies -->
                    @if($info->btc_address || $info->eth_address || $info->usdt_address || $info->usdt_ton_address || $info->usdt_bnb_address || $info->pi_address)
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Cryptomonnaies
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @if($info->btc_address)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">₿</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">Bitcoin (BTC)</span>
                                </div>
                                <div class="bg-white rounded border p-3">
                                    <span class="text-xs text-gray-500 font-mono break-all">{{ $info->btc_address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($info->eth_address)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">Ξ</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">Ethereum (ETH)</span>
                                </div>
                                <div class="bg-white rounded border p-3">
                                    <span class="text-xs text-gray-500 font-mono break-all">{{ $info->eth_address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($info->usdt_address)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">$</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">USDT (ERC-20)</span>
                                </div>
                                <div class="bg-white rounded border p-3">
                                    <span class="text-xs text-gray-500 font-mono break-all">{{ $info->usdt_address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($info->usdt_ton_address)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">T</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">USDT (TON)</span>
                                </div>
                                <div class="bg-white rounded border p-3">
                                    <span class="text-xs text-gray-500 font-mono break-all">{{ $info->usdt_ton_address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($info->usdt_bnb_address)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-yellow-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">B</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">USDT (BNB)</span>
                                </div>
                                <div class="bg-white rounded border p-3">
                                    <span class="text-xs text-gray-500 font-mono break-all">{{ $info->usdt_bnb_address }}</span>
                                </div>
                            </div>
                            @endif

                            @if($info->pi_address)
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <div class="w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-xs">π</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">Pi Network</span>
                                </div>
                                <div class="bg-white rounded border p-3">
                                    <span class="text-xs text-gray-500 font-mono break-all">{{ $info->pi_address }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Informations de contact -->
                    @if($info->contact_phone || $info->contact_email || $info->contact_address)
                    <div class="mt-8 bg-blue-50 rounded-lg p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Contact
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @if($info->contact_phone)
                            <div>
                                <span class="font-medium text-gray-700">Téléphone :</span>
                                <span class="text-gray-900">{{ $info->contact_phone }}</span>
                            </div>
                            @endif
                            @if($info->contact_email)
                            <div>
                                <span class="font-medium text-gray-700">Email :</span>
                                <span class="text-gray-900">{{ $info->contact_email }}</span>
                            </div>
                            @endif
                            @if($info->contact_address)
                            <div>
                                <span class="font-medium text-gray-700">Adresse :</span>
                                <span class="text-gray-900">{{ $info->contact_address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($info->thank_you_message)
                    <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-green-900 mb-2">Message de remerciement</h3>
                        <p class="text-green-800">{{ $info->thank_you_message }}</p>
                    </div>
                    @endif

                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune information disponible</h3>
                        <p class="mt-1 text-sm text-gray-500">Les coordonnées de don ne sont pas encore configurées.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 
 
 