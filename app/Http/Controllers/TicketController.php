<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tickets\StoreRequest;
use App\Http\Requests\Tickets\UpdateRequest;
use Inertia\Response;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\Tickets\CreateService;
use App\Services\Tickets\EditService;
use App\Services\Tickets\DestroyService;
use App\Services\Tickets\StoreService;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TicketController extends Controller
{
    private array $responseData;
    private CreateService $createService;
    private EditService $editService;
    private DestroyService $destroyService;
    private StoreService $storeService;

    public function __construct(CreateService $createService, EditService $editService, DestroyService $destroyService, StoreService $storeService)
    {
        parent::__construct();
        $this->createService = $createService;
        $this->editService = $editService;
        $this->destroyService = $destroyService;
        $this->storeService = $storeService;
    }

    public function create(): Response
    {
        $this->responseData = $this->createService->create();
        return $this->responseInertia($this->responseData);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->responseData = $this->storeService->store($request);
        return $this->responseRedirect($this->responseData);
    }

    public function edit(Ticket $ticket): Response
    {
        $this->responseData = $this->editService->edit($ticket);
        return $this->responseInertia($this->responseData);
    }

    public function update(UpdateRequest $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
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
                        'browser' => $request->header('User-Agent'),
                        'priority' => 'updated',
                        'ip_address' => $request->ip(),
                    ]
                ]
            );
        }

        return redirect()->route('dashboard')->with('message', 'Ticket atualizado!');
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->responseData = $this->destroyService->destroy($ticket);
        return $this->responseRedirect($this->responseData);
    }
}