# 🚀 ServiceHub

Aplicação **monolítica** desenvolvida em **Laravel** com **Vue.js + Inertia.js**, seguindo rigorosamente os princípios do **SOLID**.  
O projeto contempla **autenticação**, **processamento assíncrono**, **envio de e-mails em fila**, **upload de anexos** e ambiente totalmente **Dockerizado**, com suporte à visualização de e-mails via **MailHog**.

---

## 🧱 Stack & Tecnologias

- **Back-end:** Laravel
- **Front-end:** Vue.js + Inertia.js
- **Autenticação:** Laravel Jetstream
- **Banco de Dados:** MySQL
- **Cache & Filas:** Redis
- **Jobs & Queues:** Laravel Queues
- **Busca:** Meilisearch + Laravel Scout
- **Testes:** PHPUnit
- **Ambiente:** Docker (Laravel Sail)
- **E-mails (local):** MailHog
- **E2E Tests:** Selenium

---

## ⚙️ Instalação

### 1️⃣ Clonar o repositório

```bash
git clone https://github.com/r4mpo/servicehub.git
cd servicehub
cp .env.example .env
```

### 2️⃣ Configuração do `.env`

Configuração padrão recomendada para ambiente local:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```

### 3️⃣ Instalação das dependências PHP

```bash
docker run --rm -u "$(id -u):$(id -g)" -v "$(pwd):/var/www/html" -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

### 4️⃣ Alias do Sail (opcional, mas recomendado)

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

### 5️⃣ Subir o ambiente Docker

```bash
sail up -d
sail composer install
sail composer update
sail php artisan key:generate
sail npm install
sail npm update
sail npm run dev
```

📍 Acesse a aplicação em:  
👉 **http://localhost/**

---

## 🌱 Seeders

Para navegar pela aplicação sem necessidade de cadastro manual, utilize as **Seeders**:

```bash
sail php artisan db:seed
```

> 🔑 Usuários seedados utilizam a senha padrão: **password**

---

## 🧪 Testes Automatizados

Os testes automatizados são parte central do projeto e foram desenvolvidos com **PHPUnit**, cobrindo:

- Models
- Services
- Jobs
- Regras de negócio

### Executar todos os testes:

```bash
sail php artisan test
```

### Executar testes específicos:

```bash
sail php artisan test --filter NomeDaClasseTest
```

---

## 🔄 Fluxo da Aplicação

### 1️⃣ Arquitetura

- Arquitetura **Monolítica**
- Princípios **SOLID**, com foco em **Single Responsibility Principle**
- Camadas bem definidas (Controllers, Services, Jobs, Repositories)

### 2️⃣ Modelagem & Relacionamentos

Relacionamentos principais:

- **Company → Projects** (1:N)
- **Project → Tickets** (1:N)
- **Ticket → TicketDetail** (1:1)
- **User → UserProfile** (1:1)

Utilização de **Factories**, **Seeders** e **testes automatizados** para garantir integridade e escalabilidade.

### 3️⃣ Autenticação

Autenticação implementada via **Laravel Jetstream**, integrada ao **Vue.js + Inertia.js**, garantindo segurança e padronização com o ecossistema Laravel.

### 4️⃣ Criação de Tickets

Fluxo totalmente SPA:

1. Formulário em **Vue.js**
2. Envio via **Inertia.js**
3. Validação e persistência no back-end
4. Disparo de **Job assíncrono** para:
   - Processamento de anexos
   - Criação de detalhes do ticket
   - Envio de e-mails em fila
5. Redirecionamento para o dashboard

### 5️⃣ Visualização de E-mails (Local)

Após o processamento das filas, os e-mails enviados podem ser visualizados via **MailHog**:

📬 **http://localhost:8025**

---

## 🐳 Docker & Containers

| Container | Responsabilidade |
|---------|------------------|
| servicehub-laravel.test | Aplicação Laravel |
| servicehub-mysql | Banco de dados MySQL |
| servicehub-redis | Cache, sessões e filas |
| servicehub-worker | Processamento assíncrono |
| servicehub-mailpit | Visualização de e-mails |
| servicehub-meilisearch | Busca e indexação |
| servicehub-selenium | Testes E2E |

---

## 📌 Considerações Finais

Este projeto foi desenvolvido com foco em:

- Código limpo e manutenível
- Boas práticas do ecossistema Laravel
- Escalabilidade e separação de responsabilidades
- Experiência SPA fluida

---

## 🙏 Agradecimentos

**Agradecimento especial à KPMG** pela oportunidade de aprendizado e crescimento profissional.  
Espero ser aprovado e seguir evoluindo cada vez mais 🚀
