<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketRepository
{

    /**
     * Captura a lógica de obtenção dos projetos para o select (formulários de criação e edição)
     * @return Collection<int, array{id: int, name: string>|Illuminate\Database\Eloquent\Collection<int, array{id: mixed, name: string}>}
     */
    public function getProjectsForSelect(): Collection
    {
        return Project::with('company')->get()->map(fn($p) => [
            'id' => $p->id,
            'name' => "Projeto: {$p->name} / Empresa: {$p->company->name}"
        ]);
    }

    /**
     * Captura a lógica de obtenção do ticket com detail para edição
     * @param \App\Models\Ticket $ticket
     * @return \App\Models\Ticket
     */
    public function getTicketWithDetail(Ticket $ticket): Ticket
    {
        return $ticket->load('detail');
    }
}