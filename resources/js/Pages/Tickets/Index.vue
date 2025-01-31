<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import NavLink from '@/Components/NavLink.vue';

const props = defineProps({
    tickets: Array,
    authUser: Object, // Utilisateur connecté
});

// Recherche et filtres
const searchQuery = ref('');
const statusFilter = ref('');
const priorityFilter = ref('');
const sortKey = ref('');
const sortOrder = ref('asc');

// Tickets filtrés par recherche et filtres
const filteredTickets = computed(() => {
    return props.tickets.filter(ticket => {
        const matchesSearch = ticket.description.toLowerCase().includes(searchQuery.value.toLowerCase());
        const matchesStatus = statusFilter.value ? ticket.status === statusFilter.value : true;
        const matchesPriority = priorityFilter.value ? ticket.priority === priorityFilter.value : true;
        return matchesSearch && matchesStatus && matchesPriority;
    });
});

// Tickets triés
const sortedTickets = computed(() => {
    return filteredTickets.value.sort((a, b) => {
        let modifier = sortOrder.value === 'asc' ? 1 : -1;
        if (a[sortKey.value] < b[sortKey.value]) return -1 * modifier;
        if (a[sortKey.value] > b[sortKey.value]) return 1 * modifier;
        return 0;
    });
});

const deleteTicket = (id) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        router.delete(route('tickets.destroy', id));
    }
};


</script>

<template>
    <AppLayout title="Tickets">
        <div class="container mx-auto p-4">
            <!-- Titre -->
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Liste des Tickets</h1>

            <!-- Barre d'actions -->
            <div class="flex justify-between items-center mb-4">
                <!-- Barre de recherche -->
                <div class="w-1/3">
                    <input type="text" placeholder="Rechercher un ticket..." v-model="searchQuery"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
                </div>

                <!-- Filtre par statut -->
                <div class="w-1/4">
                    <select v-model="statusFilter"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Tous les statuts</option>
                        <option value="ouvert">Ouvert</option>
                        <option value="en_cours">En cours</option>
                        <option value="fermé">Fermé</option>
                    </select>
                </div>

                <!-- Filtre par priorité -->
                <div class="w-1/4">
                    <select v-model="priorityFilter"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">Toutes les priorités</option>
                        <option value="faible">Faible</option>
                        <option value="moyenne">Moyenne</option>
                        <option value="haute">Haute</option>
                    </select>
                </div>

                <!-- Bouton de création (réservé aux administrateurs) -->
                <template v-if="authUser.role === 'admin'">
                    <NavLink :href="route('tickets.create')"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow-md transition duration-300">
                        Créer un Ticket
                    </NavLink>
                </template>
            </div>

            <!-- Tableau des tickets -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 bg-white rounded-lg shadow-md">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider border-b">ID</th>
                            <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider border-b">
                                Description
                            </th>
                            <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider border-b">Statut</th>
                            <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider border-b">Priorité
                            </th>
                            <th class="text-left px-6 py-3 text-gray-600 uppercase tracking-wider border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="ticket in sortedTickets" :key="ticket.id" class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 border-b text-gray-800">{{ ticket.id }}</td>
                            <td class="px-6 py-4 border-b text-gray-800">{{ ticket.description }}</td>
                            <td class="px-6 py-4 border-b">
                                <span class="px-2 py-1 rounded-full text-white text-sm" :class="{
                                    'bg-green-500': ticket.status === 'ouvert',
                                    'bg-yellow-500': ticket.status === 'en_cours',
                                    'bg-red-500': ticket.status === 'fermé'
                                }">
                                    {{ ticket.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b">
                                <span class="px-2 py-1 rounded-full text-white text-sm" :class="{
                                    'bg-blue-500': ticket.priority === 'faible',
                                    'bg-yellow-500': ticket.priority === 'moyenne',
                                    'bg-red-500': ticket.priority === 'haute'
                                }">
                                    {{ ticket.priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b">
                                <!-- Lien Voir (disponible pour tous) -->
                                <a :href="route('tickets.show', ticket.id)" class="text-blue-500 hover:underline mr-2">
                                    Voir
                                </a>

                                <!-- Lien Modifier (réservé aux administrateurs) -->
                                <template v-if="authUser.role === 'admin'">
                                    <a :href="route('tickets.edit', ticket.id)" class="text-green-500 hover:underline">
                                        Modifier
                                    </a>
                                </template>

                                <!-- Lien Supprimer (réservé aux administrateurs) -->
                            <template v-if="authUser.role === 'admin'">
                            <button
                                @click="deleteTicket(ticket.id)"
                                class="text-red-500 hover:underline"
                            >
                                Supprimer
                            </button>

                            </template>



                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
