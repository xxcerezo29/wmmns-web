<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Schedule } from '../../../../types/interface';


const props = defineProps<{
    day: Array<Schedule>;
}>();

const formatTime = (time: string): string => {
    const [hour, minute] = time.split(':');
    let hours = parseInt(hour);
    const period = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12 || 12; // Convert 0 to 12 for 12 AM and PM

    return `${hours}:${minute} ${period}`;
};

</script>
<template>

    <div class="grid grid-rows gap-px">
        <div v-for="(mon, index) in props.day" class="relative bg-gray-50 px-3 py-2 text-gray-500">
            <time datetime="2021-12-27"></time>
            <ol class="mt-2">
                <li>
                    <div class="group flex">
                        <p class="flex-auto truncate font-medium text-gray-900">
                            Barangay- {{ mon.barangay }}</p>

                    </div>
                    <div class="group flex">
                        <p class="flex-auto truncate font-medium text-gray-900">
                            Truck- {{ mon.truck.plate_number }}</p>

                    </div>
                    <Link :href="route('schedule.show', {id:mon.id})" class="group flex">

                        <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">
                            {{ mon.route.name }}
                        </p>
                        <time datetime="2022-01-08T18:00"
                            class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">{{
            formatTime(mon.time) }} </time>
                            </Link>
                </li>
            </ol>
        </div>
    </div>


</template>