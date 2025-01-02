<script setup>
import { useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

const form = useForm({
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: "",
    is_active: false,
});

const submit = () => {
    form.post(route("users.store"));
};
</script>

<template>
    <AppLayout title="Ajouter un utilisateur">
        <div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-bold mb-6">Ajouter un utilisateur</h1>
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label for="username" class="block font-semibold"
                        >Nom d'utilisateur</label
                    >
                    <input
                        v-model="form.username"
                        type="text"
                        id="username"
                        class="w-full border-gray-300 rounded px-3 py-2"
                    />
                    <div
                        v-if="form.errors.username"
                        class="text-red-500 text-sm"
                    >
                        {{ form.errors.username }}
                    </div>
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-semibold">Email</label>
                    <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        class="w-full border-gray-300 rounded px-3 py-2"
                    />
                    <div v-if="form.errors.email" class="text-red-500 text-sm">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-semibold"
                        >Mot de passe</label
                    >
                    <input
                        v-model="form.password"
                        type="password"
                        id="password"
                        class="w-full border-gray-300 rounded px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label
                        for="password_confirmation"
                        class="block font-semibold"
                        >Confirmer le mot de passe</label
                    >
                    <input
                        v-model="form.password_confirmation"
                        type="password"
                        id="password_confirmation"
                        class="w-full border-gray-300 rounded px-3 py-2"
                    />
                </div>

                <div class="mb-4">
                    <label for="role" class="block font-semibold">Rôle</label>
                    <select
                        v-model="form.role"
                        id="role"
                        class="w-full border-gray-300 rounded px-3 py-2"
                    >
                        <option value="">Sélectionner un rôle</option>
                        <option value="admin">Admin</option>
                        <option value="technicien">Technicien</option>
                        <option value="client">Client</option>
                    </select>
                    <div v-if="form.errors.role" class="text-red-500 text-sm">
                        {{ form.errors.role }}
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="rounded border-gray-300"
                        />
                        <span class="ml-2">Actif</span>
                    </label>
                </div>

                <div>
                    <button
                        type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                        :disabled="form.processing"
                    >
                        Ajouter
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
