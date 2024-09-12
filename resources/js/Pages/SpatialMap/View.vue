<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import Map from './Component/Map.vue';

const props = defineProps<{
    spatial_map: Array<{
        ADM1_PCODE: string;
        ADM1_EN: string;
        ADM2_PCODE: string;
        ADM2_EN: string;
        ADM3_PCODE: string;
        ADM3_EN: string;
        ADM4_PCODE: string;
        ADM4_EN: string;
        geometry: string;
    }>,
    report_counts: Record<string, number>;
}>()

</script>

<template>
    <Head title="Spatial Map" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Spatial Map</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-7">
                    <Map :spatial_map="spatial_map" :report_counts="report_counts" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>