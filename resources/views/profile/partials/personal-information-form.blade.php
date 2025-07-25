<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations Personnelles') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Vos informations personnelles et de contact.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Contact -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="phone" :value="__('Téléphone')" />
                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-full" :value="old('phone', $profile->phone)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-input-label for="gender" :value="__('Genre')" />
                <select id="gender" name="gender" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="">{{ __('Sélectionner...') }}</option>
                    <option value="male" {{ old('gender', $profile->gender) === 'male' ? 'selected' : '' }}>{{ __('Homme') }}</option>
                    <option value="female" {{ old('gender', $profile->gender) === 'female' ? 'selected' : '' }}>{{ __('Femme') }}</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('gender')" />
            </div>
        </div>

        <!-- Date de naissance -->
        <div>
            <x-input-label for="birth_date" :value="__('Date de naissance')" />
            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block w-full" :value="old('birth_date', $profile->birth_date?->format('Y-m-d'))" />
            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
        </div>

        <!-- Adresse -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <x-input-label for="address" :value="__('Adresse')" />
                <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $profile->address)" autocomplete="street-address" />
                <x-input-error class="mt-2" :messages="$errors->get('address')" />
            </div>

            <div>
                <x-input-label for="city" :value="__('Ville')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $profile->city)" autocomplete="address-level2" />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>
        </div>

        <div>
            <x-input-label for="country" :value="__('Pays')" />
            <x-text-input id="country" name="country" type="text" class="mt-1 block w-full" :value="old('country', $profile->country)" autocomplete="country-name" />
            <x-input-error class="mt-2" :messages="$errors->get('country')" />
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