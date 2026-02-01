<?php

namespace Tests\Unit\Services\Tickets;

use Tests\TestCase;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Services\Tickets\UpdateService;
use App\Repositories\TicketRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_service_replaces_file_and_updates_data()
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
        $ticket = Ticket::factory()->create(['user_id' => $user->id]);
        
        // Simulamos um arquivo antigo já existente
        $oldFileRelativePath = 'attachments/antigo.txt';
        Storage::disk('public')->put($oldFileRelativePath, 'conteudo antigo');
        
        TicketDetail::factory()->create([
            'ticket_id' => $ticket->id,
            'file_path' => $oldFileRelativePath
        ]);

        // Novo arquivo para o Update
        $newFile = UploadedFile::fake()->create('novo_documento.pdf', 500);

        $request = new Request([
            'project_id'  => $ticket->project_id,
            'title'       => 'Título Atualizado KPMG',
            'description' => 'Nova descrição do problema',
        ]);

        $request->files->set('attachment', $newFile);
        $request->setUserResolver(fn() => $user);

        // 2. Execução
        $service = new UpdateService(new TicketRepository());
        $result = $service->update($ticket, $request);

        // 3. Asserções
        $this->assertEquals('dashboard', $result['route']);
        
        // Verifica se os dados básicos mudaram no banco
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'title' => 'Título Atualizado KPMG'
        ]);

        // Verifica se o NOVO arquivo existe
        Storage::disk('public')->assertExists('attachments/' . $newFile->hashName());
        
        // Verifica se o ANTIGO arquivo foi deletado (Limpeza de disco)
        Storage::disk('public')->assertMissing($oldFileRelativePath);

        // Verifica se o banco aponta para o novo caminho
        $this->assertDatabaseHas('ticket_details', [
            'ticket_id' => $ticket->id,
            'file_path' => 'attachments/' . $newFile->hashName()
        ]);
    }
}