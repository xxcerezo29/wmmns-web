<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Route } from '../types/interface';
import MapView from './Component/MapView.vue';
import { onMounted, ref } from 'vue';

interface Iwaypoint {
    lat: number;
    lng: number;
}

const props = defineProps<{
    route: Route
}>();

const waypoint = ref<Array<Iwaypoint>>([]);

onMounted(() => {
    if(props.route){
        const _waypoint = JSON.parse(props.route.waypoint)
        _waypoint.forEach((element: Iwaypoint) => {
            waypoint.value?.push(element);
        });
    }
    
})

</script>

<template>

    <Head title="Complaint" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Route Details</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Route Name
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ route.name }}
                                </dd>
                            </div>
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Barangay
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ route.barangay }}
                                </dd>
                            </div>
                        </dl>
                        <MapView :waypoint="waypoint" />
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>