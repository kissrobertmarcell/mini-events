<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    description: '',
    event_date: '',
    limit: '1',
    image: null as File | null,
});

const imagePreview = ref<string | null>(null);

const submit = () => {
    form.post(route('events.store'), {
        forceFormData: true,
    });
};

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        form.image = file;

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(file);
    }
};

const clearImage = () => {
    form.image = null;
    imagePreview.value = null;
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Új esemény" />

        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Új esemény létrehozása
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Esemény neve" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                placeholder="Pl. Nyári fesztivál"
                                required
                                autofocus
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Minimum 5 karakter
                            </p>
                        </div>

                        <div>
                            <InputLabel for="description" value="Leírás" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                rows="6"
                                placeholder="Az esemény részletes leírása..."
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Maximum 5000 karakter
                            </p>
                        </div>

                        <div>
                            <InputLabel
                                for="event_date"
                                value="Esemény dátuma és ideje"
                            />
                            <TextInput
                                id="event_date"
                                type="datetime-local"
                                class="mt-1 block w-full"
                                v-model="form.event_date"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.event_date"
                            />
                        </div>

                        <div>
                            <InputLabel for="limit" value="Létszámlimit" />
                            <TextInput
                                id="limit"
                                type="number"
                                class="mt-1 block w-full"
                                v-model.number="form.limit"
                                min="1"
                                placeholder="Pl. 50"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.limit"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="image"
                                value="Esemény képe (opcionális)"
                            />

                            <div v-if="imagePreview" class="relative mt-4">
                                <img
                                    :src="imagePreview"
                                    alt="Preview"
                                    class="h-40 w-full rounded-md object-cover"
                                />
                                <button
                                    type="button"
                                    @click="clearImage"
                                    class="absolute right-2 top-2 rounded-md bg-red-600 p-1 text-white hover:bg-red-700"
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
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <label
                                v-else
                                class="mt-2 flex cursor-pointer justify-center rounded-lg border-2 border-dashed border-gray-300 px-6 py-10 text-center transition hover:border-indigo-500 dark:border-gray-600"
                            >
                                <div class="text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        />
                                    </svg>
                                    <p
                                        class="mt-2 text-sm text-gray-600 dark:text-gray-400"
                                    >
                                        <span
                                            class="font-semibold text-indigo-600 dark:text-indigo-400"
                                            >Kattints a feltöltéshez</span
                                        >
                                        vagy húzz egy képet
                                    </p>
                                    <p
                                        class="text-xs text-gray-500 dark:text-gray-500"
                                    >
                                        PNG, JPG, GIF - maximum 3MB
                                    </p>
                                </div>
                                <input
                                    id="image"
                                    type="file"
                                    accept="image/*"
                                    class="hidden"
                                    @change="handleImageChange"
                                />
                            </label>

                            <InputError
                                class="mt-2"
                                :message="form.errors.image"
                            />
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6">
                            <Link
                                href="/"
                                class="font-medium text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                            >
                                Mégse
                            </Link>
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="mr-2 h-4 w-4 animate-spin"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                                {{
                                    form.processing
                                        ? 'Mentés...'
                                        : 'Esemény létrehozása'
                                }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
