<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ArrowDownTrayIcon} from '@heroicons/vue/24/outline';

import { useToast } from 'vue-toastification';
import { onMounted, ref } from 'vue';
import Modal from '@/Components/ui/daisyUI/Modal.vue';

const props = defineProps<{
    files: Array<{
        name: string;
        date: string;
    }>;
}>();

const toast = useToast();
const targetToDelete = ref();
const searchTerm = ref("");

const search = () => {
    router.get(
        route("trucks.list"),
        {
            searchTerm: searchTerm.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};

const handleDelete = () => {
    const deleteForm = useForm({});
    deleteForm.delete(route('trucks.delete', { id: targetToDelete.value }),
        {
            onSuccess: () => {
                toast.success('Driver Deleted.');
            },
            onError: () => {
                toast.success("Driver can't Deleted.");
            }
        })
}


onMounted(() => {
    if (usePage().props.flash.message) {
        if (usePage().props.flash.status === "success")
            toast.success(usePage().props.flash.message)
        else if (usePage().props.flash.status === "error")
            toast.error(usePage().props.flash.message)
    }
})
</script>
<template>

    <Head title="Truck" />

    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Truck</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="flex flex-row gap-2 justify-end mb-2">
                    <!-- <div>
                        <input type="text" placeholder="Search..." class="input input-bordered w-full max-w-xs"
                            @keyup="search" v-model="searchTerm" />
                    </div> -->
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>File</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="props.files.length > 0" v-for="(file, index) in props.files">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ file.name }}</td>
                                        <td>{{ file.date }}</td>
                                        <td>
                                            <div class="flex gap-2">
                                                <a :href="route('backup.download', {
                                                    filename: file.name,
                                                })
                                                    " class="p-2 text-blue-600 tooltip" data-tip="Download Zip">
                                                    <ArrowDownTrayIcon class="h-5" />
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- <tr v-for="(truck, index) in props.trucks.data">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ truck.plate_number}}</td>
                                        <td>{{ truck.barangay }}</td>
                                        <td>
                                            <div class="flex gap-2">
                                                <LinkButton class="!bg-green-600" :href="route('trucks.edit', {id: truck.id})" label="">
                                                    <template #icon>
                                                        <PencilIcon class="h-4" />
                                                    </template>
                                                </LinkButton>
                                                <LinkButton class="!bg-blue-600" :href="route('trucks.show', {id: truck.id})" label="">
                                                    <template #icon>
                                                        <EyeIcon class="h-4" />
                                                    </template>
                                                </LinkButton>
                                                <SecondaryButton @click="targetToDelete = truck.id" onclick="deleteModal.showModal()" class="!bg-red-600 text-white"> <TrashIcon class="h-4" /> </SecondaryButton>
                                            </div>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                            <!-- <Pagination :paginate="props.trucks" /> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal id="deleteModal" title="Role Delete Form">
            <template #body>
                <div class="mt-5">
                    <span class="text-red-700">You are about to delete this user?</span>
                </div>
            </template>
            <template #actions>
                <PrimaryButton @click="handleDelete" onclick="deleteModal.close()">Yes</PrimaryButton>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>