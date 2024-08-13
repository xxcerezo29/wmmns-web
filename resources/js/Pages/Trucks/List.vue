
<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { PencilIcon, PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

import { paginated, Truck } from '../types/interface';
import { useToast } from 'vue-toastification';
import { onMounted, ref } from 'vue';
import SecondaryButton from '@/Components/ui/daisyUI/SecondaryButton.vue';
import Modal from '@/Components/ui/daisyUI/Modal.vue';
import { hasRole } from '@/functions';

const props = defineProps<{
    trucks: paginated<Truck>
}>();

const toast = useToast();

const targetToDelete = ref();

const handleDelete = () => {
    const deleteForm = useForm({});
    deleteForm.delete(route('trucks.delete', {id: targetToDelete.value}), 
    {
        onSuccess: ()=>{
            toast.success('Driver Deleted.');
        },
        onError: () => {
            toast.success("Driver can't Deleted.");
        }
    })
}


onMounted(()=> {
    if(usePage().props.flash.message){
        if(usePage().props.flash.status === "success")
            toast.success(usePage().props.flash.message)
        else if(usePage().props.flash.status === "error")
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
                <Link :href="route('trucks.create')" class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-kwikweb-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"><PlusCircleIcon class="h-7" />Add Truck</Link>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Plate Number</th>
                                        <th>Barangay</th>
                                        <th>Actions</th>
                                      </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(truck, index) in props.trucks.data">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ truck.plate_number}}</td>
                                        <td>{{ truck.barangay }}</td>
                                        <td>
                                            <div class="flex gap-2">
                                                <Link class="inline-flex items-center btn px-4 py-3 text-white bg-green-600 dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-green-400 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150" :href="route('trucks.edit', {id: truck.id})"><PencilIcon class="h-4" /></Link>
                                                <SecondaryButton @click="targetToDelete = truck.id" onclick="deleteModal.showModal()" class="!bg-red-600 text-white"> <TrashIcon class="h-4" /> </SecondaryButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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