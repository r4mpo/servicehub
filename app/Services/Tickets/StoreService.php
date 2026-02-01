<?php

namespace App\Services\Tickets;

use App\Helpers\MailsHelpers;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Storage;

class StoreService
{
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Summary of store
     * @param mixed $request
     * @return array
     */
    public function store($request): array
    {
        $ticket = $this->ticketRepository->createTicket($request);
        $filePath = $this->storeAttachment($request);
        $this->ticketRepository->createDetail($ticket, $request, $filePath);

        // Dispara o job para processar a notificação do ticket
        MailsHelpers::processTicketNotification($ticket, $request->user(), 'Criado');

        return [
            'route' => 'dashboard',
            'message' => 'Ticket criado com sucesso!'
        ];
    }

    /**
     * Cria o anexo do ticket se existir no request
     * @param mixed $request
     * @return string|null
     */
    private function storeAttachment($request): string|null
    {
        if ($request->hasFile('attachment')) {
            return $request->file('attachment')->store('attachments', 'public');
        }

        return null;
    }
}