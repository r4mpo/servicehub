<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o relacionamento 1:1 entre Ticket e TicketDetail.
     * Verifica se a persistência do detalhe técnico está vinculada ao ticket pai.
     */
    public function test_it_has_a_unique_technical_detail()
    {
        $ticket = Ticket::factory()->create();
        TicketDetail::factory()->create(['ticket_id' => $ticket->id]);

        $this->assertInstanceOf(TicketDetail::class, $ticket->detail);
        $this->assertEquals($ticket->id, $ticket->detail->ticket_id);
    }

    /**
     * Verifica as associações de autoria e contexto.
     * O ticket deve possuir obrigatoriamente um autor (User) e um Projeto.
     */
    public function test_it_belongs_to_a_project_and_an_author()
    {
        $ticket = Ticket::factory()->create();

        $this->assertInstanceOf(\App\Models\Project::class, $ticket->project);
        $this->assertInstanceOf(\App\Models\User::class, $ticket->user);
    }
}