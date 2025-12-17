<script setup lang="ts">
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Bejelentkezés" />

        <div class="flex min-h-screen items-center justify-center px-4">
            <div
                class="w-full max-w-md rounded-lg border border-green-500/20 bg-gray-800/50 p-8 backdrop-blur-sm"
            >
                <!-- Header -->
                <div class="mb-8 text-center">
                    <h1 class="mb-2 text-2xl font-bold text-white">
                        Bejelentkezés
                    </h1>
                    <p class="text-gray-400">Jelentkezz be az eseményekhez</p>
                </div>

                <!-- Status Message -->
                <div
                    v-if="status"
                    class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 p-3"
                >
                    <p class="text-sm font-medium text-green-300">
                        {{ status }}
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
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
                            autofocus
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
                            autocomplete="current-password"
                        />
                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label class="flex cursor-pointer items-center">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                            />
                            <span
                                class="ms-3 text-sm text-gray-300 hover:text-gray-200"
                            >
                                Emlékezz rám
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <PrimaryButton
                        class="w-full justify-center bg-gradient-to-r from-green-400 to-green-500 py-3 text-gray-900 hover:shadow-lg hover:shadow-green-500/50"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{
                            form.processing
                                ? 'Bejelentkezés...'
                                : 'Bejelentkezés'
                        }}
                    </PrimaryButton>
                </form>

                <!-- Footer Links -->
                <div
                    class="mt-8 space-y-4 border-t border-gray-700 pt-6 text-center"
                >
                    <div v-if="canResetPassword">
                        <Link
                            :href="route('password.request')"
                            class="text-sm text-gray-400 transition hover:text-green-400"
                        >
                            Elfelejtett jelszó?
                        </Link>
                    </div>

                    <div class="text-sm text-gray-400">
                        Még nincs fiókod?
                        <Link
                            href="/register"
                            class="font-medium text-green-400 transition hover:text-green-300"
                        >
                            Regisztrálj most
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
