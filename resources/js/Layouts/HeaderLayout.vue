<script setup lang="ts">
import { BellIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';


const searchKeyword: Ref<string> = ref("");

</script>
<template>
    <header
        class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-gray-800 dark:border-gray-700">
        <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6 md:px-8" aria-label="Global">
            <div class="me-5 lg:me-0 lg:hidden">
                <a class="flex-none text-xl font-semibold dark:text-white" href="#" aria-label="Brand">
                    <img src="/wmmns-logo.png" alt="WMMNS" class="h-16 rounded-full" />
                </a>
            </div>
            <div class="w-full flex items-center justify-end ms-auto sm:justify-between sm:gap-x-3 sm:order-3">
                <div>
                    <label class="input input-bordered flex items-center gap-2">
                        <input placeholder="Search here..." class="border-0 p-0 input input-bordered w-full"
                            v-model="searchKeyword" ref="input" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </label>
                </div>

                <div class="flex flex-row items-center justify-end gap-2">
                    <div class="hs-dropdown relative inline-flex items-center gap-4 [--placement:bottom-right]">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0"
                                class="bg-gray-50 dark:bg-slate-900 p-2.5 rounded-full hover:bg-gray-100 dark:hover:bg-slate-950 cursor-pointer"
                                role="button">
                                <BellIcon class="h-7" />
                            </div>
                            <ul tabindex="0"
                                class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                                <li><a>Item 1</a></li>
                                <li><a>Item 2</a></li>
                            </ul>
                        </div>
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" class="m-1" role="button">
                                <div class="avatar">
                                    <div
                                        class="w-10 rounded-full ring-2 ring-green-300 dark:ring-green-500 ring-offset-base-100 ring-offset-1">
                                        <img
                                            src="https://st3.depositphotos.com/6672868/13801/v/450/depositphotos_138013506-stock-illustration-user-profile-group.jpg" />
                                    </div>
                                </div>
                            </div>
                            <ul tabindex="0"
                                class="dropdown-content z-[1] menu p-0 shadow bg-base-100 rounded-box w-52">
                                <div class="p-2 bg-gray-100 rounded-t">
                                    <h4 class="capitalize font-bold text-base">
                                        {{
                                            $page.props.auth.user.firstname.toLocaleLowerCase()
                                        }}

                                        {{
                                            $page.props.auth.user.lastname.toLocaleLowerCase()
                                        }}

                                    </h4>
                                    <!-- <p class="capitalize">{{ $page.props.auth.user.roles[0].name}}</p> -->
                                </div>
                                <li>
                                    <Link :href="route('profile.edit')"
                                        >Profile</Link
                                    >
                                </li>
                                <li>
                                    <Link
                                        href="/logout"
                                        as="button"
                                        method="post"
                                        >Sign out</Link
                                    >
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</template>