<template>
    <AppLayout title="Tableau de bord">
        <div class="container mx-auto p-6">

            <!-- Statistiques -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <StatCard title="Total des tickets" :value="stats.total_tickets" />
                <StatCard title="Tickets en cours" :value="stats.in_progress_tickets" />
                <StatCard title="Tickets résolus" :value="stats.closed_tickets" />
            </div>

            <!-- Graphiques et Activités récentes -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="col-span-2 bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-4">Évolution des tickets</h2>
                    <canvas id="ticketsChart"></canvas>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h2 class="text-lg font-bold mb-4">Activité récente</h2>
                    <ul>
                        <li
                            v-for="activity in recentActivities"
                            :key="activity.id"
                            class="border-b last:border-b-0 py-2"
                        >
                            <p><strong>{{ activity.description }}</strong></p>
                            <p class="text-sm text-gray-500">
                                Créé le {{ new Date(activity.created_at).toLocaleString('fr-FR') }}
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tickets récents -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-bold mb-4">Tickets récents</h2>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Client
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Statut
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="ticket in recentTickets" :key="ticket.id">
                            <td class="px-6 py-4 whitespace-nowrap">{{ ticket.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ ticket.description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ ticket.client?.username || 'Non défini' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800': ticket.status === 'ouvert',
                                        'bg-yellow-100 text-yellow-800': ticket.status === 'en_cours',
                                        'bg-red-100 text-red-800': ticket.status === 'fermé',
                                    }"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                >
                                    {{ ticket.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ new Date(ticket.created_at).toLocaleDateString('fr-FR') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { onMounted } from "vue";
import Chart from "chart.js/auto";
import AppLayout from "@/Layouts/AppLayout.vue";
import StatCard from "@/Components/StatCard.vue";

// Props
const props = defineProps({
    stats: Object,
    recentActivities: Array,
    recentTickets: Array,
    ticketTrends: Object,
});

// Initialisation du graphique
onMounted(() => {
    const ctx = document.getElementById("ticketsChart").getContext("2d");
    new Chart(ctx, {
        type: "line",
        data: {
            labels: Object.keys(props.ticketTrends).map(
                (month) =>
                    [
                        "Jan",
                        "Fév",
                        "Mar",
                        "Avr",
                        "Mai",
                        "Juin",
                        "Juil",
                        "Août",
                        "Sep",
                        "Oct",
                        "Nov",
                        "Déc",
                    ][month - 1]
            ),
            datasets: [
                {
                    label: "Tickets créés",
                    data: Object.values(props.ticketTrends),
                    borderColor: "rgba(54, 162, 235, 1)",
                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                    tension: 0.4,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: "top" },
                title: { display: true, text: "Évolution des tickets" },
            },
        },
    });
});
</script>

