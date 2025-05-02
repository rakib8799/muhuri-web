<template>
    <div>
        <div class="d-flex justify-content-between">
            <h2 class="mt-5">{{ formattedMonthYear }}</h2>
            <div>
                <button class="btn btn-white" @click="goToPreviousMonth">
                    <i class="fa fa-arrow-left"></i>
                </button>
                <span>This Month</span>
                <button class="btn btn-white" @click="goToNextMonth">
                    <i class="fa fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <apexchart type="bar" :options="chartOptions" :series="series" height="350"></apexchart>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch, computed } from 'vue';
import VueApexCharts from 'vue3-apexcharts';
import type { ApexOptions } from 'apexcharts';
import { getMonthNames } from '@/Core/helpers/Helper';
import axios from 'axios';

interface SeriesData {
    x: string;
    y: number;
}

interface Series {
    name: string;
    data: SeriesData[];
}

interface LeaveData {
    [key: string]: number;
}

const apexchart = VueApexCharts;

const months = getMonthNames();

const selectedYear = ref(new Date().getFullYear());
const selectedMonth = ref(new Date().getMonth());

const formattedMonthYear = computed(() => {
    return `${months[selectedMonth.value]} ${selectedYear.value}`;
});

const series = ref<Series[]>([]);
const chartOptions = ref<ApexOptions>({
    chart: {
        type: 'bar',
        height: 350,
    },
    plotOptions: {
        bar: {
            horizontal: false
        },
    },
    xaxis: {
        type: 'category'
    },
    yaxis: {
        min: 0,
        labels: {
            formatter: (value) => {
                return value % 1 === 0 ? value.toString() : '';
            }
        }
    },
    dataLabels: {
        enabled: false
    },
    colors: [],
    legend: {
        show: true,
        position: 'top',
    },
});

const getChartData = async (year: number, month: number) => {
    try {
        const response = await axios.get('/employee-chart', {
            params: {
                year,
                month: month + 1,
            }
        });
        return response.data;
    } catch (error) {
        console.error('Error fetching days for month:', error);
        return [];
    }
}

const updateChartData = async () => {
    const {days, weekends, attendances, calendarLeaves} = await getChartData(selectedYear.value, selectedMonth.value);
    const {weekendName, formattedWeekendData, weekendColor} = weekends;
    const {attendanceName, formattedAttendanceData, attendanceColor} = attendances;
    const {formattedLeaveData, leaveColors} = calendarLeaves;

    const leaveSeriesData: Series[] = [];

    Object.entries(formattedLeaveData).forEach(([leaveType, data]) => {
        leaveSeriesData.push({
            name: leaveType,
            data: days.map((day: any) => ({
                x: day,
                y: (data as LeaveData)[day] || 0,
            })) as SeriesData[]
        });
    });
    
    series.value = [
        {
            name: weekendName,
            data: days.map((day: any) => ({
                x: day,
                y: formattedWeekendData[day] || 0
            })) as SeriesData[]
        },
        {
            name: attendanceName,
            data: days.map((day: any) => ({
                x: day,
                y: formattedAttendanceData[day] || 0
            })) as SeriesData[]
        },
        ...leaveSeriesData
    ];

    chartOptions.value = {
        colors: [
            weekendColor,
            attendanceColor,
            ...Object.values(leaveColors)
        ]
    };
}

watch([selectedYear, selectedMonth], updateChartData, { immediate: true });

function goToPreviousMonth() {
    if (selectedMonth.value === 0) {
        selectedMonth.value = 11;
        selectedYear.value -= 1;
    } else {
        selectedMonth.value -= 1;
    }
}

function goToNextMonth() {
    if (selectedMonth.value === 11) {
        selectedMonth.value = 0;
        selectedYear.value += 1;
    } else {
        selectedMonth.value += 1;
    }
}
</script>