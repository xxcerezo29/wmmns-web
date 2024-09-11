<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
    paginate: Object,
});

const goToPage = (link) => {
    router.visit(link.url,
    {
        method:'get',
        preserveState: true
    });
};
</script>
<template>
    <div class="w-full flex justify-end mt-5">
        <div class="join">
            <button
                @click="goToPage(paginate.links[index])"
                :class="{ 'btn-active': page.active }"
                class="join-item btn"
                v-for="(page, index) in paginate.links"
                :key="index"
                :disabled="
                    (index == paginate.links.length - 1 &&
                        paginate.next_page_url == null) ||
                    (index == 0 && paginate.prev_page_url == null)
                "
            >
                <span v-if="index == 0">Prev</span>
                <span v-else-if="index == paginate.links.length - 1">Next</span>
                <span v-else>{{ page.label }}</span>
            </button>
        </div>
    </div>
</template>
