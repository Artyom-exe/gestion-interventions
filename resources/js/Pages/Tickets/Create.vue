<template>
    <AppLayout title="Créer un Ticket">
        <div class="container mx-auto p-4">
            <!-- En-tête -->
            <div class="flex justify-between items-center mb-6">
                <NavLink :href="route('tickets.index')" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                    Retour
                </NavLink>
                <h1 class="text-3xl font-bold text-gray-800">Créer un Ticket</h1>
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
                        <label class="block text-gray-700 font-bold">Pièces jointes</label>
                        <input
                            type="file"
                            multiple
                            @change="handleFileUpload"
                            class="w-full border border-dashed border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        />
                        <div class="grid grid-cols-3 gap-4 mt-4">
                            <div v-for="(image, index) in form.newImages" :key="index" class="relative">
                                <img
                                    :src="image.preview"
                                    alt="Prévisualisation"
                                    class="w-full h-32 object-cover rounded"
                                />
                                <button
                                    type="button"
                                    @click="removeImage(index)"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" @click="cancel" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg">
                            Annuler
                        </button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Créer
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
        clients: Array,
        technicians: Array,
        errors: Object,
    },
    data() {
        return {
            form: {
                client_id: '',
                description: '',
                priority: 'moyenne',
                status: 'ouvert',
                assigned_to: '',
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

            this.$inertia.post(this.route('tickets.store'), formData);
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
        removeImage(index) {
            this.form.newImages.splice(index, 1);
        },
        cancel() {
            this.$inertia.get(this.route('tickets.index'));
        },
    },
};
</script>
