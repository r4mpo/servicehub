<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TicketDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Verifica o relacionamento inverso 1:1 entre o detalhe e o ticket.
     * * Este teste garante que, a partir de um registro de detalhe técnico,
     * é possível acessar os dados do ticket pai via Eloquent Relation.
     */
    public function test_it_belongs_to_a_ticket()
    {
        $detail = TicketDetail::factory()->create();
        $this->assertInstanceOf(Ticket::class, $detail->ticket);
    }

    /**
     * Valida o comportamento de casting da coluna 'content'.
     * * O campo 'content' no banco de dados é do tipo JSON. Este teste confirma se 
     * o Model TicketDetail está configurado para converter esse JSON automaticamente 
     * em um array PHP, permitindo acesso direto às chaves.
     */
    public function test_it_stores_content_as_an_array()
    {
        $data = ['processed' => true, 'error' => 'none'];
        $detail = TicketDetail::factory()->create(['content' => $data]);

        $this->assertIsArray($detail->content);
        $this->assertEquals('none', $detail->content['error']);
    }
}