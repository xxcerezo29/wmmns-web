<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Bars3Icon, BuildingStorefrontIcon, CalendarDaysIcon, HomeIcon, InboxStackIcon, MapIcon, MapPinIcon, ReceiptRefundIcon, RectangleStackIcon, TruckIcon, UserGroupIcon } from '@heroicons/vue/24/outline';
import MenuItem from '@/Components/ui/MenuItem.vue';
import MenuItemDropdown from '@/Components/ui/MenuItemDropdown.vue';
import { hasRole } from '@/functions';

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
    isSidebarOpen.value = false;
};


</script>

<template>
    <div
        class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 md:px-8 lg:hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center py-4">
            <button @click="toggleSidebar" type="button"
                class="p-2 inline-flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-transparent dark:border-neutral-700 dark:text-white dark:hover:bg-white/10"
                data-hs-overlay="#sidebar-mini" aria-controls="sidebar-mini" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="flex-shrink-0 size-4" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <ol class="ms-3 flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                <li class="text-sm font-semibold text-gray-800 truncate dark:text-gray-400" aria-current="page">
                    <slot name="mobileMenuName"></slot>
                </li>
            </ol>
        </div>
    </div>

    <div v-if="isSidebarOpen" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-50" @click="closeSidebar"></div>

    <div :class="{
                'translate-x-0': isSidebarOpen,
                '-translate-x-full': !isSidebarOpen,
            }"
        class="hs-overlay [--auto-close:sm] hs-overlay-open:translate-x-0 transition-all duration-300 transform w-[260px] fixed inset-y-0 start-0 z-[60] bg-white border-e border-gray-200 lg:translate-x-0 lg:end-auto lg:bottom-0 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="px-6">
            <Link :href="route('dashboard')" aria-label="Brand"
                class="flex-none text-xl font-semibold dark:text-white dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                <div>
                    <div class="w-40">
                        WMMNS
                    </div>
                </div>
            </Link>
        </div>
        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1.5">
                <MenuItem pattern="dashboard" routeName="dashboard" label="Dashboard">
                    <template #menuIcon>
                        <HomeIcon class="h-6" />
                    </template>
                </MenuItem>
                <MenuItem pattern="trucks" routeName="trucks.list" label="Trucks">
                    <template #menuIcon>
                        <TruckIcon class="h-6" />
                    </template>
                </MenuItem>
                <MenuItemDropdown label="Users" pattern="users">
                    <template #menuIcon>
                        <UserGroupIcon class="h-6" />
                    </template>
                    <MenuItem v-if="hasRole('admin')" pattern="users.all" routeName="users.all.list" label="All" />
                    <MenuItem pattern="users.drivers" routeName="users.drivers.list" label="Drivers" />
                    <MenuItem v-if="hasRole('admin')" pattern="users.residents" routeName="users.residents.list" label="Residents" />
                    <MenuItem v-if="hasRole('admin')" pattern="users.roles" routeName="users.roles.list" label="Roles" />
                    <MenuItem v-if="hasRole('admin')" pattern="users.permissions" routeName="users.permissions.list" label="Permissions" />
                </MenuItemDropdown>
                <MenuItem pattern="routes" routeName="routes.list" label="Routes">
                    <template #menuIcon>
                        <MapIcon class="h-6" />
                    </template>
                </MenuItem>
                
                <MenuItem pattern="schedule" routeName="schedule.calendar" label="Schedules">
                    <template #menuIcon>
                        <CalendarDaysIcon class="h-6" />
                    </template>
                </MenuItem>
                <MenuItem pattern="complaints" routeName="complaints.list" label="Complaints">
                    <template #menuIcon>
                        <RectangleStackIcon class="h-6" />
                    </template>
                </MenuItem>
                <MenuItem pattern="roam-map" routeName="roam-map.view" label="Map">
                    <template #menuIcon>
                        <MapIcon class="h-6"/>
                    </template>
                </MenuItem>
                <MenuItem pattern="spatial-map" routeName="spatial-map.view" label="Spatial Map">
                    <template #menuIcon>
                        <MapPinIcon class="h-6" />
                    </template>
                </MenuItem>
                <MenuItem v-if="hasRole('Admin')" pattern="backup" routeName="backup.list" label="Backup">
                    <template #menuIcon>
                        <InboxStackIcon class="h-6" />
                    </template>
                </MenuItem>
            </ul>
        </nav>
    </div>

</template>