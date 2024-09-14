<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';
import { ICities, Roles, Truck } from '../../types/interface';
import axios from 'axios';
import { hasRole } from '@/functions';

const props = defineProps<{
  trucks: Array<Truck>
}>();

const form = useForm({
  firstname: '',
  middlename: '',
  lastname: '',
  email: '',
  mobile_number: '',
  barangay: '',
  truck_id: '',
})

const toast = useToast();

const barangay = ref<Array<ICities>>([]);

const submit = () => {
  form.post(route('users.drivers.store'),{
    onError: () => {
      Object.values(form.errors).forEach((error) => {
        toast.error(error);
      });
    }
  });
}

onMounted(()=> {
    if(usePage().props.flash.message){
        if(usePage().props.flash.status === "success")
            toast.success(usePage().props.flash.message)
        else if(usePage().props.flash.status === "error")
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
    <Head title="New Driver" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Driver</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <label class="input input-bordered flex items-center gap-2">
                              Firstname
                              <input v-model="form.firstname" type="text" class="grow" placeholder="Daisy" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.firstname">{{form.errors.firstname}}</span>
                            <label class="input input-bordered flex items-center gap-2 mt-2">
                              Middlename
                              <input v-model="form.middlename" type="text" class="grow" placeholder="Daisy" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.middlename">{{form.errors.middlename}}</span>
                            <label class="input input-bordered flex items-center gap-2 mt-2">
                              Lastname
                              <input v-model="form.lastname" type="text" class="grow" placeholder="Daisy" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.lastname">{{form.errors.lastname}}</span>
                            <label class="input input-bordered flex items-center gap-2 mt-2">
                              Email
                              <input v-model="form.email"  type="email" class="grow" placeholder="example@example.com" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.email">{{form.errors.email}}</span>

                            <label class="input input-bordered flex items-center gap-2 mt-2">
                              Mobile Number
                              <input v-model="form.mobile_number"  type="text" class="grow" placeholder="9876543321" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.mobile_number">{{form.errors.mobile_number}}</span>

                            <label v-if="hasRole('admin')" class="form-control w-full max-w-xs mt-2">
                              <div class="label">
                                <span class="label-text">Barangay</span>
                              </div>
                              <select v-model="form.barangay" class="select select-bordered">
                                <option disabled selected value="">Please Choose Barangay</option>
                                <option v-for="(_barangay, index) in barangay" :value="_barangay.name">{{_barangay.name}}</option>
                              </select>
                            </label>

                            <label class="form-control w-full max-w-xs mt-2">
                              <div class="label">
                                <span class="label-text">Truck Assignment</span>
                              </div>
                              <select v-model="form.truck_id" class="select select-bordered">
                                <option disabled selected value="">Please Choose Truck</option>
                                <option v-for="(truck, index) in props.trucks" :value="truck.id">{{truck.plate_number}}</option>
                              </select>
                            </label>
                            <span class="text-red-700" v-if="form.errors.truck_id">{{form.errors.truck_id}}</span>

                            <div class="flex justify-end mt-5 gap-2">
                                <Link :href="route('users.drivers.list')" class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150">Cancel</Link>
                                <PrimaryButton>Submit</PrimaryButton>
                            </div>

                          </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>