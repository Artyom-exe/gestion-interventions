<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use App\Models\TicketImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function Ramsey\Uuid\v1;
use Illuminate\Support\Facades\Log;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'technicien') {
            $tickets = Tickets::with(['assignedUser', 'images', 'statusHistory'])
                ->where('assigned_to', $user->id)
                ->get();
        } elseif ($user->role === 'admin') {
            $tickets = Tickets::with(['assignedUser', 'images', 'statusHistory'])->get();
        } else {
            abort(403, 'Accès non autorisé.');
        }

        return \Inertia\Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'authUser' => $user, // Ajout de l'utilisateur connecté
            'csrf_token' => csrf_token(), // Ajouter ici
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Bloquer les techniciens
        if (auth()->user()->role === 'technicien') {
            abort(403, 'Vous n’êtes pas autorisé à créer un ticket.');
        }

        $clients = \App\Models\User::where('role', 'client')->get(['id', 'username']);
        $technicians = \App\Models\User::where('role', 'technicien')->get(['id', 'username']);

        return \Inertia\Inertia::render('Tickets/Create', [
            'clients' => $clients,
            'technicians' => $technicians,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $validated = $this->validateRequest($request);

        // Création du ticket
        $ticket = Tickets::create($validated);

        // Vérification et traitement des images
        if ($request->hasFile('images')) {
            $this->saveImages($ticket, $request->file('images'));
        } else {
            Log::info('Aucune image détectée');
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Tickets::with(['client', 'assignedUser', 'images', 'statusHistory'])->findOrFail($id);

        return \Inertia\Inertia::render('Tickets/Detail', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Bloquer les techniciens
        if (auth()->user()->role === 'technicien') {
            abort(403, 'Vous n’êtes pas autorisé à modifier un ticket.');
        }

        $ticket = Tickets::with('images')->findOrFail($id);
        $clients = \App\Models\User::where('role', 'client')->get(['id', 'username']);
        $technicians = \App\Models\User::where('role', 'technicien')->get(['id', 'username']);

        return \Inertia\Inertia::render('Tickets/Edit', [
            'ticket' => $ticket,
            'clients' => $clients,
            'technicians' => $technicians,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ticket = Tickets::findOrFail($id);

        $validated = $this->validateRequest($request);
        $ticket->update($validated);

        // Supprimer les images marquées pour suppression
        if ($request->has('removedImages')) {
            foreach ($request->input('removedImages') as $imageId) {
                $image = TicketImages::findOrFail($imageId);
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }

        // Ajouter de nouvelles images
        if ($request->hasFile('images')) {
            $this->saveImages($ticket, $request->file('images'));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            if (auth()->user()->role === 'technicien') {
                abort(403, 'Vous n’êtes pas autorisé à supprimer un ticket.');
            }

            $ticket = Tickets::with('images')->findOrFail($id);

            foreach ($ticket->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $ticket->images()->delete();
            $ticket->delete();

            return redirect()->route('tickets.index')->with('success', 'Ticket et ses images supprimés avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('tickets.index')->with('error', 'Une erreur s\'est produite lors de la suppression.');
        }
    }



    /**
     * Validate the request for storing or updating a ticket.
     */
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'client_id' => 'required|exists:utilisateurs,id',
            'description' => 'required|string|max:255',
            'priority' => 'required|in:faible,moyenne,haute',
            'status' => 'required|in:ouvert,en_cours,fermé',
            'assigned_to' => 'nullable|exists:utilisateurs,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    }

    /**
     * Save uploaded images to the database and storage.
     */
    private function saveImages(Tickets $ticket, $images)
    {
        foreach ($images as $image) {
            $path = $image->store('ticket_images', 'public');
            $ticket->images()->create(['image_path' => $path]);
        }
    }
}
