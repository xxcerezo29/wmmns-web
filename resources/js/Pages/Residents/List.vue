<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {
    EyeIcon,
    PencilIcon,
    PlusCircleIcon,
    TrashIcon,
    ArrowDownCircleIcon,
} from "@heroicons/vue/24/outline";

import { paginated, Resident } from "../../types/interface";
import { useToast } from "vue-toastification";
import { onMounted, ref } from "vue";
import SecondaryButton from "@/Components/ui/daisyUI/SecondaryButton.vue";
import Modal from "@/Components/ui/daisyUI/Modal.vue";
import Pagination from "@/Components/ui/daisyUI/Pagination.vue";
import LinkButton from "@/Components/ui/daisyUI/LinkButton.vue";

const props = defineProps<{
    residents: paginated<Resident>;
}>();

const toast = useToast();

const targetToDelete = ref();
const searchTerm = ref("");

const search = () => {
    router.get(
        route("users.residents.list"),
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
    deleteForm.delete(
        route("users.residents.destroy", { id: targetToDelete.value }),
        {
            onSuccess: () => {
                toast.success("Resident Deleted.");
            },
            onError: () => {
                toast.success("Resident can't Deleted.");
            },
        }
    );
};

onMounted(() => {
    if (usePage().props.flash.message) {
        if (usePage().props.flash.status === "success")
            toast.success(usePage().props.flash.message);
        else if (usePage().props.flash.status === "error")
            toast.error(usePage().props.flash.message);
    }
});
</script>
<template>
    <Head title="Residents" />

    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Residents
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <a
                        :href="route('users.residents.pdf')"
                        target="_blank"
                        class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-slate-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <ArrowDownCircleIcon class="h-7" />
                        Download PDF
                    </a>
                </div>
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5"
                >
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Barangay</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(resident, index) in props
                                            .residents.data"
                                    >
                                        <td>{{ index + 1 }}</td>
                                        <td>
                                            {{ resident.firstname }}
                                            {{ resident.lastname }}
                                        </td>
                                        <td>{{ resident.email }}</td>
                                        <td>{{ resident.barangay }}</td>
                                        <td>
                                            <LinkButton
                                                class="!bg-blue-600"
                                                :href="
                                                    route(
                                                        'users.residents.show',
                                                        { id: resident.id }
                                                    )
                                                "
                                                label=""
                                            >
                                                <template #icon>
                                                    <EyeIcon class="h-4" />
                                                </template>
                                            </LinkButton>
                                            <SecondaryButton
                                                @click="
                                                    targetToDelete = resident.id
                                                "
                                                onclick="deleteModal.showModal()"
                                                class="!bg-red-600 text-white"
                                            >
                                                <TrashIcon class="h-4" />
                                            </SecondaryButton>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination :paginate="props.residents" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal id="deleteModal" title="Resident Delition Form">
            <template #body>
                <div class="mt-5">
                    <span class="text-red-700"
                        >You are about to delete this resident?</span
                    >
                </div>
            </template>
            <template #actions>
                <PrimaryButton
                    @click="handleDelete"
                    onclick="deleteModal.close()"
                    >Yes</PrimaryButton
                >
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
