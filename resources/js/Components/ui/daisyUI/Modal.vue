<script setup lang="ts">

withDefaults(
    defineProps<{
        title?: string
    }>(),
    {
        title: "Title",
    }
);
</script>
<template>
    <dialog  class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold">{{ title }}</h3>
            <slot name="body" />
            <div class="modal-action">
                <form method="dialog">
                    <!-- if there is a button in form, it will close the modal -->
                    <button class="btn">Close</button>
                </form>
                <slot name="actions" />
            </div>
        </div>
    </dialog>
</template>