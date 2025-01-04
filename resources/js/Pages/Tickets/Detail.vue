<template>
    <AppLayout title="Détail du Ticket">
        <div class="flex">
            <!-- Main Content -->
            <main class="flex-1 relative">
                <!-- Ticket Header -->
                <section class="p-4">
                    <div class="flex items-center justify-between">
                        <NavLink :href="route('tickets.index')" class="bg-gray-100 p-2 rounded">Retour</NavLink>
                        <h1 class="text-xl font-bold text-blue-900">
                            Ticket #{{ ticket.id }} - {{ ticket.description || 'Sans Titre' }}
                        </h1>
                        <div class="flex space-x-4">
                            <button
                                class="bg-green-500 text-white p-2 rounded"
                                :class="{
                                    'bg-red-500': ticket.status === 'fermé',
                                    'bg-yellow-500': ticket.status === 'en_cours',
                                    'bg-green-500': ticket.status === 'ouvert',
                                }"
                            >
                                {{ ticket.status }}
                            </button>
                            <NavLink :href="route('tickets.edit', ticket.id)" class="bg-blue-600 text-white p-2 rounded">
                                Modifier
                            </NavLink>
                        </div>
                    </div>
                </section>

                <!-- Main Ticket Content -->
                <section class="p-4 flex">
                    <!-- Left Column -->
                    <div class="w-2/3">
                        <!-- Ticket Info Card -->
                        <div class="bg-white border border-gray-200 p-4 rounded mb-4">
                            <h2 class="text-lg font-bold text-blue-900">Informations du ticket</h2>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <p class="text-gray-500">Client:</p>
                                    <p class="text-gray-900">{{ ticket.client?.username || 'Non attribué' }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Créé le:</p>
                                    <p class="text-gray-900">{{ formatDate(ticket.created_at) }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Priorité:</p>
                                    <p class="text-gray-900">{{ ticket.priority }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Assigné à:</p>
                                    <p class="text-gray-900">{{ ticket.assigned_user?.username || 'Non assigné' }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-gray-500">Description:</p>
                                <div class="bg-gray-50 p-4 rounded mt-2">
                                    <p class="text-gray-900">{{ ticket.description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Images Section -->
                        <div v-if="ticket.images.length" class="bg-white border border-gray-200 p-4 rounded">
                            <h2 class="text-lg font-bold text-blue-900">Images associées</h2>
                            <div class="grid grid-cols-3 gap-4 mt-4">
                                <img
                                    v-for="image in ticket.images"
                                    :key="image.id"
                                    :src="`/storage/${image.image_path}`"
                                    alt="Image du ticket"
                                    class="w-full h-32 object-cover rounded-lg shadow"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="w-1/3 ml-4">
                        <!-- Activity Timeline -->
                        <div v-if="ticket.status_history.length" class="bg-white border border-gray-200 p-4 rounded">
                            <h2 class="text-lg font-bold text-blue-900">Historique des statuts</h2>
                            <div class="mt-4">
                                <div
                                    class="flex items-start mb-4"
                                    v-for="status in ticket.status_history"
                                    :key="status.id"
                                >
                                    <div class="bg-blue-600 h-2 w-2 rounded-full mt-1"></div>
                                    <div class="ml-4">
                                        <p class="text-gray-900">{{ status.status }}</p>
                                        <p class="text-gray-500 text-sm">Modifié le {{ formatDate(status.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </AppLayout>
</template>

<script setup>
import { defineProps } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import NavLink from "@/Components/NavLink.vue";

// Props
const props = defineProps({
    ticket: Object,
});

// Functions
const formatDate = (date) =>
    new Date(date).toLocaleString("fr-FR", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
</script>

<style scoped>
/* Styles spécifiques */
</style>
