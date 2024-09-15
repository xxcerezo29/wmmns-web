<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Day from './Day.vue';
import Header from './Header.vue';
import DateComponent from './DateComponent.vue';
import { Schedule } from '@/types/interface';
import Sched from './Sched.vue';
import Pagination from '@/Components/ui/daisyUI/Pagination.vue';


const props = defineProps<{
    schedule: {
        monday: Array<Schedule>;
        tuesday: Array<Schedule>;
        wednesday: Array<Schedule>;
        thursday: Array<Schedule>;
        friday: Array<Schedule>;
        saturday: Array<Schedule>;
        sunday: Array<Schedule>;
    };
    pagination: {
        current_page : number;
        last_page: number;
        next_page_url: string;
        prev_page_url: string;
    }
}>();

const calendarDays = ref<Array<
    {
        day: number;
    }>>([]);

const month = ref();
const year = ref();
const Today = ref();

const generateCalendar = () => {
    const today = new Date();

    Today.value = today.getDate();

    const currentMonth = today.getMonth();
    const currentYear = today.getFullYear();

    const firstDay = new Date(currentYear, currentMonth, 1).getDay();

    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    const daysInPreviousMonth = new Date(currentYear, currentMonth, 0).getDate();

    for (let i = firstDay - 1; i >= 0; i--) {
        calendarDays.value?.push({ day: daysInPreviousMonth - i });
    }
    for (let day = 1; day <= daysInMonth; day++) {
        calendarDays.value?.push({ day: day });
    }

    month.value = today.toLocaleString('default', { month: 'long' });
    year.value = today.getFullYear();

    const totalSlots = 42;
    const remainingSlots = totalSlots - calendarDays.value.length;
    for (let day = 1; day <= remainingSlots; day++) {
        calendarDays.value?.push({ day: day });
    }
}

onMounted(() => {
    generateCalendar();
})



</script>

<template>
    <div class="lg:flex lg:h-full lg:flex-col">
        <Header :month="month" :year="year" />
        <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
            <Day />
            <div class="grid grid-cols-7 gap-px  text-center text-xs font-semibold leading-6 text-gray-700 lg:flex-none">
                <Sched :day="props.schedule.monday" />
                <Sched :day="props.schedule.tuesday" />
                <Sched :day="props.schedule.wednesday" />
                <Sched :day="props.schedule.thursday" />
                <Sched :day="props.schedule.friday" />
                <Sched :day="props.schedule.saturday" />
                <Sched :day="props.schedule.sunday" />
            </div>
            <!-- <DateComponent :calendar_day="calendarDays" /> -->
        </div>
        
    </div>
</template>