<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { User } from '@/types';
import { OffDay } from '@/types/calendar';
import { Head, router } from '@inertiajs/vue3';
import dayjs, { Dayjs } from 'dayjs'
import advancedFormat from 'dayjs/plugin/advancedFormat'
import isToday from 'dayjs/plugin/isToday'
import { computed, reactive, ref } from 'vue'
dayjs.extend(isToday)
dayjs.extend(advancedFormat)

const props = defineProps<{
    user: User;
}>();

const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']

const currentMonth = ref(dayjs())
const form = reactive<{
    reason: string;
    newOffDays: string[]
}>({
    reason: '',
    newOffDays: []
})
const isShowModal = ref(false)

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

function isDayOff(date: Dayjs) {
    return props.user.offdays?.some((day) => day.date === date.format('YYYY-MM-DD')) || false
}

function isNewDayOff(date: Dayjs) {
    return form.newOffDays.some((day) => day === date.format('YYYY-MM-DD')) || false
}

function selectDay(date: Dayjs) {
    if (form.newOffDays.some((day) => day === date.format('YYYY-MM-DD'))) {
        form.newOffDays = form.newOffDays.filter((day) => day != date.format('YYYY-MM-DD'))
    } else {
        form.newOffDays.push(date.format('YYYY-MM-DD'))
    }
    console.log('form.newOffDays', form.newOffDays)
}

function openConfirmation() {
    form.reason = ''
    isShowModal.value = true
}

async function submit() {
    console.log('form.newOffDays', form.newOffDays)
    console.log('reason', form.reason)
    router.post('/calendar', form, {
        onSuccess: () => {
            isShowModal.value = false
            form.newOffDays = []
        },
        onError: (errors) => {
            console.log('errors', errors)
        },
        preserveScroll: true,
    })
}
</script>

<template>

    <Head title="My Days Off Calendar" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                My Days Off Calendar
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
                        class="h-12 flex items-center justify-center rounded hover:bg-blue-100 cursor-pointer" :class="{
                            'text-gray-400': !day.isCurrentMonth,
                            'font-bold text-blue-700': day.isToday,
                            'bg-blue-100': isDayOff(day.date),
                            'bg-green-200': isNewDayOff(day.date),
                        }" @click="selectDay(day.date)">
                        {{ day.date.date() }}
                    </div>
                </div>
            </div>

            <button :v-if="form.newOffDays.length > 0" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600" @click="openConfirmation">
                Submit Selected Dates
            </button>
        </div>

        <Modal :show="isShowModal" @close="isShowModal = false" :max-width="'md'">
            <div class="bg-white rounded-lg p-6 w-full shadow-lg">
                <h2 class="text-lg font-semibold mb-4">Confirm Action</h2>
                <p class="mb-2">You are about to submit the following days off:</p>
                <ol class="list-disc list-inside mb-4 ms-2">
                    <li v-for="day in form.newOffDays" :key="day">
                        {{ dayjs(day).format('dddd, MMMM Do YYYY') }}
                    </li>
                </ol>
                <p class="mb-2">Please provide a reason before proceeding:</p>
                <textarea v-model="form.reason" class="w-full border rounded p-2" placeholder="Enter your reason..." maxlength="64"></textarea>

                <div class="flex justify-end gap-2 mt-4">
                    <button class="px-4 py-2 text-gray-600" @click="isShowModal = false">Cancel</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700" @click="submit"
                        :disabled="!form.reason.trim()">
                        Confirm
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
