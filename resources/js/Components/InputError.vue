<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message">
        <p class="text-sm text-turquoise-600">
            {{ message }}
        </p>
    </div>
</template>
