<script setup lang="ts">
withDefaults(
    defineProps<{
        type?: "button" | "submit" | "reset";
    }>(),
    {
        type: "button",
    }
);
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
