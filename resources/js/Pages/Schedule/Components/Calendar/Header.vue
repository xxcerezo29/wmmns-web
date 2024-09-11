<script setup lang="ts">
import PrimaryButton from '@/Components/ui/daisyUI/PrimaryButton.vue';
import { PlusCircleIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    month: string;
    year: string;
}>();
</script>
<template>
    <header class="flex items-center justify-between border-b border-gray-200 px-6 py-4 lg:flex-none">
        <h1 class="text-base font-semibold leading-6 text-gray-900">
            <time datetime="2022-01">{{month}} {{year}}</time>
        </h1>
        <div class="flex items-center">
            <div class=" md:ml-4 md:flex md:items-center">
                <Link :href="route('schedule.create')" class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-kwikweb-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"><PlusCircleIcon class="h-7" />Add Schedule</Link>
            </div>
        </div>
    </header>
</template>