<?php

namespace App\Services\Tickets;

use App\Helpers\MailsHelpers;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Storage;

class updateService
{
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Summary of update
     * @param mixed $model
     * @param mixed $request
     * @return array
     */
    public function update($model, $request): array
    {
        $this->ticketRepository->update($model, $request->all());
        $this->updateAttachment($model, $request);

        // Dispara o job para processar a notificação do ticket
        MailsHelpers::processTicketNotification($model, $request->user(), 'Atualizado');

        return ['route' => 'dashboard', 'message' => 'Ticket atualizado com sucesso'];
    }

    /**
     * Atualiza o anexo do ticket se existir no request
     * @param mixed $model
     * @param mixed $request
     * @return void
     */
    private function updateAttachment($model, $request): void
    {
        if ($request->hasFile('attachment')) {
            if ($model->detail && $model->detail->file_path) {
                Storage::disk('public')->delete($model->detail->file_path);
            }

            $newPath = $request->file('attachment')->store('attachments', 'public');
            $this->ticketRepository->createOrUpdateDetail($model, $request, $newPath);
        }
    }
}