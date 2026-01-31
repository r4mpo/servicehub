<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Ticket;
use App\Services\Tickets\EditService;
use App\Services\Tickets\StoreService;
use App\Services\Tickets\updateService;
use App\Services\Tickets\CreateService;
use App\Services\Tickets\DestroyService;
use App\Http\Requests\Tickets\StoreRequest;
use App\Http\Requests\Tickets\UpdateRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TicketController extends Controller
{
    private array $responseData;
    private CreateService $createService;
    private EditService $editService;
    private DestroyService $destroyService;
    private StoreService $storeService;
    private updateService $updateService;

    public function __construct(CreateService $createService, EditService $editService, DestroyService $destroyService, StoreService $storeService, updateService $updateService)
    {
        parent::__construct();
        $this->editService = $editService;
        $this->storeService = $storeService;
        $this->updateService = $updateService;
        $this->createService = $createService;
        $this->destroyService = $destroyService;
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

    public function update(UpdateRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->responseData = $this->updateService->update($ticket, $request);
        return $this->responseRedirect($this->responseData);
    }

    public function destroy(Ticket $ticket): RedirectResponse
    {
        $this->responseData = $this->destroyService->destroy($ticket);
        return $this->responseRedirect($this->responseData);
    }
}