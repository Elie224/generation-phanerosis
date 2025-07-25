<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-turquoise-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-turquoise-500 active:bg-turquoise-700 focus:outline-none focus:ring-2 focus:ring-turquoise-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
