<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Regisztráció" />

        <div class="flex min-h-screen items-center justify-center px-4">
            <div
                class="w-full max-w-md rounded-lg border border-green-500/20 bg-gray-800/50 p-8 backdrop-blur-sm"
            >
                <!-- Header -->
                <div class="mb-8 text-center">
                    <h1 class="mb-2 text-2xl font-bold text-white">
                        Regisztráció
                    </h1>
                    <p class="text-gray-400">Hozz létre egy új fiókot</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Name -->
                    <div>
                        <InputLabel for="name" value="Teljes név" />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-700/50 px-4 py-3 text-white placeholder-gray-500 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                            v-model="form.name"
                            placeholder="János Kovács"
                            required
                            autofocus
                            autocomplete="name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Email -->
                    <div>
                        <InputLabel for="email" value="E-mail cím" />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-700/50 px-4 py-3 text-white placeholder-gray-500 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                            v-model="form.email"
                            placeholder="email@example.com"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password -->
                    <div>
                        <InputLabel for="password" value="Jelszó" />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-700/50 px-4 py-3 text-white placeholder-gray-500 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                            v-model="form.password"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Jelszó megerősítése"
                        />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-2 block w-full rounded-lg border border-gray-700 bg-gray-700/50 px-4 py-3 text-white placeholder-gray-500 transition focus:border-green-500 focus:outline-none focus:ring-2 focus:ring-green-500/20"
                            v-model="form.password_confirmation"
                            placeholder="••••••••"
                            required
                            autocomplete="new-password"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <!-- Submit Button -->
                    <PrimaryButton
                        class="w-full justify-center bg-gradient-to-r from-green-400 to-green-500 py-3 text-gray-900 hover:shadow-lg hover:shadow-green-500/50"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing ? 'Regisztráció...' : 'Regisztrálok'
                        }}
                    </PrimaryButton>
                </form>

                <!-- Footer Links -->
                <div class="mt-8 border-t border-gray-700 pt-6 text-center">
                    <div class="text-sm text-gray-400">
                        Már van fiókod?
                        <Link
                            :href="route('login')"
                            class="font-medium text-green-400 transition hover:text-green-300"
                        >
                            Jelentkezz be
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
