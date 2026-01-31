<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create()
    {
        $projects = Project::with('company')
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => 'Projeto: ' . $project->name . ' / Empresa: ' . $project->company->name
                ];
            });

        return Inertia::render('Tickets/Create', [
            'projects' => $projects
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|max:255',
            'description' => 'required',
            'attachment' => 'nullable|file|mimes:json,txt,pdf|max:2048',
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('tickets', 'public');
        }

        $request->user()->tickets()->create($validated);
        return redirect()->route('dashboard')->with('message', 'Ticket criado com sucesso!');
    }
}