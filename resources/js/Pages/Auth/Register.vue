<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />
    <div class="min-he screen bg-no-repeat bg-cover bg-center" style="background-image: url('/garbage-truck.png')">
        <div class="flex justify-end">
            <div class="bg-white min-h-screen w-1/2 flex justify-center items-center">
                <div>
                    <form @submit.prevent="submit">
                        <div>
                            <span class="text-sm text-gray-900">Registration</span>
                            <h1 class="text-2xl font-bold">Fill out to get started</h1>
                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                {{ status }}
                            </div>
                        </div>
                        <div>
                            <InputLabel for="name" value="Name" />
            
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
            
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="email" value="Email" />
            
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />
            
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="password" value="Password" />
            
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />
            
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>
                        <div class="mt-4">
                            <InputLabel for="password_confirmation" value="Confirm Password" />
            
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />
            
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>
            
                        <div class="flex items-center justify-end mt-4">
                            <Link
                                :href="route('login')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Already registered?
                            </Link>
            
                            <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Register
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
