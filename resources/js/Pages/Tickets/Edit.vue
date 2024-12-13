<template>
    <AppLayout title="Modifier un Ticket">
        <div class="container mx-auto p-4">
            <!-- En-tête -->
            <div class="flex justify-between items-center mb-6">
                <NavLink :href="route('tickets.index')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    Retour
                </NavLink>
                <h1 class="text-3xl font-bold text-gray-800">Modifier le Ticket #{{ ticket.id }}</h1>
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
                            <option v-for="client in clients" :key="client.id" :value="client.id">
                                {{ client.username }}
                            </option>
                        </select>
                        <InputError :message="errors.client_id" class="mt-2" />
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
                        <InputError :message="errors.description" class="mt-2" />
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
                            <InputError :message="errors.priority" class="mt-2" />
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
                            <InputError :message="errors.status" class="mt-2" />
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
                            <option v-for="technician in technicians" :key="technician.id" :value="technician.id">
                                {{ technician.username }}
                            </option>
                        </select>
                        <InputError :message="errors.assigned_to" class="mt-2" />
                    </div>

                    <!-- Images -->
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold">Images</label>
                        <!-- Prévisualisation des images existantes -->
                        <div v-if="form.existingImages.length" class="mt-4 grid grid-cols-3 gap-4">
                            <div v-for="(image, index) in form.existingImages" :key="image.id" class="relative">
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

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import NavLink from '@/Components/NavLink.vue';
import InputError from '@/Components/InputError.vue';

export default {
    components: {
        AppLayout,
        NavLink,
        InputError,
    },
    props: {
        ticket: Object,
        clients: Array,
        technicians: Array,
        errors: Object,
    },
    data() {
        return {
            form: {
                client_id: this.ticket.client_id || '',
                description: this.ticket.description || '',
                priority: this.ticket.priority || 'moyenne',
                status: this.ticket.status || 'ouvert',
                assigned_to: this.ticket.assigned_to || '',
                existingImages: this.ticket.images || [],
                newImages: [],
            },
        };
    },
    methods: {
        submitForm() {
            const formData = new FormData();
            formData.append('client_id', this.form.client_id);
            formData.append('description', this.form.description);
            formData.append('priority', this.form.priority);
            formData.append('status', this.form.status);
            formData.append('assigned_to', this.form.assigned_to);

            this.form.newImages.forEach((image) => {
                formData.append('images[]', image.file);
            });

            this.$inertia.post(route('tickets.update', { id: this.ticket.id }), formData);
        },
        handleFileUpload(event) {
            const files = Array.from(event.target.files);
            files.forEach((file) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.form.newImages.push({
                        file,
                        preview: e.target.result,
                    });
                };
                reader.readAsDataURL(file);
            }); 
        },
        removeImage(imageId, index) {
            this.$inertia.delete(route('tickets.deleteImage', { ticketId: this.ticket.id, imageId }));
            this.form.existingImages.splice(index, 1);
        },
        removeNewImage(index) {
            this.form.newImages.splice(index, 1);
        },
        cancel() {
            this.$inertia.get(route('tickets.index'));
        },
    },
};
</script>
