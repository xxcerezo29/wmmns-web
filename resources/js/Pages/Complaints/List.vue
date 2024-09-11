<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { hasRole } from '@/functions';
import { IComplaint, paginated } from '../types/interface';
import { EyeDropperIcon, EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/ui/daisyUI/SecondaryButton.vue';
import Pagination from '@/Components/ui/daisyUI/Pagination.vue';
import { ref } from 'vue';

const props = defineProps<{
    complaints: paginated<IComplaint>
}>();


const formatReportType = (type: string) => {
    return type.replace(/_/g, ' ').replace(/\b\w/g, (char) => char.toUpperCase());
};

const searchTerm = ref("");

const search = () => {
    router.get(
        route("complaints.list"),
        {
            searchTerm: searchTerm.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};

</script>

<template>
    <Head title="Complaints"/>
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Complaints</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="flex flex-row gap-2 justify-end mb-2">
                    <div>
                        <input
                            type="text"
                            placeholder="Search..."
                            class="input input-bordered w-full max-w-xs"
                            @keyup="search"
                            v-model="searchTerm"
                        />
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Ref. Id</th>
                                        <th>Report Type</th>
                                        <th>Description</th>
                                        <th>Schedule</th>
                                        <th v-if="hasRole('admin')">Barangay</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(complaint, index) in props.complaints.data">
                                        <td>{{ index+1 }}</td>
                                        <td>{{complaint.reference_number}}</td>
                                        <td>{{ formatReportType(complaint.report_type) }}</td>
                                        <td>{{ complaint.description }}</td>
                                        <td>
                                            <span v-if="complaint.schedule_id">
                                                {{ complaint.schedule.day }}-{{ complaint.schedule.time }}: {{ complaint.schedule.truck.plate_number }}
                                            </span>
                                            <span v-else>
                                                -
                                            </span>
                                        </td>
                                        <td v-if="hasRole('admin')">{{ complaint.barangay }}</td>
                                        <td>
                                            <div class="badge text-white uppercase" :class="{
                                                'bg-green-600': complaint.status === 'resolved',
                                                'bg-yellow-600' : complaint.status === 'reviewed',
                                                'bg-red-600': complaint.status === 'pending',
                                                'bg-gray-600': complaint.status === 'closed'
                                            }">{{ complaint.status }}</div>
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <Link class="inline-flex items-center btn px-4 py-3 text-white bg-green-600 dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-green-400 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150" :href="route('complaints.view', {id: complaint.reference_number})"><EyeIcon class="h-4" /></Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination :paginate="props.complaints" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>