<?php

namespace App\Services\Tickets;

use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Storage;

class DestroyService
{
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Summary of destroy
     * @param mixed $model
     * @return array
     */
    public function destroy($model): array
    {
        $this->deletePhysicalFileIfExists($model);
        $this->ticketRepository->delete($model);

        return [
            'route' => 'dashboard',
            'message' => 'Ticket removido com sucesso!'
        ];
    }

    /**
     * Exclui fisicamente o arquivo associado ao ticket, se existir
     * @param mixed $model
     * @return void
     */
    private function deletePhysicalFileIfExists($model): void
    {
        if ($model->detail && $model->detail->file_path) {
            Storage::disk('public')->delete($model->detail->file_path);
        }
    }
}