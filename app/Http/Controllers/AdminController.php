<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use App\Models\Interventions;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Affiche le tableau de bord de l'administrateur.
     */
    public function dashboard()
    {
        // Vérification si l'utilisateur est un administrateur
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès refusé.');
        }

        // Statistiques pour les cartes
        $stats = [
            'total_tickets' => Tickets::count(),
            'in_progress_tickets' => Tickets::where('status', 'en_cours')->count(),
            'closed_tickets' => Tickets::where('status', 'fermé')->count(),
        ];

        // Activités récentes (10 derniers tickets)
        $recentActivities = Tickets::latest()
            ->take(10)
            ->get(['id', 'description', 'created_at', 'status']);

        // Données pour les tickets récents
        $recentTickets = Tickets::with('client')
            ->latest()
            ->take(10)
            ->get();

        // Évolution des tickets (par mois)
        $ticketTrends = Tickets::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month');

        return inertia('Admin/Dashboard', [
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'recentTickets' => $recentTickets,
            'ticketTrends' => $ticketTrends,
        ]);
    }
}
