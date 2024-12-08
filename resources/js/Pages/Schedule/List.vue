<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import { Schedule } from "../../types/interface";
import { useToast } from "vue-toastification";
import { onMounted, ref } from "vue";
import Modal from "@/Components/ui/daisyUI/Modal.vue";
import Calendar from "./Components/Calendar/Calendar.vue";
import Pagination from "@/Components/ui/daisyUI/Pagination.vue";

import VueCal from "vue-cal";
import "vue-cal/dist/vuecal.css";
import { hasRole } from "@/functions";

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
        current_page: number;
        last_page: number;
        next_page_url: string;
        prev_page_url: string;
    };
    show: string;
}>();

const toast = useToast();

const targetToDelete = ref();
const showAll = ref();

const update = () => {
    router.get(
        route("schedule.calendar"),
        {
            show: showAll.value,
        },
        {
            preserveScroll: true,
            preserveState: true,
        }
    );
};

const handleDelete = () => {
    const deleteForm = useForm({});
    deleteForm.delete(route("schedule.delete", { id: targetToDelete.value }), {
        onSuccess: () => {
            toast.success("Schedule Deleted.");
            detailsModal.value?.close();
        },
        onError: () => {
            toast.success("Schedule can't be Deleted.");
        },
    });
};

const selectedEvent = ref<{
    id: number;
    start: any;
    end: any;
    title: string;
    icon: string;
    content: string;
    contentFull: string;
    class: string;
}>();
const detailsModal = ref<HTMLDialogElement | null>();
const handleEventClick = (event: any, e: any) => {
    selectedEvent.value = event;
    detailsModal.value?.showModal();
};

const handleNotify = () => {
    const _form = useForm({});
    _form.get(route("schedule.notify"));
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
    <Head title="Schedules" />

    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Schedules
            </h2>
        </template>

        <header
            class="flex items-center justify-between border-b border-gray-200 px-6 py-4 lg:flex-none"
        >
            <div class="flex items-center">
                <div class="md:ml-4 md:flex md:items-center gap-2">
                    <div v-if="hasRole('admin')">
                        <div class="form-control">
                            <label class="label cursor-pointer">
                                <span class="label-text mr-2">Show all</span>
                                <input
                                    v-model="showAll"
                                    type="checkbox"
                                    class="checkbox"
                                    @change="update"
                                />
                            </label>
                        </div>
                    </div>
                    <a
                        :href="route('schedule.pdf')"
                        target="_blank"
                        class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-slate-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <ArrowDownCircleIcon class="h-7" />
                        Download PDF
                    </a>
                    <button
                        @click="handleNotify"
                        class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-slate-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Send Notification
                    </button>
                    <Link
                        :href="
                            route('schedule.create', {
                                cenro: hasRole('admin') ? 'true' : 'false',
                            })
                        "
                        class="disabled:text-gray-500 inline-flex hover:border-transparent items-center px-4 py-3 btn bg-slate-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-kwikweb dark:hover:bg-white focus:bg-kwikweb-200 dark:focus:bg-white active:bg-kwikweb-900 dark:active:bg-gray-300 focus:outline-none focus:ring-offset-2 transition ease-in-out duration-150"
                        ><PlusCircleIcon class="h-7" />Add Schedule</Link
                    >
                </div>
            </div>
        </header>
        <vue-cal
            class="md:col-span-10 h-full"
            :events="schedule"
            events-on-month-view="short"
            :snap-to-time="15"
            :disable-views="['years']"
            :on-event-click="handleEventClick"
        />

        <div class="py-12">
            <!-- <Calendar :pagination="pagination" :schedule="props.schedule" />
            <Pagination :paginate="pagination" /> -->
        </div>
        <Modal id="deleteModal" title="Schedule Delete Form">
            <template #body>
                <div class="mt-5">
                    <span class="text-red-700"
                        >You are about to delete this schedule?</span
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
        <dialog ref="detailsModal" class="modal">
            <div class="modal-box">
                <h3 class="text-lg font-bold">
                    {{ selectedEvent?.title }}
                    {{ selectedEvent?.start.format("DD/MM/YYYY") }}
                </h3>
                <div class="mt-5">
                    <span v-html="selectedEvent?.contentFull" />
                </div>
                <div class="modal-action">
                    <Link
                        v-if="selectedEvent"
                        :href="
                            route('schedule.update', { id: selectedEvent?.id })
                        "
                        class="btn btn-success text-white"
                        >Edit</Link
                    >
                    <Link
                        v-if="selectedEvent"
                        :href="
                            route('schedule.show', { id: selectedEvent?.id })
                        "
                        class="btn btn-info text-white"
                        >View</Link
                    >
                    <button
                        class="btn btn-error text-white"
                        @click="targetToDelete = selectedEvent?.id"
                        onclick="deleteModal.showModal()"
                    >
                        Delete
                    </button>
                    <form method="dialog">
                        <!-- if there is a button in form, it will close the modal -->
                        <button class="btn">Close</button>
                    </form>
                </div>
            </div>
        </dialog>
    </AuthenticatedLayout>
</template>
