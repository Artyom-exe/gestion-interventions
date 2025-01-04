<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UsersController extends Controller
{

/**
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        $users = User::all(); // Récupère tous les utilisateurs
        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }


     /**
     * Affiche le formulaire d'ajout d'utilisateur.
     */
    public function create()
    {
        return Inertia::render('Users/AddUser');
    }


/**
 * Traite le formulaire d'ajout d'utilisateur.
 */
public function store(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:utilisateurs',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:admin,technicien,client',
        'is_active' => 'required|boolean',
    ]);

    User::create([
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'],
        'is_active' => $validated['is_active'],
    ]);

    return redirect()->route('users.index')->with('success', 'Utilisateur ajouté avec succès.');
}


 /**
     * Affiche le formulaire d'édition d'un utilisateur existant.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }


    /**
     * Met à jour les informations de profil de l'utilisateur connecté.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:utilisateurs,email,' . $user->id,
            'role' => 'required|in:admin,technicien,client',
            'is_active' => 'required|boolean',
        ]);

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }


    /**
     * Supprime le compte de l'utilisateur connecté.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé avec succès.');
    }
}
