<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    event: any;
}>();

const page = usePage();
const form = useForm({});

const handleRegister = () => {
    form.post(route('events.register', props.event.id));
};

const handleUnregister = () => {
    form.delete(route('events.unregister', props.event.id));
};

const isUserRegistered = computed(() => {
    if (!page.props.auth.user) return false;
    return props.event.signups?.some(
        (signup: any) => signup.user_id === page.props.auth.user.id,
    );
});

const eventDate = computed(() => {
    const date = new Date(props.event.event_date);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    return `${year}.${month}.${day}. ${hours}:${minutes}`;
});

const isFull = computed(() => props.event.available_spots <= 0);
</script>

<template>
    <div
        class="flex flex-col overflow-hidden rounded-lg bg-white shadow-md transition-shadow hover:shadow-lg dark:bg-gray-800"
    >
        <div class="relative h-48 overflow-hidden bg-gray-200 dark:bg-gray-700">
            <img
                v-if="event.image"
                :src="`/storage/${event.image}`"
                :alt="event.name"
                class="h-full w-full object-cover"
            />
            <div
                v-else
                class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-400 to-indigo-600"
            >
                <svg
                    class="h-12 w-12 text-white"
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
        </div>

        <div class="flex flex-1 flex-col p-4">
            <h3
                class="mb-2 line-clamp-2 text-lg font-semibold text-gray-900 dark:text-white"
            >
                {{ event.name }}
            </h3>

            <p
                class="mb-3 line-clamp-2 flex-1 text-sm text-gray-600 dark:text-gray-400"
            >
                {{ event.description }}
            </p>

            <div
                class="mb-2 flex items-center text-sm text-gray-700 dark:text-gray-300"
            >
                <svg
                    class="mr-2 h-4 w-4 flex-shrink-0"
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
                <span class="truncate">{{ eventDate }}</span>
            </div>

            <div class="mb-4 flex items-center text-sm">
                <svg
                    class="mr-2 h-4 w-4 flex-shrink-0"
                    :class="isFull ? 'text-red-600' : 'text-green-600'"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
                <span
                    :class="
                        isFull
                            ? 'font-semibold text-red-700 dark:text-red-400'
                            : 'text-green-700 dark:text-green-400'
                    "
                >
                    {{
                        isFull
                            ? 'BETELT'
                            : `${event.available_spots} szabad hely`
                    }}
                </span>
            </div>

            <div class="mt-auto flex gap-2">
                <button
                    v-if="page.props.auth.user"
                    @click="isUserRegistered ? handleUnregister() : handleRegister()"
                    :disabled="isUserRegistered || isFull"
                    :class="[
                        'flex-1 rounded-md px-3 py-2 text-sm font-semibold transition',
                        isUserRegistered || isFull
                            ? 'cursor-not-allowed bg-gray-400 text-gray-700 dark:bg-gray-600 dark:text-gray-300'
                            : 'cursor-pointer bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800',
                    ]"
                >
                    {{ isUserRegistered ? 'Már jelentkeztél' : 'Jelentkezem' }}
                </button>

                <Link
                    v-if="page.props.auth.user?.id === event.user_id"
                    :href="`/events/${event.id}/edit`"
                    class="rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
                >
                    Szerkesztés
                </Link>

                <Link
                    v-if="!page.props.auth.user"
                    :href="route('login')"
                    class="flex-1 rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-indigo-700"
                >
                    Bejelentkezés
                </Link>
            </div>
        </div>
    </div>
</template>
