<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Ticket;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o relacionamento inverso (BelongsTo).
     * Garante que um projeto está vinculado a uma empresa existente.
     */
    public function test_a_project_belongs_to_a_company()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(Company::class, $project->company);
    }

    /**
     * Testa o relacionamento 1:N entre Project e Ticket.
     * Garante que múltiplos tickets podem ser abertos sob o mesmo projeto.
     */
    public function test_a_project_can_have_many_tickets()
    {
        $project = Project::factory()
            ->has(Ticket::factory()->count(5))
            ->create();

        $this->assertCount(5, $project->tickets);
    }
}