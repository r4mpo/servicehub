<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TicketRepository extends RepositoryDefault
{
    /**
     * Retorna a lista de tickets paginada para o dashboard do usuário autenticado
     * @param mixed $request
     * @return \Illuminate\Pagination\LengthAwarePaginator<int, array{id: int, title: string, project_name: string, company_name: string, created_at: string}>
     */
    public function dashboard($request): LengthAwarePaginator
    {
        return $request->user()->tickets()
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
    }

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

    /**
     * Cria um novo ticket associado ao usuário autenticado
     * @param mixed $request
     * @return mixed
     */
    public function createTicket($request): mixed
    {
        return $request->user()->tickets()->create($request->all());
    }

    /**
     * Cria o detailhe do ticket
     * @param mixed $model
     * @param mixed $request
     * @param string|null $filePath
     * @return void
     */
    public function createDetail($model, $request, $filePath): void
    {
        $model->detail()->create([
            'content' => [
                'browser' => $request->header('User-Agent'),
                'priority' => 'medium',
                'ip_address' => $request->ip(),
            ],
            'file_path' => $filePath
        ]);

    }

    /**
     * Atualiza ou cria o detailhe do ticket
     * @param mixed $model
     * @param mixed $request
     * @param string|null $filePath
     * @return void
     */
    public function createOrUpdateDetail($model, $request, $filePath): void
    {
        $model->detail()->updateOrCreate(
            ['ticket_id' => $model->id],
            [
                'file_path' => $filePath,
                'content' => [
                    'browser' => $request->header('User-Agent'),
                    'priority' => 'updated',
                    'ip_address' => $request->ip(),
                ]
            ]
        );
    }
}