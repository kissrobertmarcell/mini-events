<script setup lang="ts">
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    event: any;
}>();

const confirmingDeletion = ref(false);
const imagePreview = ref<string | null>(
    props.event.image ? `/storage/${props.event.image}` : null,
);

const form = useForm({
    name: props.event.name,
    description: props.event.description,
    event_date: props.event.event_date.slice(0, 16),
    limit: props.event.limit,
    image: null as File | null,
});

const deleteForm = useForm({});

const submit = () => {
    form.post(route('events.update', props.event.id), {
        method: 'patch',
        forceFormData: true,
    });
};

const handleDelete = () => {
    deleteForm.delete(route('events.destroy', props.event.id));
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
        <Head title="Esemény szerkesztése" />

        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Esemény szerkesztése
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
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name"
                            />
                        </div>

                        <div>
                            <InputLabel for="description" value="Leírás" />
                            <textarea
                                id="description"
                                v-model="form.description"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                rows="6"
                                required
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.description"
                            />
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
                                            >Új kép feltöltéséhez kattints</span
                                        >
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
                                {{
                                    form.processing
                                        ? 'Mentés...'
                                        : 'Módosítások mentése'
                                }}
                            </PrimaryButton>
                        </div>
                    </form>

                    <div
                        class="mt-8 border-t border-gray-200 pt-8 dark:border-gray-700"
                    >
                        <div class="mb-6">
                            <h3
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                Esemény törlése
                            </h3>
                            <p
                                class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                            >
                                Az eseményt véglegesen törlöd. Ez a művelet nem
                                vonható vissza.
                            </p>
                        </div>
                        <DangerButton @click="confirmingDeletion = true">
                            Esemény törlése
                        </DangerButton>
                    </div>
                </div>
            </div>
        </div>

        <Modal
            :show="confirmingDeletion"
            @close="confirmingDeletion = false"
            closeable
        >
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    Biztosan szeretnéd törölni ezt az eseményt?
                </h2>

                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                    Ez a művelet végérvényes és nem vonható vissza.
                </p>

                <div class="mt-6 flex justify-end gap-4">
                    <button
                        @click="confirmingDeletion = false"
                        class="px-4 py-2 font-medium text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100"
                    >
                        Mégsem
                    </button>
                    <DangerButton
                        @click="handleDelete"
                        :class="{ 'opacity-25': deleteForm.processing }"
                        :disabled="deleteForm.processing"
                    >
                        {{ deleteForm.processing ? 'Törlés...' : 'Törlés' }}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
