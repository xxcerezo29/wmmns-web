<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { onMounted, ref } from 'vue';
import { useToast } from 'vue-toastification';
import { ICities, Roles } from '../types/interface';
import axios from 'axios';

const props = defineProps<{
  roles: Array<Roles>
}>();

const form = useForm({
  firstname: '',
  middlename: '',
  lastname: '',
  email: '',
  barangay: '',
  password: '',
  password_confirmation: '',
  roles: [] as string[],
})

const toast = useToast();

const barangay = ref<Array<ICities>>([]);

const submit = () => {
  form.post(route('users.all.store'),{
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
    <Head title="New User" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">New User</h2>
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
                              Password
                              <input v-model="form.password"  type="password" class="grow" placeholder="Password" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.password">{{form.errors.password}}</span>
                            <label class="input input-bordered flex items-center gap-2 mt-2">
                              Password Confirmation
                              <input v-model="form.password_confirmation"  type="password" class="grow" placeholder="Password confirmation" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.password_confirmation">{{form.errors.password_confirmation}}</span>

                            <label class="form-control w-full max-w-xs mt-2">
                              <div class="label">
                                <span class="label-text">Barangay</span>
                              </div>
                              <select v-model="form.barangay" class="select select-bordered">
                                <option disabled selected value="">Please Choose Barangay</option>
                                <option v-for="(_barangay, index) in barangay" :value="_barangay.name">{{_barangay.name}}</option>
                              </select>
                            </label>

                            <div class="mt-5">
                              <span>Roles</span>
                          </div>

                          <div class="flex gap-2 ">
                              <div v-for="(role, index) in props.roles" :key="role.id" class="form-control">
                                  <label class="label cursor-pointer">
                                      <input type="checkbox"  :value="role.name" v-model="form.roles" class="checkbox" />
                                      <span class="label-text ml-2">{{role.name}}</span>
                                  </label>
                              </div>
                          </div>

                            <div class="flex justify-end mt-5 gap-2">
                                <Link :href="route('users.all.list')" class="inline-flex items-center btn px-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none disabled:opacity-25 transition ease-in-out duration-150">Cancel</Link>
                                <PrimaryButton type="submit">Submit</PrimaryButton>
                            </div>

                          </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>