<script setup lang="ts">
import { ref } from 'vue';


const props = defineProps<{
    calendar_day: Array<
    {
        day: number;
    }>
}>()

</script>
<template>
    <div class="flex bg-gray-200 text-xs leading-6 text-gray-700 lg:flex-auto">
        <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
            <div v-for="(day, index) in props.calendar_day" class="relative bg-gray-50 px-3 py-2 text-gray-500">
                <!--
                  Is today, include: "flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white"
                -->
                <time datetime="2021-12-27">{{day.day}}</time>
                <ol class="mt-2">
                    <li>
                      <a href="#" class="group flex">
                        <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">Date night</p>
                        <time datetime="2022-01-08T18:00" class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">6PM</time>
                      </a>
                    </li>
                  </ol>
              </div>
        </div>
    </div>
</template>