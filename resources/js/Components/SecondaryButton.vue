<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center rounded-md border border-orange-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-orange-700 shadow-sm transition duration-150 ease-in-out hover:bg-orange-50 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 disabled:opacity-25"
    >
        <slot />
    </button>
</template>
