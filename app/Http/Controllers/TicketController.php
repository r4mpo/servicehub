<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Ticket;
use App\Models\Project;
use App\Models\TicketDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    // Padronização para Create e Edit
    private function getProjectsForSelect()
    {
        return Project::with('company')->get()->map(fn($p) => [
            'id' => $p->id,
            'name' => "Projeto: {$p->name} / Empresa: {$p->company->name}"
        ]);
    }

    public function create()
    {
        return Inertia::render('Tickets/Create', [
            'projects' => $this->getProjectsForSelect()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title'      => 'required|max:255',
            'description'=> 'required',
            'attachment' => 'nullable|file|mimes:json,txt,pdf|max:2048',
        ]);

        // 1. Criar o Ticket (Master)
        // Definimos um status padrão já que está no seu fillable
        $ticket = $request->user()->tickets()->create(array_merge($validated, ['status' => 'Aberto']));

        // 2. Preparar dados do Detail
        $filePath = null;
        if ($request->hasFile('attachment')) {
            $filePath = $request->file('attachment')->store('attachments', 'public');
        }

        // 3. Criar o Detail (HasOne)
        $ticket->detail()->create([
            'content' => [
                'browser'    => $request->header('User-Agent'),
                'priority'   => 'medium',
                'ip_address' => $request->ip(),
            ],
            'file_path' => $filePath
        ]);

        return redirect()->route('dashboard')->with('message', 'Ticket criado com sucesso!');
    }

    public function edit(Ticket $ticket)
    {
        // Eager load do detail para pegar o arquivo existente
        $ticket->load('detail');

        return Inertia::render('Tickets/Edit', [
            'ticket'   => $ticket,
            'projects' => $this->getProjectsForSelect()
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title'      => 'required|string|max:255',
            'description'=> 'required|string',
            'attachment' => 'nullable|file|max:2048',
        ]);

        // 1. Atualizar Master
        $ticket->update($validated);

        // 2. Lógica de Anexo no Detail
        if ($request->hasFile('attachment')) {
            // Se já existe um detail com arquivo, deleta o físico
            if ($ticket->detail && $ticket->detail->file_path) {
                Storage::disk('public')->delete($ticket->detail->file_path);
            }

            $newPath = $request->file('attachment')->store('attachments', 'public');

            // UpdateOrCreate: garante que o detail exista e atualiza o arquivo
            $ticket->detail()->updateOrCreate(
                ['ticket_id' => $ticket->id],
                [
                    'file_path' => $newPath,
                    'content' => [
                        'browser'    => $request->header('User-Agent'),
                        'priority'   => 'updated',
                        'ip_address' => $request->ip(),
                    ]
                ]
            );
        }

        return redirect()->route('dashboard')->with('message', 'Ticket atualizado!');
    }

    public function destroy(Ticket $ticket)
    {
        // Deletar arquivo físico se existir no detail
        if ($ticket->detail && $ticket->detail->file_path) {
            Storage::disk('public')->delete($ticket->detail->file_path);
        }
        
        $ticket->delete(); // O detail será deletado se houver cascade no banco
        return redirect()->route('dashboard')->with('message', 'Ticket removido!');
    }
}