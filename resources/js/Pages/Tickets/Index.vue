<template>
    <div>
        <h1 class="text-xl font-bold mb-4">Liste des Tickets</h1>
        <table class="table-auto w-full border">
            <thead>
                <tr>
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Statut</th>
                    <th class="border px-4 py-2">Priorité</th>
                    <th class="border px-4 py-2">Assigné à</th>
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
                        {{ ticket.assignedUser ? ticket.assignedUser.username : 'Non assigné' }}
                    </td>
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
