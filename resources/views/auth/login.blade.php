<x-guest-layout>
    <div class="w-full max-w-md px-6 py-4 bg-white shadow-lg overflow-hidden sm:rounded-lg">
        <div x-data="{ tab: 'login' }">
            <div class="flex justify-center mb-6">
                <button @click="tab = 'login'" :class="tab === 'login' ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-l focus:outline-none">Connexion</button>
                <button @click="tab = 'register'" :class="tab === 'register' ? 'bg-turquoise-600 text-white' : 'bg-gray-200 text-gray-700'" class="px-4 py-2 rounded-r focus:outline-none">Inscription</button>
            </div>
            <div x-show="tab === 'login'">
                <h2 class="text-xl font-bold mb-4 text-center text-orange-700">Connexion</h2>
                @include('auth.partials.login-form')
            </div>
            <div x-show="tab === 'register'">
                <h2 class="text-xl font-bold mb-4 text-center text-turquoise-700">Inscription</h2>
                @include('auth.partials.register-form')
            </div>
        </div>
    </div>
</x-guest-layout>
