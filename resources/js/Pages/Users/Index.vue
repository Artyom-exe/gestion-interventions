<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    users: Array, // La liste des utilisateurs
});

const search = ref(""); // Recherche par utilisateur
const selectedRole = ref(""); // Filtre par rôle
const selectedStatus = ref(""); // Filtre par statut

const filteredUsers = computed(() => {
    if (!props.users) return [];

    return props.users.filter((user) => {
        const matchesSearch =
            user.username?.toLowerCase().includes(search.value.toLowerCase()) ||
            user.email?.toLowerCase().includes(search.value.toLowerCase());

        const matchesRole =
            !selectedRole.value || user.role === selectedRole.value;

        const matchesStatus =
            !selectedStatus.value ||
            (selectedStatus.value === "actif" && user.is_active) ||
            (selectedStatus.value === "inactif" && !user.is_active);

        return matchesSearch && matchesRole && matchesStatus;
    });
});

function editUser(id) {
    // Redirige vers la page d'édition
    router.visit(route("users.edit", id));
}

const deleteUser = (id) => {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce ticket ?")) {
        router.delete(route('users.destroy', id));
    }
};
</script>

<template>
    <AppLayout title="Gestion des Utilisateurs">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion des Utilisateurs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"
                >
                    <!-- Barre de recherche et filtres -->
                    <div
                        class="flex flex-wrap justify-between items-center gap-4 mb-6"
                    >
                        <!-- Barre de recherche -->
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Rechercher un utilisateur..."
                            class="border rounded px-4 py-2 w-full sm:w-1/3"
                        />

                        <!-- Filtres -->
                        <select
                            v-model="selectedRole"
                            class="border rounded px-4 py-2 w-full sm:w-40 text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Tous les rôles</option>
                            <option value="admin">Admin</option>
                            <option value="technicien">Technicien</option>
                            <option value="client">Client</option>
                        </select>

                        <select
                            v-model="selectedStatus"
                            class="border rounded px-4 py-2 w-full sm:w-40 text-gray-700 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Tous les statuts</option>
                            <option value="actif">Actif</option>
                            <option value="inactif">Inactif</option>
                        </select>

                        <!-- Bouton pour ajouter un utilisateur -->
                        <button
                            @click="$inertia.get(route('users.create'))"
                            class="bg-blue-500 text-white px-4 py-2 rounded"
                        >
                            Ajouter un utilisateur
                        </button>
                    </div>

                    <!-- Table des utilisateurs -->
                    <table
                        v-if="filteredUsers.length"
                        class="min-w-full border-collapse"
                    >
                        <thead>
                            <tr class="border-b">
                                <th class="px-4 py-2 text-left">Utilisateur</th>
                                <th class="px-4 py-2 text-left">Email</th>
                                <th class="px-4 py-2 text-left">Rôle</th>
                                <th class="px-4 py-2 text-left">Statut</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="user in filteredUsers"
                                :key="user.id"
                                class="border-b"
                            >
                                <td class="px-4 py-2">{{ user.username }}</td>
                                <td class="px-4 py-2">{{ user.email }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        :class="{
                                            'bg-gray-200 text-gray-700 px-2 py-1 rounded':
                                                user.role === 'admin',
                                            'bg-blue-200 text-blue-700 px-2 py-1 rounded':
                                                user.role === 'technicien',
                                            'bg-green-200 text-green-700 px-2 py-1 rounded':
                                                user.role === 'client',
                                        }"
                                    >
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <span
                                        :class="{
                                            'bg-green-500 text-white px-2 py-1 rounded':
                                                user.is_active,
                                            'bg-red-500 text-white px-2 py-1 rounded':
                                                !user.is_active,
                                        }"
                                    >
                                        {{
                                            user.is_active ? "Actif" : "Inactif"
                                        }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <button
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded transition duration-300"
                                        @click="editUser(user.id)"
                                    >
                                        Éditer
                                    </button>

                                    <button
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded transition duration-300"
                                        @click="deleteUser(user.id)"
                                    >
                                        Supprimer
                                    </button>

                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <p v-else class="text-center text-gray-500">
                        Aucun utilisateur trouvé.
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
