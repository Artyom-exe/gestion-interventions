<template>
    <div>
        <h1 class="text-2xl font-bold mb-4">Liste des Tickets</h1>
        <div class="flex justify-end mb-4">
            <a href="/tickets/create" class="bg-blue-500 text-white px-4 py-2 rounded">Créer un Ticket</a>
        </div>
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Priorité</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ticket in tickets" :key="ticket.id">
                    <td class="border px-4 py-2">{{ ticket.id }}</td>
                    <td class="border px-4 py-2">{{ ticket.description }}</td>
                    <td class="border px-4 py-2">{{ ticket.status }}</td>
                    <td class="border px-4 py-2">{{ ticket.priority }}</td>
                    <td class="border px-4 py-2">
                        <a :href="`/tickets/${ticket.id}`" class="text-blue-500 underline">Voir</a>
                        <a :href="`/tickets/${ticket.id}/edit`" class="text-green-500 underline ml-2">Modifier</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        tickets: Array,
    },
};
</script>
