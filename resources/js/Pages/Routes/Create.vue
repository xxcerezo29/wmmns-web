<script setup lang="ts">
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "../../Layouts/AuthenticatedLayout.vue";
import { onMounted, ref } from "vue";
import { ICities } from "../types/interface";
import axios from "axios";
import { hasRole } from "@/functions";
import PrimaryButton from "@/Components/ui/daisyUI/PrimaryButton.vue";
import Map from "./Component/Map.vue";

interface waypoint {
    lat: number;
    lng: number;
}

const form = useForm({
    name: '',
    barangay: usePage().props.auth.user.barangay,
    waypoint: [] as waypoint[]
})

const submit = () => {
    form.post(route('routes.store'));
}

const barangay = ref<Array<ICities>>([]);

onMounted(() => {
    axios.get('https://psgc.gitlab.io/api/cities/023135000/barangays')
      .then((response: any) => {
          barangay.value = response.data
      }).catch((err: any) => {
        alert(err);
      })
})

</script>
<template>

    <Head title="New Route" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New Route</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    
                        <form @submit.prevent="submit">
                            <label class="input input-bordered flex items-center gap-2">
                                Route Name
                                <input v-model="form.name"  type="text" class="grow" placeholder="Daisy" />
                              </label>
                              <span class="text-red-700" v-if="form.errors.name">{{form.errors.name}}</span>
    
                              <label v-if="hasRole('admin')" class="form-control w-full max-w-xs mt-2">
                                <div class="label">
                                  <span class="label-text">Barangay</span>
                                </div>
                                <select  v-model="form.barangay" class="select select-bordered">
                                  <option disabled selected value="">Please Choose Barangay</option>
                                  <option v-for="(_barangay, index) in barangay" :value="_barangay.name">{{_barangay.name}}</option>
                                </select>
                              </label>
                              <span class="text-red-700" v-if="form.errors.barangay">{{form.errors.barangay}}</span>
    
                              <Map :form="form" />
                            
    
                            <div class="flex justify-end mt-5 gap-2">
                                <Link :href="route('routes.list')" class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150">Cancel</Link>
                                <PrimaryButton>Submit</PrimaryButton>
                            </div>
                        </form>

                </div>
            </div>
        </div>

    </AuthenticatedLayout>

</template>