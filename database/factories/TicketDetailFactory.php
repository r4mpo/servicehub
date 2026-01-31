<?php

namespace Database\Factories;

use App\Models\TicketDetail;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketDetailFactory extends Factory
{
    protected $model = TicketDetail::class;

    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'content' => [
                'browser' => $this->faker->userAgent(),
                'ip_address' => $this->faker->ipv4(),
                'priority' => 'medium'
            ],
            'file_path' => 'attachments/' . $this->faker->uuid() . '.json',
        ];
    }
}