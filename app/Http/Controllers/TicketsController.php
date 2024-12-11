<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = \App\Models\Tickets::with('assignedUser', 'images', 'statusHistory')->get();

        return \Inertia\Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = \App\Models\User::where('role', 'technicien')->get();

        return \Inertia\Inertia::render('Tickets/Create', [
            'users' => $users, // Liste des techniciens pour assignation
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'status' => 'required|in:ouvert,en_cours,fermé',
            'priority' => 'required|in:faible,moyenne,haute',
            'assigned_to' => 'nullable|exists:utilisateurs,id',
        ]);

        \App\Models\Tickets::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = \App\Models\Tickets::with('assignedUser', 'images', 'statusHistory')->findOrFail($id);

        return \Inertia\Inertia::render('Tickets/Detail', [
            'ticket' => $ticket,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ticket = \App\Models\Tickets::findOrFail($id);
        $users = \App\Models\User::where('role', 'technicien')->get();

        return \Inertia\Inertia::render('Tickets/Edit', [
            'ticket' => $ticket, // Données du ticket à modifier
            'users' => $users,   // Liste des techniciens pour assignation
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'status' => 'required|in:ouvert,en_cours,fermé',
            'priority' => 'required|in:faible,moyenne,haute',
            'assigned_to' => 'nullable|exists:utilisateurs,id',
        ]);

        $ticket = \App\Models\Tickets::findOrFail($id);
        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket mis à jour avec succès.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ticket = \App\Models\Tickets::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket supprimé avec succès.');
    }
}
