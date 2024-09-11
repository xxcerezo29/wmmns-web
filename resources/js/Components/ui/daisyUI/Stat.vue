<script setup lang="ts">

const props = defineProps<{
    stat: {
        label: string;
        value: string|number;
        description: string;
    }
}>();

</script>
<template>
    <div class="stat">
        <div class="stat-figure text-secondar">
            <slot name="icon" class="inline-block h-8 w-8 stroke-current">

            </slot>
        </div>
        
        <!-- <div class="stat-figure text-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                class="inline-block h-8 w-8 stroke-current">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div> -->
        <div class="stat-title">{{props.stat.label}}</div>
        <div class="stat-value">{{props.stat.value}}</div>
        <div class="stat-desc">{{props.stat.description}}</div>
    </div>
</template>