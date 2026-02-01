<?php

namespace Tests\Unit\Services\Tickets;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Tickets\StoreService;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_service_logic_independently()
    {
        // 1. Preparação
        Storage::fake('public');

        // Deixamos a fila síncrona para facilitar o teste
        config(['queue.default' => 'sync']);

        // Configuração do mail para usar Mailpit
        config(['mail.default' => 'smtp']);
        config(['mail.mailers.smtp.host' => 'mailpit']);
        config(['mail.mailers.smtp.port' => 1025]);

        $user = User::factory()->create();
        $project = Project::factory()->create();
        $file = UploadedFile::fake()->create('anexo.txt', 100);

        // Criamos uma Request "falsa" manualmente
        $request = new Request([
            'project_id' => $project->id,
            'title' => 'Título via Service',
            'description' => 'Conteúdo do ticket',
        ]);

        // Injetamos o arquivo e o usuário na Request
        $request->files->set('attachment', $file);
        $request->setUserResolver(fn() => $user);

        // 2. Execução
        // Injetamos o repositório real (ou um mock, se preferir)
        $service = new StoreService(new TicketRepository());
        $result = $service->store($request);

        // 3. Asserções
        $this->assertEquals('dashboard', $result['route']);
        $this->assertDatabaseHas('tickets', ['title' => 'Título via Service']);
        Storage::disk('public')->assertExists('attachments/' . $file->hashName());
    }
}