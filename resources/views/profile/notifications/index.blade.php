<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Préférences de Notification') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Configuration des Notifications') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Personnalisez vos préférences de notification pour rester informé des activités de l\'église.') }}
                            </p>
                        </header>

                        @if (session('status'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{ route('profile.notifications.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Canaux de notification -->
                            <div class="space-y-4">
                                <h3 class="text-md font-medium text-gray-900 border-b pb-2">
                                    {{ __('Canaux de Notification') }}
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input id="email_enabled" name="email_enabled" type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ $preferences->email_enabled ? 'checked' : '' }}>
                                        <label for="email_enabled" class="ml-2 text-sm text-gray-700">
                                            {{ __('Email') }}
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="sms_enabled" name="sms_enabled" type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ $preferences->sms_enabled ? 'checked' : '' }}>
                                        <label for="sms_enabled" class="ml-2 text-sm text-gray-700">
                                            {{ __('SMS') }}
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="push_enabled" name="push_enabled" type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ $preferences->push_enabled ? 'checked' : '' }}>
                                        <label for="push_enabled" class="ml-2 text-sm text-gray-700">
                                            {{ __('Notifications Push') }}
                                        </label>
                                    </div>

                                    <div class="flex items-center">
                                        <input id="whatsapp_enabled" name="whatsapp_enabled" type="checkbox" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                               {{ $preferences->whatsapp_enabled ? 'checked' : '' }}>
                                        <label for="whatsapp_enabled" class="ml-2 text-sm text-gray-700">
                                            {{ __('WhatsApp') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Types de notifications -->
                            <div class="space-y-4">
                                <h3 class="text-md font-medium text-gray-900 border-b pb-2">
                                    {{ __('Types de Notifications') }}
                                </h3>

                                @php
                                    $notificationTypes = [
                                        'events' => 'Événements',
                                        'prayer_requests' => 'Demandes de Prière',
                                        'announcements' => 'Annonces',
                                        'messages' => 'Messages Privés',
                                        'ministry_updates' => 'Mises à jour de Ministère',
                                        'birthday_reminders' => 'Rappels d\'Anniversaire',
                                        'spiritual_reminders' => 'Rappels Spirituels'
                                    ];
                                @endphp

                                @foreach($notificationTypes as $type => $label)
                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <h4 class="font-medium text-gray-900">{{ $label }}</h4>
                                            <div class="flex space-x-2">
                                                <button type="button" 
                                                        onclick="toggleAllChannels('{{ $type }}', true)"
                                                        class="text-xs bg-green-500 text-white px-2 py-1 rounded">
                                                    Tout activer
                                                </button>
                                                <button type="button" 
                                                        onclick="toggleAllChannels('{{ $type }}', false)"
                                                        class="text-xs bg-red-500 text-white px-2 py-1 rounded">
                                                    Tout désactiver
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                            <div class="flex items-center">
                                                <input id="{{ $type }}_email" name="{{ $type }}_email" type="checkbox" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ $preferences->isNotificationEnabled($type, 'email') ? 'checked' : '' }}>
                                                <label for="{{ $type }}_email" class="ml-2 text-xs text-gray-700">
                                                    {{ __('Email') }}
                                                </label>
                                            </div>

                                            <div class="flex items-center">
                                                <input id="{{ $type }}_sms" name="{{ $type }}_sms" type="checkbox" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ $preferences->isNotificationEnabled($type, 'sms') ? 'checked' : '' }}>
                                                <label for="{{ $type }}_sms" class="ml-2 text-xs text-gray-700">
                                                    {{ __('SMS') }}
                                                </label>
                                            </div>

                                            <div class="flex items-center">
                                                <input id="{{ $type }}_push" name="{{ $type }}_push" type="checkbox" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ $preferences->isNotificationEnabled($type, 'push') ? 'checked' : '' }}>
                                                <label for="{{ $type }}_push" class="ml-2 text-xs text-gray-700">
                                                    {{ __('Push') }}
                                                </label>
                                            </div>

                                            <div class="flex items-center">
                                                <input id="{{ $type }}_whatsapp" name="{{ $type }}_whatsapp" type="checkbox" 
                                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                       {{ $preferences->isNotificationEnabled($type, 'whatsapp') ? 'checked' : '' }}>
                                                <label for="{{ $type }}_whatsapp" class="ml-2 text-xs text-gray-700">
                                                    {{ __('WhatsApp') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Informations de contact -->
                            <div class="space-y-4">
                                <h3 class="text-md font-medium text-gray-900 border-b pb-2">
                                    {{ __('Informations de Contact') }}
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="whatsapp_number" :value="__('Numéro WhatsApp')" />
                                        <x-text-input id="whatsapp_number" name="whatsapp_number" type="tel" 
                                                     class="mt-1 block w-full" 
                                                     :value="old('whatsapp_number', $preferences->whatsapp_number)" 
                                                     placeholder="+1234567890" />
                                        <x-input-error class="mt-2" :messages="$errors->get('whatsapp_number')" />
                                    </div>

                                    <div>
                                        <x-input-label for="sms_number" :value="__('Numéro SMS')" />
                                        <x-text-input id="sms_number" name="sms_number" type="tel" 
                                                     class="mt-1 block w-full" 
                                                     :value="old('sms_number', $preferences->sms_number)" 
                                                     placeholder="+1234567890" />
                                        <x-input-error class="mt-2" :messages="$errors->get('sms_number')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Heures silencieuses -->
                            <div class="space-y-4">
                                <h3 class="text-md font-medium text-gray-900 border-b pb-2">
                                    {{ __('Heures Silencieuses') }}
                                </h3>
                                
                                <div class="flex items-center mb-4">
                                    <input id="respect_quiet_hours" name="respect_quiet_hours" type="checkbox" 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ $preferences->respect_quiet_hours ? 'checked' : '' }}>
                                    <label for="respect_quiet_hours" class="ml-2 text-sm text-gray-700">
                                        {{ __('Respecter les heures silencieuses') }}
                                    </label>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="quiet_hours_start" :value="__('Début (HH:MM)')" />
                                        <x-text-input id="quiet_hours_start" name="quiet_hours_start" type="time" 
                                                     class="mt-1 block w-full" 
                                                     :value="old('quiet_hours_start', $preferences->quiet_hours_start?->format('H:i'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('quiet_hours_start')" />
                                    </div>

                                    <div>
                                        <x-input-label for="quiet_hours_end" :value="__('Fin (HH:MM)')" />
                                        <x-text-input id="quiet_hours_end" name="quiet_hours_end" type="time" 
                                                     class="mt-1 block w-full" 
                                                     :value="old('quiet_hours_end', $preferences->quiet_hours_end?->format('H:i'))" />
                                        <x-input-error class="mt-2" :messages="$errors->get('quiet_hours_end')" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Sauvegarder les Préférences') }}</x-primary-button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAllChannels(type, enabled) {
            const checkboxes = [
                document.getElementById(`${type}_email`),
                document.getElementById(`${type}_sms`),
                document.getElementById(`${type}_push`),
                document.getElementById(`${type}_whatsapp`)
            ];
            
            checkboxes.forEach(checkbox => {
                if (checkbox) {
                    checkbox.checked = enabled;
                }
            });
        }
    </script>
</x-app-layout> 