<script setup lang="ts">
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <!-- Header Navigation -->
        <nav class="sticky top-0 z-50 border-b border-green-500/20 bg-gray-900/50 backdrop-blur-md">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <!-- Logo/Dashboard -->
                    <Link :href="route('dashboard')" class="group flex items-center gap-2">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-green-400 to-green-500 transition-all group-hover:shadow-lg group-hover:shadow-green-500/50">
                            <svg class="h-6 w-6 text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54-1.3-1.54c-.3-.36-.77-.36-1.07 0-.3.36-.3.92 0 1.28l1.83 2.19c.3.37.77.37 1.07 0l3.29-4c.3-.36.3-.92 0-1.28-.3-.37-.76-.37-1.07 0z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white hidden sm:inline">Mini Events</span>
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

                        <!-- User Menu -->
                        <div class="relative"> </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="border-b border-green-500/20 bg-gray-900/50 py-8 backdrop-blur-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <slot />
    </div>
</template>
