<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import { Schedule } from '../../types/interface';
import { useToast } from 'vue-toastification';
import { onMounted, ref } from 'vue';
import Modal from '@/Components/ui/daisyUI/Modal.vue';
import Calendar from './Components/Calendar/Calendar.vue';
import Pagination from '@/Components/ui/daisyUI/Pagination.vue';


const props = defineProps<{
    schedule: {
        monday: Array<Schedule>;
        tuesday: Array<Schedule>;
        wednesday: Array<Schedule>;
        thursday: Array<Schedule>;
        friday: Array<Schedule>;
        saturday: Array<Schedule>;
        sunday: Array<Schedule>;
    };
    pagination: {
        current_page : number;
        last_page: number;
        next_page_url: string;
        prev_page_url: string;
    }
}>();

const toast = useToast();

const targetToDelete = ref();

const handleDelete = () => {
    const deleteForm = useForm({});
    deleteForm.delete(route('users.all.delete', { id: targetToDelete.value }),
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

    <Head title="Schedules" />

    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Schedules</h2>
        </template>

        <div class="py-12">
            <Calendar :pagination="pagination" :schedule="props.schedule" />
            <Pagination :paginate="pagination" />
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