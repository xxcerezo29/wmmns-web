<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';
import { ICities, Roles, Route, Truck } from '../../types/interface';
import axios from 'axios';

const props = defineProps<{
  routes: Array<Route>;
  trucks: Array<Truck>
}>();

const form = useForm({
  truck_id: '',
  day: '',
  route: '',
  time: '',
  barangay: usePage().props.auth.user.barangay
})

const toast = useToast();

const barangay = ref<Array<ICities>>([]);

const submit = () => {
  form.post(route('schedule.store'), {
    onSuccess: () => {
      // toast.success(usePage().props.errors);
    },
    onError: () => {
      Object.values(form.errors).forEach((error) => {
        toast.error(error);
      });
    }
  });
}

onMounted(() => {
  if (usePage().props.flash.message) {
    if (usePage().props.flash.status === "success")
      toast.success(usePage().props.flash.message)
    else if (usePage().props.flash.status === "error")
      toast.error(usePage().props.flash.message)
  }

  axios.get('https://psgc.gitlab.io/api/cities/023135000/barangays')
    .then((response: any) => {
      barangay.value = response.data
    }).catch((err: any) => {
      alert(err);
    })
})

</script>
<template>

  <Head title="New Schedule" />
  <AuthenticatedLayout>
    <template #mobileMenuName>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Schedule</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submit">

              <label class="form-control w-full max-w-xs mt-2">
                <div class="label">
                  <span class="label-text">Truck</span>
                </div>
                <select v-model="form.truck_id" class="select select-bordered">
                  <option disabled selected value="">Please Choose Truck</option>
                  <option v-for="(truck, index) in props.trucks" :value="truck.id">{{ truck.plate_number }}</option>
                </select>
              </label>
              <span class="text-red-700" v-if="form.errors.truck_id">{{ form.errors.truck_id }}</span>

              <label class="form-control w-full max-w-xs mt-2">
                <div class="label">
                  <span class="label-text">Day</span>
                </div>
                <select v-model="form.day" class="select select-bordered">
                  <option disabled selected value="">Please Choose Day</option>
                  <option selected value="monday">Monday</option>
                  <option selected value="tuesday">Tuesday</option>
                  <option selected value="wednesday">Wednesday</option>
                  <option selected value="thursday">Thursday</option>
                  <option selected value="friday">Friday</option>
                  <option selected value="saturday">Saturday</option>
                  <option selected value="sunday">Sunday</option>
                </select>
              </label>
              <span class="text-red-700" v-if="form.errors.day">{{ form.errors.day }}</span>

              <label class="form-control w-full max-w-xs mt-2">
                <div class="label">
                  <span class="label-text">Time</span>
                </div>
                <input type="time" v-model="form.time" class="input input-bordered" />
              </label>
              <span class="text-red-700" v-if="form.errors.time">{{ form.errors.time }}</span>

              <label class="form-control w-full max-w-xs mt-2">
                <div class="label">
                  <span class="label-text">Route</span>
                </div>
                <select v-model="form.route" class="select select-bordered">
                  <option disabled selected value="">Please Choose Route</option>
                  <option v-for="(_route, index) in props.routes" :value="_route.id">{{ _route.name }}</option>
                </select>
              </label>
              <span class="text-red-700" v-if="form.errors.route">{{ form.errors.route }}</span>

              <div class="flex justify-end mt-5 gap-2">
                <Link :href="route('schedule.calendar')"
                  class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150">
                Cancel</Link>
                <PrimaryButton>Submit</PrimaryButton>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>