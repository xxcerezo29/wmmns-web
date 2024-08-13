<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { PencilIcon, PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

import { paginated } from '../types/interface';
import { User } from '../types';
import { useToast } from 'vue-toastification';
import { onMounted, ref } from 'vue';
import SecondaryButton from '@/Components/ui/daisyUI/SecondaryButton.vue';
import Modal from '@/Components/ui/daisyUI/Modal.vue';
import Calendar from './Components/Calendar/Calendar.vue';

const props = defineProps<{
    users: paginated<User>
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
            <Calendar />
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