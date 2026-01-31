<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, router } from '@inertiajs/vue3'

defineProps({
    tickets: Object
});

const deleteTicket = (id) => {
    if (confirm('Tem certeza que deseja excluir este ticket?')) {
        router.delete(route('tickets.destroy', id));
    }
};
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="dashboard-container">
            <aside class="sidebar">
                <ul class="sidebar-menu">
                    <li class="active">
                        <i class="fas fa-chart-line"></i>
                        Dashboard
                    </li>
                </ul>
            </aside>

            <main class="main-content">
                <div class="page-header">
                    <h1>Visão Geral do ServiceHub</h1>

                    <Link :href="route('tickets.create')" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Novo Ticket
                    </Link>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>Tickets Recentes</h3>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Empresa / Projeto</th>
                                <th>Data</th>
                                <th style="width: 120px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="ticket in tickets.data" :key="ticket.id">
                                <td>#TK-{{ ticket.id }}</td>
                                <td>{{ ticket.title }}</td>
                                <td>
                                    <span style="font-weight: 600;">{{ ticket.company_name }}</span>
                                    <br>
                                    <small style="color: var(--gray-medium);">{{ ticket.project_name }}</small>
                                </td>
                                <td>{{ ticket.created_at }}</td>
                                <td class="actions">
                                    <Link :href="route('tickets.edit', ticket.id)">
                                        <i class="fas fa-pen action edit" title="Editar"></i>
                                    </Link>

                                    <button @click="deleteTicket(ticket.id)" class="bg-transparent border-none p-0">
                                        <i class="fas fa-trash action delete" title="Excluir"></i>
                                    </button>
                                </td>
                            </tr>

                            <tr v-if="tickets.length === 0">
                                <td colspan="6" style="text-align: center; padding: 40px; color: var(--gray-medium);">
                                    Nenhum ticket encontrado. Clique em "Novo Ticket" para começar.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination-container mt-4 flex justify-between items-center p-4">
                        <div class="text-sm text-gray-600">
                            Mostrando de {{ tickets.from }} até {{ tickets.to }} de {{ tickets.total }} tickets
                        </div>

                        <div class="flex gap-2">
                            <Component :is="link.url ? Link : 'span'" v-for="link in tickets.links" :key="link.label"
                                :href="link.url" v-html="link.label"
                                class="px-3 py-1 border rounded-md text-sm transition-colors" :class="{
                                    'bg-blue-700 text-white border-blue-700': link.active,
                                    'text-gray-400 border-gray-200 cursor-not-allowed': !link.url,
                                    'hover:bg-gray-100': link.url && !link.active
                                }" />
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </AppLayout>
</template>

<style scoped>
.pagination-container {
    border-top: 1px solid var(--border-color);
}

.pagination-container a,
.pagination-container span {
    min-width: 35px;
    text-align: center;
    display: inline-block;
}

:deep(.pagination-container span),
:deep(.pagination-container a) {
    text-decoration: none;
}
</style>