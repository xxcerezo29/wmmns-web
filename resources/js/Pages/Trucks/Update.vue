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

const props = defineProps<{
  truck: Truck
}>();

const form = useForm({
  plate_number: props.truck.plate_number,
  barangay: props.truck.barangay,
})

const toast = useToast();

const barangay = ref<Array<ICities>>([]);

const submit = () => {
  form.post(route('trucks.store'),{
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
    <Head title="Update Truck" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Truck</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <label class="input input-bordered flex items-center gap-2">
                              Plate Number
                              <input v-model="form.plate_number" type="text" class="grow" placeholder="Daisy" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.plate_number">{{form.errors.plate_number}}</span>
                      
                            <label class="form-control w-full max-w-xs mt-2">
                              <div class="label">
                                <span class="label-text">Barangay</span>
                              </div>
                              <select v-model="form.barangay" class="select select-bordered">
                                <option disabled selected value="">Please Choose Barangay</option>
                                <option v-for="(_barangay, index) in barangay" :value="_barangay.name">{{_barangay.name}}</option>
                              </select>
                            </label>
                            <span class="text-red-700" v-if="form.errors.barangay">{{form.errors.barangay}}</span>

                            <div class="flex justify-end mt-5 gap-2">
                                <Link :href="route('trucks.list')" class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150">Cancel</Link>
                                <PrimaryButton>Submit</PrimaryButton>
                            </div>

                          </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>