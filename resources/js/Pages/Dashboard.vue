<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Dashboard" />

        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Dashboard
            </h2>
        </template>

        <div
            class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-12 sm:px-6 lg:px-8"
        >
            <div class="mx-auto max-w-7xl">
                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1 class="mb-2 text-4xl font-bold text-white">
                        Üdvözlünk, {{ page.props.auth.user?.name }}!
                    </h1>
                    <p class="text-gray-400">
                        Kezeld az eseményeidet és kövesd a regisztrációkat
                    </p>
                </div>

                <!-- Quick Stats -->
                <div class="mb-8 grid gap-6 md:grid-cols-3">
                    <!-- My Events -->
                    <div
                        class="rounded-lg border border-green-500/20 bg-gray-800/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">
                                Saját Események
                            </h3>
                            <svg
                                class="h-8 w-8 text-green-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                />
                            </svg>
                        </div>
                        <p class="mb-4 text-3xl font-bold text-green-400">0</p>
                        <Link
                            href="/"
                            class="inline-block text-sm font-medium text-gray-300 transition hover:text-green-400"
                        >
                            Összes esemény megtekintése →
                        </Link>
                    </div>

                    <!-- Registered Events -->
                    <div
                        class="rounded-lg border border-green-500/20 bg-gray-800/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">
                                Bejelentkezve
                            </h3>
                            <svg
                                class="h-8 w-8 text-green-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <p class="mb-4 text-3xl font-bold text-green-400">0</p>
                        <p class="text-sm text-gray-400">következő 30 napban</p>
                    </div>

                    <!-- Profile -->
                    <div
                        class="rounded-lg border border-green-500/20 bg-gray-800/50 p-6 backdrop-blur-sm"
                    >
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">
                                Profil
                            </h3>
                            <svg
                                class="h-8 w-8 text-green-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                        </div>
                        <p class="mb-4 text-gray-300">
                            {{ page.props.auth.user?.email }}
                        </p>
                        <Link
                            :href="route('profile.edit')"
                            class="inline-block text-sm font-medium text-gray-300 transition hover:text-green-400"
                        >
                            Szerkesztés →
                        </Link>
                    </div>
                </div>

                <!-- Actions -->
                <div
                    class="rounded-lg border border-green-500/20 bg-gray-800/50 p-8 backdrop-blur-sm"
                >
                    <h2 class="mb-6 text-2xl font-bold text-white">
                        Gyors műveletek
                    </h2>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <Link
                            href="/events/create"
                            class="group flex items-center gap-4 rounded-lg border border-green-500/30 bg-green-500/10 p-6 transition hover:border-green-500/60 hover:bg-green-500/20"
                        >
                            <div
                                class="rounded-lg bg-green-500/20 p-3 transition group-hover:bg-green-500/30"
                            >
                                <svg
                                    class="h-6 w-6 text-green-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">
                                    Új esemény
                                </h3>
                                <p class="text-sm text-gray-400">
                                    Hozz létre egy új eseményt
                                </p>
                            </div>
                        </Link>

                        <Link
                            href="/"
                            class="group flex items-center gap-4 rounded-lg border border-green-500/30 bg-green-500/10 p-6 transition hover:border-green-500/60 hover:bg-green-500/20"
                        >
                            <div
                                class="rounded-lg bg-green-500/20 p-3 transition group-hover:bg-green-500/30"
                            >
                                <svg
                                    class="h-6 w-6 text-green-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">
                                    Események megtekintése
                                </h3>
                                <p class="text-sm text-gray-400">
                                    Összes közös eseményt
                                </p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
