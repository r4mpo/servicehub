<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, router } from '@inertiajs/vue3'
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

                    <Link href="#" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Ticket
                    </Link>
                </div>

                <!-- Tickets -->
                <div class="card">
                    <div class="card-header">
                        <h3>Tickets Recentes</h3>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Projeto</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th style="width: 120px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#TK-1092</td>
                                <td>Erro na integração de API</td>
                                <td>Projeto Alpha</td>
                                <td>30/01/2026</td>
                                <td>
                                    <span class="status-badge status-open">Aberto</span>
                                </td>
                                <td class="actions">
                                    <i class="fas fa-eye action view"></i>
                                    <i class="fas fa-pen action edit"></i>
                                    <i class="fas fa-trash action delete"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>#TK-1091</td>
                                <td>Ajuste de layout mobile</td>
                                <td>ServiceHub UI</td>
                                <td>29/01/2026</td>
                                <td>
                                    <span class="status-badge status-progress">Em fila</span>
                                </td>
                                <td class="actions">
                                    <i class="fas fa-eye action view"></i>
                                    <i class="fas fa-pen action edit"></i>
                                    <i class="fas fa-trash action delete"></i>
                                </td>
                            </tr>

                            <tr>
                                <td>#TK-1088</td>
                                <td>Relatório mensal de vendas</td>
                                <td>KPMG Analytics</td>
                                <td>28/01/2026</td>
                                <td>
                                    <span class="status-badge status-closed">Concluído</span>
                                </td>
                                <td class="actions">
                                    <i class="fas fa-eye action view"></i>
                                    <i class="fas fa-pen action edit"></i>
                                    <i class="fas fa-trash action delete"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </AppLayout>
</template>