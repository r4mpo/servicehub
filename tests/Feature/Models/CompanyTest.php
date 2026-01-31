<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Company;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o relacionamento 1:N entre Company e Project.
     * Verifica se uma empresa consegue recuperar corretamente seus 3 projetos associados.
     */
    public function test_a_company_can_have_many_projects()
    {
        $company = Company::factory()
            ->has(Project::factory()->count(3))
            ->create();

        $this->assertCount(3, $company->projects);
        $this->assertInstanceOf(Project::class, $company->projects->first());
    }
}