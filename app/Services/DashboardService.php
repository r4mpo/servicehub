<?php

namespace App\Services;

use App\Repositories\TicketRepository;

class DashboardService
{
    private string $component = 'Dashboard';
    private TicketRepository $ticketRepository;

    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Summary of dashboard
     * @return array{component: string, props: array{projects: \Illuminate\Support\Collection}}
     */
    public function dashboard($request): array
    {
        return [
            'component' => $this->component,
            'props' => [
                'tickets' => $this->ticketRepository->dashboard($request)
            ]
        ];
    }
}