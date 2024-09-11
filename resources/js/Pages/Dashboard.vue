<script setup lang="ts">
import Stat from '@/Components/ui/daisyUI/Stat.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { UsersIcon } from '@heroicons/vue/24/outline';
import { Head } from '@inertiajs/vue3';

const props = defineProps<{
    stats: Array<{
        label: string,
        value: string|number,
        description: string,

    }>
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full">
                        <Stat v-for="(stat, index) in props.stats" :stat="stat">
                            <template #icon>
                                <UsersIcon class="h-7" />
                            </template>
                        </Stat>
                    </div>
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
