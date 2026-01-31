<?php

namespace App\Services\Tickets;

use App\Repositories\TicketRepository;

class EditService
{
    private string $component = 'Tickets/Edit';
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Summary of edit
     * @param mixed $model
     * @return array{component: string, props: array{projects: \Illuminate\Support\Collection}}
     */
    public function edit($model): array
    {
        return [
            'component' => $this->component,
            'props' => [
                'ticket'   => $this->ticketRepository->getTicketWithDetail($model),
                'projects' => $this->ticketRepository->getProjectsForSelect()
            ]
        ];
    }
}