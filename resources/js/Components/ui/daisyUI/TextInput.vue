<template>
    <label class="form-control w-full">
        <div class="label">
            <span class="label-text">{{ props.label }}</span>
        </div>
        <input
            v-model="modelValue"
            :type="props.type"
            :placeholder="props.placeholder"
            class="input input-bordered w-full"
        />
        <div class="label">
            <span class="label-text-alt text-red-600" v-if="props.error">{{
                props.error
            }}</span>
        </div>
    </label>
</template>
<script setup lang="ts">
const modelValue = defineModel();
const props = defineProps<{
    label: string;
    type: string;
    placeholder: string;
    error?: string;
}>();
</script>
