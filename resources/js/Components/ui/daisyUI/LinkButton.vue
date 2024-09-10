<script setup lang="ts">
import { Link } from '@inertiajs/vue3';



const props = withDefaults(defineProps<{
    label: string
}>(), {
    route: 'home',
    label: 'Label'
});
</script>
<template>
    <Link href="" class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-slate-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150">
        <slot name="icon" />
        {{props.label}}
    </Link>
</template>