<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <div class="min-he screen bg-no-repeat bg-cover bg-center" style="background-image: url('/garbage-truck.png')">
        <div class="flex justify-end">
            <div class="bg-white min-h-screen w-1/2 flex justify-center items-center">
                <div>
                    <form  @submit.prevent="submit">
                        <div>
                            <span class="text-sm text-gray-900">Welcome back</span>
                            <h1 class="text-2xl font-bold">Login to your account</h1>
                            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                                {{ status }}
                            </div>
                        </div>
                        <div class="mt-5">
                            <div>
                                <InputLabel for="email" value="Email" />
                
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autofocus
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
                                    autocomplete="current-password"
                                />
                
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                            <div class="block mt-4">
                                <label class="flex items-center">
                                    <Checkbox name="remember" v-model:checked="form.remember" />
                                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                                </label>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Forgot your password?
                                </Link>
                
                                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Log in
                                </PrimaryButton>
                            </div>
                        </div>
                    </form>
                    <p class="mt-8"> Dont have an account? <span class="cursor-pointer text-sm text-blue-600"> <Link :href="route('register')">Create now.</Link> </span></p>
                </div>
            </div>
        </div>
    </div>
</template>
