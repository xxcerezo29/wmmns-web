<script setup lang="ts">
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/outline'
import { usePage } from '@inertiajs/vue3'
import { onMounted, Ref, ref } from 'vue'

const props = defineProps<{
    label: string
    pattern: string
}>()

const isActive: Ref<boolean> = ref(false)

onMounted((): void => {
    isActive.value = usePage().props.currentRouteName.includes(props.pattern)
})
</script>
<template>
    <li
        class="hs-accordion"
        :class="{
            'active rounded-md pb-2 bg-gray-50 dark:bg-gray-700': isActive,
        }"
        :id="label.replaceAll(' ', '__')"
    >
        <button
            @click="isActive = !isActive"
            type="button"
            class="w-full text-start flex items-center gap-x-3.5 py-2 px-2.5 hs-accordion-active:text-green-600 hs-accordion-active:hover:bg-transparent text-sm hover:text-slate-700 rounded-lg hover:bg-purple-100 active:dark:bg-gray-700 dark:hover:bg-green-900 dark:text-slate-400 dark:hover:text-slate-300 dark:hs-accordion-active:text-white dark:focus:outline-none dark:focus:ring-0"
        >
            <slot name="menuIcon" />
            <span class="dark:text-gray-100">{{ label }}</span>
            <ChevronUpIcon
                class="h-4 hs-accordion-active:block ms-auto hidden"
            />
            <ChevronDownIcon
                class="h-4 hs-accordion-active:hidden ms-auto block"
            />
        </button>

        <div
            :id="label.replaceAll(' ', '__') + '-child'"
            class="hs-accordion-content w-full overflow-hidden duration-300 pl-5"
            :class="{ hidden: !isActive }"
            :style="!isActive ? 'height:0' : 'display:block'"
        >
            <ul class="pt-2 ps-2 pr-2">
                <slot />
            </ul>
        </div>
    </li>
</template>
