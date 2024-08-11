<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, ref, Ref } from 'vue';


const props = defineProps<{
    routeName: string;
    label: string;
    pattern: string;
}>();

const isActive: Ref<boolean> = ref(false);

onMounted(() => {
    isActive.value = route().current()!.toString().includes(props.pattern);
})

</script>

<template>
    <li>
        <Link aria-controls="sidebar" :href="route(routeName)"
            :class="{
                'bg-green-300 dark:bg-green-500 text-slate-700': isActive,
            }"
            class="flex items-center gap-x-3.5 py-2 px-2.5 bg-gray-100 text-sm text-gray-700 rounded-lg hover:bg-gray-100 dark:bg-neutral-700 dark:text-white">
        <slot name="menuIcon" />
        {{ label }}
        </Link>
    </li>
</template>