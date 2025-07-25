<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations d\'Église') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Votre participation et engagement dans l\'église.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Membre depuis -->
        <div>
            <x-input-label for="member_since" :value="__('Membre depuis')" />
            <x-text-input id="member_since" name="member_since" type="date" class="mt-1 block w-full" :value="old('member_since', $profile->member_since?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('member_since')" />
        </div>

        <!-- Groupe d'âge -->
        <div>
            <x-input-label for="age_group" :value="__('Groupe d\'âge')" />
            <select id="age_group" name="age_group" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">{{ __('Sélectionner...') }}</option>
                <option value="jeunesse" {{ old('age_group', $profile->age_group) === 'jeunesse' ? 'selected' : '' }}>{{ __('Jeunesse (12-25 ans)') }}</option>
                <option value="adultes" {{ old('age_group', $profile->age_group) === 'adultes' ? 'selected' : '' }}>{{ __('Adultes (26-65 ans)') }}</option>
                <option value="seniors" {{ old('age_group', $profile->age_group) === 'seniors' ? 'selected' : '' }}>{{ __('Seniors (65+ ans)') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('age_group')" />
        </div>

        <!-- Ministères -->
        <div>
            <x-input-label for="ministries" :value="__('Ministères de participation')" />
            <div class="mt-2 space-y-2">
                @php
                    $selectedMinistries = old('ministries', $profile->ministries ?? []);
                    if (!is_array($selectedMinistries)) $selectedMinistries = [];
                @endphp
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="musique" {{ in_array('musique', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Musique et Louange') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="jeunesse" {{ in_array('jeunesse', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Ministère de Jeunesse') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="enseignement" {{ in_array('enseignement', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Enseignement') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="accueil" {{ in_array('accueil', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Accueil') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="priere" {{ in_array('priere', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Ministère de Prière') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="evangelisation" {{ in_array('evangelisation', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Évangélisation') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="social" {{ in_array('social', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Action Sociale') }}</span>
                </label>
                
                <label class="flex items-center">
                    <input type="checkbox" name="ministries[]" value="technique" {{ in_array('technique', $selectedMinistries) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">{{ __('Support Technique') }}</span>
                </label>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('ministries')" />
        </div>

        <!-- Groupe de maison -->
        <div>
            <x-input-label for="small_group" :value="__('Groupe de maison')" />
            <x-text-input id="small_group" name="small_group" type="text" class="mt-1 block w-full" :value="old('small_group', $profile->small_group)" placeholder="Nom de votre groupe de maison..." />
            <x-input-error class="mt-2" :messages="$errors->get('small_group')" />
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