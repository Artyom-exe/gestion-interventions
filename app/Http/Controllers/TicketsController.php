<?php

namespace App\Http\Controllers;

use App\Models\Tickets;
use App\Models\TicketImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Tickets::with(['assignedUser', 'images', 'statusHistory'])->get();

        return \Inertia\Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        $validated = $this->validateRequest($request);

        $ticket = Tickets::create($validated);

        if ($request->hasFile('images')) {
            $this->saveImages($ticket, $request->file('images'));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = Tickets::with(['assignedUser', 'images', 'statusHistory'])->findOrFail($id);

        return \Inertia\Inertia::render('Tickets/Detail', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
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
        $ticket = Tickets::with('images')->findOrFail($id);

        foreach ($ticket->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $ticket->images()->delete();
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket et ses images supprimés avec succès.');
    }

    /**
     * Delete a specific image from a ticket.
     */
    public function deleteImage($ticketId, $imageId)
    {
        $image = TicketImages::where('ticket_id', $ticketId)->findOrFail($imageId);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()->back()->with('success', 'Image supprimée avec succès.');
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
