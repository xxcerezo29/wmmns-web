<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { useToast } from 'vue-toastification';
import { Permissions, Roles } from '@/types/interface';

const props = defineProps<{
    permissions: Array<Permissions>;
    role: Roles
}>();

const form = useForm({
    name: props.role.name,
    permissions: [] as string[],
})

const toast = useToast();

const submit = () => {
    form.post(route('users.roles.update', {id: props.role.id}), {
        onError: () => {
      Object.values(form.errors).forEach((error) => {
        toast.error(error);
      });
    }
    });
}

onMounted(() => {
    if(props.role.permissions){
        props.role.permissions.forEach(element => {
            form.permissions.push(element.name)
        });
    }
    if (usePage().props.flash.message) {
        if (usePage().props.flash.status === "success")
            toast.success(usePage().props.flash.message)
        else if (usePage().props.flash.status === "error")
            toast.error(usePage().props.flash.message)
    }
})

</script>
<template>

    <Head title="Update Role" />
    <AuthenticatedLayout>
        <template #mobileMenuName>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Role</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 ">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-5">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <label class="input input-bordered flex items-center gap-2">
                                Name
                                <input v-model="form.name" type="text" class="grow" placeholder="Daisy" />
                            </label>
                            <span class="text-red-700" v-if="form.errors.name">{{ form.errors.name }}</span>

                            <div class="mt-5">
                                <span>Permissions</span>
                            </div>

                            <div class="flex gap-2 ">
                                <div v-for="(permission, index) in props.permissions" :key="permission.id" class="form-control">
                                    <label class="label cursor-pointer">
                                        <input type="checkbox"  :value="permission.name" v-model="form.permissions" class="checkbox" />
                                        <span class="label-text ml-2">{{permission.name}}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex justify-end mt-5 gap-2">
                                <Link :href="route('users.roles.list')"
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