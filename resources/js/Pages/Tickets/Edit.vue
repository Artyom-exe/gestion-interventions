<template>
    <AppLayout title="Modifier un Ticket">
        <div class="container mx-auto p-4">
            <!-- En-tête -->
            <div class="flex justify-between items-center mb-6">
                <NavLink :href="route('tickets.index')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    Retour
                </NavLink>
                <h1 class="text-3xl font-bold text-gray-800">Modifier le Ticket #{{ props.ticket.id }}</h1>
            </div>

            <!-- Formulaire -->
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <form @submit.prevent="submitForm" enctype="multipart/form-data">
                    <!-- Sélection du client -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Client*</label>
                        <select
                            v-model="form.client_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                            <option value="" disabled>Sélectionner un client</option>
                            <option v-for="client in props.clients" :key="client.id" :value="client.id">
                                {{ client.username }}
                            </option>
                        </select>
                        <InputError :message="props.errors.client_id" class="mt-2" />
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Description*</label>
                        <textarea
                            v-model="form.description"
                            placeholder="Décrivez le problème..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            rows="4"
                        ></textarea>
                        <InputError :message="props.errors.description" class="mt-2" />
                    </div>

                    <!-- Priorité et Statut -->
                    <div class="flex mb-4 space-x-4">
                        <!-- Priorité -->
                        <div class="w-1/2">
                            <label class="block text-gray-700 font-bold">Priorité*</label>
                            <select
                                v-model="form.priority"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            >
                                <option value="faible">Faible</option>
                                <option value="moyenne">Moyenne</option>
                                <option value="haute">Haute</option>
                            </select>
                            <InputError :message="props.errors.priority" class="mt-2" />
                        </div>

                        <!-- Statut -->
                        <div class="w-1/2">
                            <label class="block text-gray-700 font-bold">Statut*</label>
                            <select
                                v-model="form.status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            >
                                <option value="ouvert">Ouvert</option>
                                <option value="en_cours">En cours</option>
                                <option value="fermé">Fermé</option>
                            </select>
                            <InputError :message="props.errors.status" class="mt-2" />
                        </div>
                    </div>

                    <!-- Assignation -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Assigner à un technicien*</label>
                        <select
                            v-model="form.assigned_to"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                            <option value="" disabled>Sélectionner un technicien</option>
                            <option v-for="technician in props.technicians" :key="technician.id" :value="technician.id">
                                {{ technician.username }}
                            </option>
                        </select>
                        <InputError :message="props.errors.assigned_to" class="mt-2" />
                    </div>

                    <!-- Images -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Images</label>
                        <!-- Images existantes -->
                        <div v-if="form.existingImages.length" class="mt-4 grid grid-cols-3 gap-4">
                            <div
                                v-for="(image, index) in form.existingImages"
                                :key="image.id"
                                class="relative"
                            >
                                <img
                                    :src="`/storage/${image.image_path}`"
                                    alt="Image du ticket"
                                    class="w-full h-32 object-cover rounded"
                                />
                                <button
                                    type="button"
                                    @click="removeImage(image.id, index)"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>

                        <!-- Ajouter de nouvelles images -->
                        <input
                            type="file"
                            @change="handleFileUpload"
                            multiple
                            class="w-full border border-dashed border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none mt-4"
                        />
                        <div v-if="form.newImages.length" class="mt-4 grid grid-cols-3 gap-4">
                            <div v-for="(image, index) in form.newImages" :key="index" class="relative">
                                <img :src="image.preview" alt="Preview" class="w-full h-32 object-cover rounded" />
                                <button
                                    type="button"
                                    @click="removeNewImage(index)"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="cancel"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg"
                        >
                            Annuler
                        </button>
                        <button
                            type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
                        >
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavLink from '@/Components/NavLink.vue';
import InputError from '@/Components/InputError.vue';

// Props
const props = defineProps({
    ticket: Object,
    clients: Array,
    technicians: Array,
    errors: Object,
});

// Form
const form = useForm({
    client_id: props.ticket.client_id || '',
    description: props.ticket.description || '',
    priority: props.ticket.priority || 'moyenne',
    status: props.ticket.status || 'ouvert',
    assigned_to: props.ticket.assigned_to || '',
    newImages: [],
    existingImages: props.ticket.images || [],
    removedImages: [], // Ajout d'un tableau pour suivre les images supprimées
});

// Methods
const handleFileUpload = (event) => {
    const files = Array.from(event.target.files);
    files.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            form.newImages.push({ file, preview: e.target.result });
        };
        reader.readAsDataURL(file);
    });
};

const removeImage = (imageId, index) => {
    form.existingImages.splice(index, 1);
    form.removedImages.push(imageId); // Ajouter l'image supprimée au tableau removedImages
};

const removeNewImage = (index) => {
    form.newImages.splice(index, 1);
};

const submitForm = () => {
    const formData = new FormData();
    formData.append('_method', 'PUT'); // Ajout de cette ligne pour spécifier la méthode PUT
    formData.append('client_id', form.client_id);
    formData.append('description', form.description);
    formData.append('priority', form.priority);
    formData.append('status', form.status);
    formData.append('assigned_to', form.assigned_to);

    form.newImages.forEach((image, index) => {
        formData.append(`images[${index}]`, image.file);
    });

    form.removedImages.forEach((imageId, index) => {
        formData.append(`removedImages[${index}]`, imageId);
    });

    router.post(route('tickets.update', props.ticket.id), formData, {
        onSuccess: () => form.reset('newImages', 'removedImages'),
    });
};

const cancel = () => {
    router.get(route('tickets.index'));
};
</script>
