<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Day from './Day.vue';
import Header from './Header.vue';
import DateComponent from './DateComponent.vue';



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
        calendarDays.value?.push({ day: daysInPreviousMonth - i});
      }
    for (let day = 1; day <= daysInMonth; day++) {
        calendarDays.value?.push({ day: day });
    }

    month.value = today.toLocaleString('default', { month: 'long'});
    year.value = today.getFullYear();

    const totalSlots = 42;
    const remainingSlots = totalSlots - calendarDays.value.length;
    for (let day = 1; day <= remainingSlots; day++) {
        calendarDays.value?.push({ day: day });
      }
}

onMounted(()=> {
    generateCalendar();
})

</script>

<template>
    <div class="lg:flex lg:h-full lg:flex-col">
        <Header  :month="month" :year="year"/>
        <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
            <Day/>
            <DateComponent :calendar_day="calendarDays" />
        </div>

    </div>
</template>./DateComponent.vue