<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Models\User;
use App\Mail\TicketNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessTicketNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected Ticket $ticket,
        protected User $user,
        protected string $actionType
    ) {}

    public function handle()
    {
        // Só envia e-mail se houver anexo (conforme seu requisito)
        if ($this->ticket->detail?->file_path) {
            Mail::to($this->user->email)->send(
                new TicketNotificationMail($this->ticket, $this->actionType)
            );
        }
    }
}