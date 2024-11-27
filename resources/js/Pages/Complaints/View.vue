<script setup lang="ts">
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { IComplaint } from "../../types/interface";
import SecondaryButton from "@/Components/ui/daisyUI/SecondaryButton.vue";
import { ref } from "vue";
import Modal from "@/Components/ui/daisyUI/Modal.vue";

const props = defineProps<{
    complaint: IComplaint;
}>();
const actionType = ref("");

const formatReportType = (type: string) => {
    return type
        .replace(/_/g, " ")
        .replace(/\b\w/g, (char) => char.toUpperCase());
};

const form = useForm({});

const selectedImage = ref("");

const selectImage = (image: string) => {
    selectedImage.value = image;
};

const reviewed_submit = () => {
    // form.get(route("complaints.reviewed", { id: props.complaint.id }));
    actionType.value = "reviewed";
};
const resolved_submit = () => {
    actionType.value = "resolved";
    // form.get(route("complaints.resolved", { id: props.complaint.id }));
};
const closed_submit = () => {
    actionType.value = "closed";
    // form.get(route("complaints.closed", { id: props.complaint.id }));
};
const review = () => {
    form.get(route("complaints.reviewed", { id: props.complaint.id }));
};
const resolved = () => {
    form.get(route("complaints.resolved", { id: props.complaint.id }));
};
const closed = () => {
    form.get(route("complaints.closed", { id: props.complaint.id }));
};
</script>
<template>
    <Head title="Complaint" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Complaint Details
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5"
                >
                    <div class="p-6 text-gray-900">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Reference Number
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.reference_number }}
                                </dd>
                            </div>
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Status
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    <div
                                        class="badge text-white uppercase"
                                        :class="{
                                            'bg-green-600':
                                                complaint.status === 'resolved',
                                            'bg-yellow-600':
                                                complaint.status === 'reviewed',
                                            'bg-red-600':
                                                complaint.status === 'pending',
                                            'bg-gray-600':
                                                complaint.status === 'closed',
                                        }"
                                    >
                                        {{ complaint.status }}
                                    </div>
                                </dd>
                            </div>
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Full name
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.resident.firstname }}
                                    {{ complaint.resident.middlename }}
                                    {{ complaint.resident.lastname }}
                                </dd>
                            </div>
                            <div
                                v-if="complaint.schedule_id"
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Schedule
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.schedule.schedule }}
                                </dd>
                            </div>
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Report Type
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{
                                        formatReportType(complaint.report_type)
                                    }}
                                </dd>
                            </div>
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Description
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.description }}
                                </dd>
                            </div>
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Photo
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    <div
                                        v-if="complaint.photo_url"
                                        class="grid gap-4"
                                    >
                                        <div v-if="selectedImage">
                                            <img
                                                class="h-auto w-full max-w-full rounded-lg object-cover object-center md:h-[480px]"
                                                :src="
                                                    '/storage/' + selectedImage
                                                "
                                                alt=""
                                            />
                                        </div>
                                        <div v-else>
                                            Click an Image to Enlarge.
                                        </div>
                                        <div class="grid grid-cols-5 gap-4">
                                            <div
                                                @click="selectImage(image)"
                                                v-for="(
                                                    image, index
                                                ) in JSON.parse(
                                                    complaint.photo_url
                                                )"
                                                :key="index"
                                            >
                                                <img
                                                    :src="'/storage/' + image"
                                                    class="object-cover object-center h-20 max-w-full rounded-lg cursor-pointer"
                                                    alt="gallery-image"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <span v-else>No Photos uploaded</span>
                                </dd>
                            </div>
                            <div
                                v-if="
                                    complaint.report_type === 'illegal_dumping'
                                "
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Location
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.location }}
                                </dd>
                            </div>
                            <div
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Barangay
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.barangay }}
                                </dd>
                            </div>
                            <div
                                v-if="complaint.resolved_at"
                                class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                            >
                                <dt class="text-sm font-medium text-gray-500">
                                    Resolved at
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                                >
                                    {{ complaint.resolved_at }}
                                </dd>
                            </div>
                        </dl>
                        <div class="flex gap-2 justify-end">
                            <Link
                                :href="route('complaints.list')"
                                class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150"
                                >Back</Link
                            >
                            <form
                                v-if="
                                    complaint.status !== 'resolved' &&
                                    complaint.status !== 'pending' &&
                                    complaint.status !== 'closed'
                                "
                                @submit.prevent="closed_submit"
                            >
                                <SecondaryButton
                                    type="submit"
                                    v-if="complaint.resolved_at === null"
                                    class="!bg-red-600 text-white"
                                    onclick="validationModal.showModal()"
                                    @click="closed_submit"
                                    >Mark as Closed</SecondaryButton
                                >
                            </form>
                            <form
                                v-if="complaint.status !== 'resolved'"
                                @submit.prevent="reviewed_submit"
                            >
                                <SecondaryButton
                                    type="submit"
                                    v-if="
                                        complaint.resolved_at === null &&
                                        complaint.status === 'pending'
                                    "
                                    class="!bg-green-600 text-white"
                                    onclick="validationModal.showModal()"
                                    @click="reviewed_submit"
                                    >Mark as Reviewed</SecondaryButton
                                >
                            </form>
                            <form
                                v-if="complaint.status !== 'resolved'"
                                @submit.prevent="resolved_submit"
                            >
                                <SecondaryButton
                                    type="submit"
                                    v-if="complaint.status === 'reviewed'"
                                    class="!bg-green-600 text-white"
                                    @click="resolved_submit"
                                    onclick="validationModal.showModal()"
                                    >Mark as Resolved</SecondaryButton
                                >
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Modal id="validationModal" title="Confirm Review Action">
            <template #body>
                <p
                    v-if="actionType === 'reviewed'"
                    class="text-sm text-gray-600"
                >
                    Are you sure you want to mark this complaint as reviewed?
                    This action cannot be undone.
                </p>
                <p
                    v-else-if="actionType === 'resolved'"
                    class="text-sm text-gray-600"
                >
                    Are you sure you want to mark this complaint as resolved?
                    This action cannot be undone.
                </p>
                <p
                    v-else-if="actionType === 'closed'"
                    class="text-sm text-gray-600"
                >
                    Are you sure you want to mark this complaint as closed? This
                    action cannot be undone.
                </p>
            </template>
            <template #actions>
                <button
                    @click="review"
                    class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded hover:bg-blue-700"
                    v-if="actionType === 'reviewed'"
                >
                    Mark as Reviewed
                </button>
                <button
                    @click="resolved"
                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded hover:bg-blue-700"
                    v-else-if="actionType === 'resolved'"
                >
                    Mark as Resolved
                </button>
                <button
                    @click="closed"
                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded hover:bg-blue-700"
                    v-else-if="actionType === 'closed'"
                >
                    Mark as Closed
                </button>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
