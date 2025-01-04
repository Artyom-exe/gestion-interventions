<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    username: props.user.username,
    email: props.user.email,
    role: props.user.role,
    is_active: props.user.is_active,
});

function submit() {
    form.put(route("users.update", props.user.id));
}
</script>

<template>
    <AppLayout title="Éditer l'utilisateur">
        <div
            class="max-w-2xl mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mt-20"
        >
            <h2 class="text-2xl font-bold mb-6">Éditer l'utilisateur</h2>

            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label
                        for="username"
                        class="block text-gray-700 text-sm font-bold mb-2"
                        >Nom d'utilisateur :</label
                    >
                    <input
                        id="username"
                        v-model="form.username"
                        type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>

                <div class="mb-4">
                    <label
                        for="email"
                        class="block text-gray-700 text-sm font-bold mb-2"
                        >Email :</label
                    >
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    />
                </div>

                <div class="mb-4">
                    <label
                        for="role"
                        class="block text-gray-700 text-sm font-bold mb-2"
                        >Rôle :</label
                    >
                    <select
                        id="role"
                        v-model="form.role"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option value="admin">Admin</option>
                        <option value="technicien">Technicien</option>
                        <option value="client">Client</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label
                        for="is_active"
                        class="block text-gray-700 text-sm font-bold mb-2"
                        >Statut :</label
                    >
                    <select
                        id="is_active"
                        v-model="form.is_active"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>

                <div class="flex items-center justify-between">
                    <button
                        type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    >
                        Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
