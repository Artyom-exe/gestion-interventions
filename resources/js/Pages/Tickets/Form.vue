<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">{{ formTitle }}</h1>
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium">Description</label>
                <input
                    id="description"
                    v-model="form.description"
                    type="text"
                    class="mt-1 block w-full border rounded px-4 py-2"
                    required
                />
            </div>
            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium">Priorité</label>
                <select
                    id="priority"
                    v-model="form.priority"
                    class="mt-1 block w-full border rounded px-4 py-2"
                >
                    <option value="faible">Faible</option>
                    <option value="moyenne">Moyenne</option>
                    <option value="haute">Haute</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium">Statut</label>
                <select
                    id="status"
                    v-model="form.status"
                    class="mt-1 block w-full border rounded px-4 py-2"
                >
                    <option value="ouvert">Ouvert</option>
                    <option value="en_cours">En cours</option>
                    <option value="fermé">Fermé</option>
                </select>
            </div>
            <button
                type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded"
            >
                {{ buttonText }}
            </button>
        </form>
    </div>
</template>

<script>
export default {
    props: {
        formTitle: String,
        buttonText: String,
        form: Object,
    },
    methods: {
        submitForm() {
            this.$emit('submit', this.form);
        },
    },
};
</script>
