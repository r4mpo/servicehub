<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tickets = $request->user()->tickets()
            ->with('project.company')
            ->latest()
            ->paginate(10)
            ->through(fn($ticket) => [
                'id' => $ticket->id,
                'title' => $ticket->title,
                'project_name' => $ticket->project->name,
                'company_name' => $ticket->project->company->name,
                'created_at' => $ticket->created_at->format('d/m/Y'),
            ]);

        return Inertia::render('Dashboard', [
            'tickets' => $tickets
        ]);
    }
}