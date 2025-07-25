<form method="POST" action="{{ route('login') }}">
    @csrf
    <!-- Email Address -->
    <div>
        <x-input-label for="email" :value="__('E-mail')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <!-- Password -->
    <div class="mt-4">
        <x-input-label for="password" :value="__('Mot de passe')" />
        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>
    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
            <span class="ml-2 text-sm text-gray-600">{{ __('Souviens-toi de moi') }}</span>
        </label>
    </div>
    <div class="flex items-center justify-between mt-4">
        <a class="underline text-sm text-gray-600 hover:text-orange-600" href="{{ route('password.request') }}">
            {{ __('Mot de passe oublié?') }}
        </a>
        <x-primary-button class="ml-3">
            {{ __('Se connecter') }}
        </x-primary-button>
    </div>
</form> 