<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Paramètres de Confidentialité') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Contrôlez qui peut voir votre profil et vos préférences de notifications.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Niveau de confidentialité -->
        <div>
            <x-input-label for="privacy_level" :value="__('Visibilité du profil')" />
            <select id="privacy_level" name="privacy_level" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="public" {{ old('privacy_level', $profile->privacy_level) === 'public' ? 'selected' : '' }}>{{ __('Public - Visible par tous') }}</option>
                <option value="members" {{ old('privacy_level', $profile->privacy_level) === 'members' ? 'selected' : '' }}>{{ __('Membres - Visible par les membres de l\'église') }}</option>
                <option value="friends" {{ old('privacy_level', $profile->privacy_level) === 'friends' ? 'selected' : '' }}>{{ __('Amis - Visible par vos amis uniquement') }}</option>
                <option value="private" {{ old('privacy_level', $profile->privacy_level) === 'private' ? 'selected' : '' }}>{{ __('Privé - Visible par vous uniquement') }}</option>
            </select>
            <p class="mt-1 text-sm text-gray-500">
                {{ __('Ce paramètre contrôle qui peut voir votre profil public.') }}
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('privacy_level')" />
        </div>

        <!-- Préférences de notifications -->
        <div>
            <x-input-label :value="__('Préférences de notifications')" />
            <div class="mt-2 space-y-3">
                @php
                    $notificationPrefs = old('notification_preferences', $profile->notification_preferences ?? []);
                    if (!is_array($notificationPrefs)) $notificationPrefs = [];
                @endphp
                
                <label class="flex items-center">
                    <input type="checkbox" name="notification_preferences[email]" value="1" {{ isset($notificationPrefs['email']) && $notificationPrefs['email'] ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Notifications par email') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="notification_preferences[sms]" value="1" {{ isset($notificationPrefs['sms']) && $notificationPrefs['sms'] ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Notifications par SMS') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="notification_preferences[push]" value="1" {{ isset($notificationPrefs['push']) && $notificationPrefs['push'] ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Notifications push (navigateur)') }}</span>
                </label>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('notification_preferences')" />
        </div>

        <!-- Langue -->
        <div>
            <x-input-label for="language" :value="__('Langue préférée')" />
            <select id="language" name="language" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="fr" {{ old('language', $profile->language) === 'fr' ? 'selected' : '' }}>{{ __('Français') }}</option>
                <option value="en" {{ old('language', $profile->language) === 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('language')" />
        </div>

        <!-- Thème -->
        <div>
            <x-input-label for="theme" :value="__('Thème d\'affichage')" />
            <select id="theme" name="theme" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="light" {{ old('theme', $profile->theme) === 'light' ? 'selected' : '' }}>{{ __('Clair') }}</option>
                <option value="dark" {{ old('theme', $profile->theme) === 'dark' ? 'selected' : '' }}>{{ __('Sombre') }}</option>
                <option value="auto" {{ old('theme', $profile->theme) === 'auto' ? 'selected' : '' }}>{{ __('Automatique (selon le système)') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('theme')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Sauvegarder') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Sauvegardé.') }}</p>
            @endif
        </div>
    </form>
</section> 