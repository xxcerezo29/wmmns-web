<script setup lang="ts">
import SidebarLayout from './SidebarLayout.vue';
import HeaderLayout from './HeaderLayout.vue';
</script>
<style>
body {
    background-color: #f5f4f8;
}

.dark-mode body {
    background-color: #1a1a1a;
}
</style>
<template>
    <HeaderLayout />
    <SidebarLayout>
        <template #mobileMenuName>
            <slot name="mobileMenuName" />
        </template>
    </SidebarLayout>
    <div class="w-full xs:px-2 py-10 sm:px-5 md:px-10 lg:pl-72 ">
        <main class="relative main ">
            <slot />
        </main>
    </div>
</template>
