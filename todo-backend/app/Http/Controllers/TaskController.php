<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // ✅ GET /api/tasks : liste des tâches de l'utilisateur connecté
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return response()->json($tasks);
    }

    // ✅ GET /api/tasks/{id} : détail d'une tâche (si elle appartient à l'utilisateur)
    public function show($id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tâche introuvable'], 404);
        }

        return response()->json($task);
    }

    // ✅ POST /api/tasks : créer une tâche
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:en_attente,en_cours,terminee',
            'priority' => 'required|in:faible,moyenne,elevee',
            'due_date' => 'nullable|date',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $validated['user_id'] = Auth::id();
        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    // ✅ PUT /api/tasks/{id} : modifier une tâche
    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tâche introuvable'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:en_attente,en_cours,terminee',
            'priority' => 'in:faible,moyenne,elevee',
            'due_date' => 'nullable|date',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    // ✅ DELETE /api/tasks/{id} : supprimer une tâche
    public function destroy($id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if (!$task) {
            return response()->json(['message' => 'Tâche introuvable'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tâche supprimée avec succès']);
    }
}
