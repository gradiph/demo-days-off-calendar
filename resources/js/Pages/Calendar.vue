<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import dayjs from 'dayjs'
import isToday from 'dayjs/plugin/isToday'
import { computed, onMounted, ref } from 'vue'
dayjs.extend(isToday)

const props = defineProps<{
    user: object;
}>();

onMounted(() => {
    console.log('user', props.user);
})

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

let currentMonth = ref(dayjs())

const startOfMonth = computed(() => currentMonth.value.startOf('month'))
const endOfMonth = computed(() => currentMonth.value.endOf('month'))

const calendarDays = computed(() => {
    const startDay = startOfMonth.value.startOf('week')
    const endDay = endOfMonth.value.endOf('week')

    const days = []
    let date = startDay

    while (date.isBefore(endDay) || date.isSame(endDay)) {
        days.push({
            date,
            isToday: date.isToday(),
            isCurrentMonth: date.month() === currentMonth.value.month(),
        })
        date = date.add(1, 'day')
    }

    return days
})

function prevMonth() {
    currentMonth.value = currentMonth.value.subtract(1, 'month')
}

function nextMonth() {
    currentMonth.value = currentMonth.value.add(1, 'month')
}
</script>

<template>

    <Head title="Calendar" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Calendar
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <button @click="prevMonth" class="text-sm text-blue-600">&lt; Prev</button>
                    <h2 class="text-lg font-semibold">
                        {{ currentMonth.format('MMMM YYYY') }}
                    </h2>
                    <button @click="nextMonth" class="text-sm text-blue-600">Next &gt;</button>
                </div>

                <div class="grid grid-cols-7 gap-2 text-center font-medium text-gray-600">
                    <div v-for="(day, i) in weekDays" :key="i">{{ day }}</div>
                </div>

                <div class="grid grid-cols-7 gap-2 mt-2">
                    <div v-for="(day, index) in calendarDays" :key="index"
                        class="h-12 flex items-center justify-center rounded hover:bg-blue-100 cursor-pointer"
                        :class="{ 'text-gray-400': !day.isCurrentMonth, 'font-bold text-blue-700': day.isToday }">
                        {{ day.date.date() }}
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Optional: for smoother feel */
</style>