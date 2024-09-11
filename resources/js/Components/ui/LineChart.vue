<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { LineChart } from 'vue-chart-3';
import { Chart, registerables } from 'chart.js';
import { IChartData } from '@/Pages/types/interface';

Chart.register(...registerables);



const props = defineProps<{
    charData: IChartData
}>();



</script>
<template>
    <div class="w-full">
        <LineChart :chartData="props.charData" />
      </div>
</template>