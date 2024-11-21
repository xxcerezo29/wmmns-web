<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {
    EyeIcon,
    PencilIcon,
    PlusCircleIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";

import { paginated, Route } from "../../types/interface";
import { useToast } from "vue-toastification";
import { onMounted, ref } from "vue";
import SecondaryButton from "@/Components/ui/daisyUI/SecondaryButton.vue";
import Modal from "@/Components/ui/daisyUI/Modal.vue";
import Pagination from "@/Components/ui/daisyUI/Pagination.vue";
import LinkButton from "@/Components/ui/daisyUI/LinkButton.vue";
const props = defineProps<{
    routes: paginated<Route>;
}>();

const toast = useToast();

const targetToDelete = ref();

const searchTerm = ref("");

const search = () => {
    router.get(
        route("routes.list"),
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
    deleteForm.delete(route("routes.destroy", { id: targetToDelete.value }), {
        onSuccess: () => {
            toast.success("Route Deleted.");
        },
        onError: () => {
            toast.success("Route can't Deleted.");
        },
    });
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
    <Head title="Routes" />

    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Routes
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
                    <Link
                        :href="route('routes.create')"
                        class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-kwikweb-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"
                        ><PlusCircleIcon class="h-7" />Add Routes</Link
                    >
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
                                        <th>Route Name</th>
                                        <th>Barangay</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(_route, index) in props.routes
                                            .data"
                                    >
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ _route.name }}</td>
                                        <td>{{ _route.barangay }}</td>
                                        <td>
                                            <div class="flex gap-2">
                                                <Link
                                                    class="inline-flex items-center btn px-4 py-3 text-white bg-green-600 dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-green-400 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150"
                                                    :href="
                                                        route('routes.edit', {
                                                            id: _route.id,
                                                        })
                                                    "
                                                    ><PencilIcon class="h-4"
                                                /></Link>
                                                <LinkButton
                                                    class="!bg-blue-600"
                                                    :href="
                                                        route('routes.show', {
                                                            id: _route.id,
                                                        })
                                                    "
                                                    label=""
                                                >
                                                    <template #icon>
                                                        <EyeIcon class="h-4" />
                                                    </template>
                                                </LinkButton>
                                                <SecondaryButton
                                                    @click="
                                                        targetToDelete =
                                                            _route.id
                                                    "
                                                    onclick="deleteModal.showModal()"
                                                    class="!bg-red-600 text-white"
                                                >
                                                    <TrashIcon class="h-4" />
                                                </SecondaryButton>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <Pagination :paginate="props.routes" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal id="deleteModal" title="Route Delete Form">
            <template #body>
                <div class="mt-5">
                    <span class="text-red-700"
                        >You are about to delete the route?</span
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
