<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Truck } from '../../types/interface';
import SecondaryButton from '@/Components/ui/daisyUI/SecondaryButton.vue';
import { ref } from 'vue';

const props = defineProps<{
    truck: Truck
}>();

</script>

<template>
    <Head title="Complaint" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Complaint Details</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Plate Number
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ truck.plate_number }}
                                </dd>
                            </div>
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Driver Assigned
                                </dt>
                                <dd v-if="truck.driver" class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ truck.driver.firstname }} {{ truck.driver.middlename }} {{ truck.driver.lastname }}
                                </dd>
                            </div>
                            <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">
                                    Barangay
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    {{ truck.barangay }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>