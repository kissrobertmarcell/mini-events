<script setup lang="ts">
import EventCard from '@/Components/EventCard.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    events: any;
}>();

const page = usePage<{
    props: {
        flash?: {
            success?: string;
            error?: string;
        };
    };
}>();

const hasEvents = computed(() => props.events.data.length > 0);
</script>

<template>
    <GuestLayout>
        <Head title="Események" />

        <div
            class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 px-4 py-12 sm:px-6 lg:px-8"
        >
            <div class="mx-auto max-w-7xl">
                <div class="mb-8">
                    <h1 class="mb-2 text-4xl font-bold text-white">
                        Események
                    </h1>
                    <p class="text-gray-400">
                        Fedezd fel az elkövetkezendő eseményeket és jelentkezz
                        fel
                    </p>
                </div>

                <div v-if="page.props.auth.user" class="mb-8">
                    <Link
                        href="/events/create"
                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-400 to-green-500 px-6 py-3 font-semibold text-gray-900 transition hover:shadow-lg hover:shadow-green-500/50 active:scale-95"
                    >
                        <svg
                            class="h-5 w-5"
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
                        Új esemény
                    </Link>
                </div>

                <Transition name="fade">
                    <div
                        v-if="page.props.flash?.success"
                        class="mb-4 rounded-lg border border-green-500/30 bg-green-500/10 p-4 backdrop-blur-sm"
                    >
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-green-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-300">
                                    {{ page.props.flash.success }}
                                </p>
                            </div>
                        </div>
                    </div>
                </Transition>

                <Transition name="fade">
                    <div
                        v-if="page.props.flash?.error"
                        class="mb-4 rounded-lg border border-red-500/30 bg-red-500/10 p-4 backdrop-blur-sm"
                    >
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg
                                    class="h-5 w-5 text-red-400"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-300">
                                    {{ page.props.flash.error }}
                                </p>
                            </div>
                        </div>
                    </div>
                </Transition>

                <div
                    v-if="hasEvents"
                    class="grid gap-6 md:grid-cols-2 lg:grid-cols-3"
                >
                    <EventCard
                        v-for="event in events.data"
                        :key="event.id"
                        :event="event"
                    />
                </div>

                <div v-else class="py-12 text-center">
                    <svg
                        class="mx-auto h-12 w-12 text-green-400/50"
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
                    <p class="mt-4 text-lg text-gray-400">
                        Jelenleg nincsenek jövőbeli események.
                    </p>
                </div>

                <div
                    v-if="events.last_page > 1"
                    class="mt-8 flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <Link
                        v-if="events.prev_page_url"
                        :href="events.prev_page_url"
                        class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        ← Előző
                    </Link>

                    <div class="flex items-center gap-2">
                        <span
                            class="px-3 py-2 font-medium text-gray-700 dark:text-gray-300"
                        >
                            {{ events.current_page }} / {{ events.last_page }}
                        </span>
                    </div>

                    <Link
                        v-if="events.next_page_url"
                        :href="events.next_page_url"
                        class="rounded-md border border-gray-300 px-4 py-2 text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Következő →
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
