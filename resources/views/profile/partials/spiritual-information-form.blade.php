<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations Spirituelles') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Votre parcours spirituel et vos dons.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Dates importantes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="conversion_date" :value="__('Date de conversion')" />
                <x-text-input id="conversion_date" name="conversion_date" type="date" class="mt-1 block w-full" :value="old('conversion_date', $profile->conversion_date?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('conversion_date')" />
            </div>

            <div>
                <x-input-label for="baptism_date" :value="__('Date de baptême')" />
                <x-text-input id="baptism_date" name="baptism_date" type="date" class="mt-1 block w-full" :value="old('baptism_date', $profile->baptism_date?->format('Y-m-d'))" />
                <x-input-error class="mt-2" :messages="$errors->get('baptism_date')" />
            </div>
        </div>

        <!-- Rôle ministériel -->
        <div>
            <x-input-label for="ministry_role" :value="__('Rôle ministériel')" />
            <x-text-input id="ministry_role" name="ministry_role" type="text" class="mt-1 block w-full" :value="old('ministry_role', $profile->ministry_role)" placeholder="Ex: Diacre, Ancien, Responsable de jeunesse..." />
            <x-input-error class="mt-2" :messages="$errors->get('ministry_role')" />
        </div>

        <!-- Dons spirituels -->
        <div>
            <x-input-label for="spiritual_gifts" :value="__('Dons spirituels')" />
            <textarea id="spiritual_gifts" name="spiritual_gifts" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Ex: Enseignement, Prophétie, Service, Évangélisation...">{{ old('spiritual_gifts', $profile->spiritual_gifts) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('spiritual_gifts')" />
        </div>

        <!-- Témoignage -->
        <div>
            <x-input-label for="testimony" :value="__('Témoignage personnel')" />
            <textarea id="testimony" name="testimony" rows="6" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Partagez votre témoignage de conversion ou une expérience spirituelle marquante...">{{ old('testimony', $profile->testimony) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('testimony')" />
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