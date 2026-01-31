<?php

namespace App\Services\Tickets;

use App\Repositories\TicketRepository;

class CreateService
{
    private string $component = 'Tickets/Create';
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Summary of create
     * @return array{component: string, props: array{projects: \Illuminate\Support\Collection}}
     */
    public function create(): array
    {
        return [
            'component' => $this->component,
            'props' => [
                'projects' => $this->ticketRepository->getProjectsForSelect()
            ]
        ];
    }
}