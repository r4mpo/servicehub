<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();
        $users = User::all();

        foreach ($projects as $project) {
            // Cria 3 tickets por projeto
            Ticket::factory(3)->create([
                'project_id' => $project->id,
                'user_id' => $users->random()->id,
            ])->each(function ($ticket) {
                // Cria o detalhe técnico para cada ticket
                TicketDetail::factory()->create([
                    'ticket_id' => $ticket->id
                ]);
            });
        }
    }
}