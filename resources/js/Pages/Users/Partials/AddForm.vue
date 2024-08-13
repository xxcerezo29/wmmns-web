<script setup lang="ts">
import Modal from '@/Components/ui/daisyUI/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('users.store'),{
    // onSuccess: () => {

    // }
  });
}
</script>

<template>
    <Modal id="addModal" title="User Form">
        <template #body>
            <div class="mt-5">
              <form @submit.prevent="submit">
                <label class="input input-bordered flex items-center gap-2">
                  Name
                  <input v-model="form.name" type="text" class="grow" placeholder="Daisy" />
                </label>
                <span class="text-red-700" v-if="form.errors.name">{{form.errors.name}}</span>
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
              </form>
                
            </div>
            
        </template>
        <template #actions>
            <PrimaryButton @click="submit">Submit</PrimaryButton>
        </template>
    </Modal>
</template>