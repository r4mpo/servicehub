<?php

namespace App\Helpers;

use App\Jobs\ProcessTicketNotification;

class MailsHelpers
{
    /**
     * Dispara o job para processar a notificação do ticket
     * @param mixed $model
     * @param mixed $user
     * @param mixed $actionType
     * @return void
     */
    public static function processTicketNotification($model, $user, $actionType): void
    {
        ProcessTicketNotification::dispatch($model, $user, $actionType);
    }
}