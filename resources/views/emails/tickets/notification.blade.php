@component('mail::message')
# Olá, {{ $ticket->user->name }}

Informamos que o seu ticket **#{{ $ticket->id }}** foi **{{ $actionType }}** com sucesso no sistema.

**Detalhes do Ticket:**
* **Título:** {{ $ticket->title }}
* **Projeto:** {{ $ticket->project->name }}

@if($ticket->detail && $ticket->detail->file_path)
@component('mail::panel')
📎 **Anexo Identificado:** O arquivo foi processado e armazenado com segurança em nossos servidores técnicos.
@endcomponent
@endif

@component('mail::button', ['url' => route('dashboard')])
Visualizar no Dashboard
@endcomponent

Atenciosamente,<br>
**Equipe de Suporte Técnico KPMG**
@endcomponent