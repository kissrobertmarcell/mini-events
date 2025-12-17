<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950"
    >
        <!-- Header -->
        <nav
            class="sticky top-0 z-50 border-b border-green-500/20 bg-gray-900/50 backdrop-blur-md"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo/Events -->
                    <Link href="/" class="group flex items-center gap-2">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-green-400 to-green-500 transition-all group-hover:shadow-lg group-hover:shadow-green-500/50"
                        >
                            <svg
                                class="h-6 w-6 text-gray-900"
                                fill="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.3-1.54c-.3-.36-.77-.36-1.07 0-.3.36-.3.92 0 1.28l1.83 2.19c.3.37.77.37 1.07 0l3.29-4c.3-.36.3-.92 0-1.28-.3-.37-.76-.37-1.07 0z"
                                />
                            </svg>
                        </div>
                        <span
                            class="hidden text-xl font-bold text-white sm:inline"
                            >Mini Events</span
                        >
                    </Link>

                    <!-- Right Actions -->
                    <div class="flex items-center gap-4">
                        <Link
                            href="/"
                            class="font-medium text-gray-300 transition hover:text-green-400"
                        >
                            Események
                        </Link>

                        <div class="h-6 w-px bg-green-500/20"></div>

                        <div
                            v-if="page.props.auth.user"
                            class="flex items-center gap-4"
                        >
                            <Link
                                :href="route('dashboard')"
                                class="text-sm font-medium text-gray-300 transition hover:text-green-400"
                            >
                                {{ page.props.auth.user.name }}
                            </Link>
                            <Link
                                :href="route('logout')"
                                method="post"
                                as="button"
                                class="px-4 py-2 text-sm font-medium text-gray-300 transition hover:text-green-400"
                            >
                                Kijelentkezés
                            </Link>
                        </div>

                        <div v-else class="flex items-center gap-3">
                            <Link
                                href="/login"
                                class="px-4 py-2 text-sm font-medium text-gray-300 transition hover:text-green-400"
                            >
                                Bejelentkezés
                            </Link>
                            <Link
                                href="/register"
                                class="rounded-lg bg-gradient-to-r from-green-400 to-green-500 px-4 py-2 text-sm font-medium font-semibold text-gray-900 transition-all hover:shadow-lg hover:shadow-green-500/50"
                            >
                                Regisztráció
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <slot />
    </div>
</template>

<style scoped>
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: rgba(74, 222, 128, 0.3);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(74, 222, 128, 0.5);
}
</style>
