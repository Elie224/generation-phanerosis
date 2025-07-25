<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations de Base') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Mettez à jour vos informations de base et votre photo de profil.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Avatar et Bannière -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Avatar -->
            <div>
                <x-input-label for="avatar" :value="__('Photo de profil')" />
                <div class="mt-2 flex items-center space-x-4">
                    <div class="w-20 h-20 rounded-full overflow-hidden bg-gray-200">
                        <img src="{{ $profile->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF jusqu'à 2MB</p>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>

            <!-- Bannière -->
            <div>
                <x-input-label for="banner" :value="__('Bannière de profil')" />
                <div class="mt-2">
                    @if($profile->banner_url)
                        <div class="w-full h-32 rounded-lg overflow-hidden bg-gray-200 mb-2">
                            <img src="{{ $profile->banner_url }}" alt="Bannière" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <input type="file" id="banner" name="banner" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF jusqu'à 5MB</p>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('banner')" />
            </div>
        </div>

        <!-- Nom et Email -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="name" :value="__('Nom complet')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Votre adresse email n\'est pas vérifiée.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio" :value="__('Biographie')" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Parlez-nous un peu de vous...">{{ old('bio', $profile->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
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

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>
</section> 