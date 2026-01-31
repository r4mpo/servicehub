<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    ticket: Object,
    projects: Array,
});

const fileInput = ref(null);
const fileName = ref("Clique para substituir o arquivo atual");

const form = useForm({
    project_id: props.ticket.project_id,
    title: props.ticket.title,
    description: props.ticket.description,
    attachment: null,
    _method: 'PUT', 
});

const submit = () => {
    // Usamos .post em vez de .put porque o PHP não suporta arquivos em PUT nativo
    form.post(route('tickets.update', props.ticket.id), {
        onSuccess: () => {
            // Feedback opcional
        },
    });
};

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.attachment = file;
        fileName.value = file.name;
    }
};
</script>

<template>
    <Head :title="`Editar Ticket #${ticket.id}`" />

    <AppLayout>
        <div class="container">
            <div class="form-container">
                <div class="breadcrumb">
                    <Link :href="route('dashboard')">Home</Link> / <span>Editar Ticket #{{ ticket.id }}</span>
                </div>
                
                <div class="card" style="max-width: 100%;">
                    <h2 class="mb-20">Editar Ordem de Serviço</h2>
                    <p class="mb-20" style="color: var(--gray-medium);">
                        Atualize as informações do ticket técnico abaixo.
                    </p>
                    
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <label for="project">Projeto Relacionado</label>
                            <select id="project" v-model="form.project_id" class="form-control" required>
                                <option value="">Selecione um projeto...</option>
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.project_id" class="error-msg">{{ form.errors.project_id }}</div>
                        </div>

                        <div class="form-group">
                            <label for="title">Título do Ticket</label>
                            <input type="text" id="title" v-model="form.title" class="form-control" placeholder="Ex: Erro ao processar arquivo JSON" required>
                            <div v-if="form.errors.title" class="error-msg">{{ form.errors.title }}</div>
                        </div>

                        <div class="form-group">
                            <label for="description">Descrição Detalhada</label>
                            <textarea id="description" v-model="form.description" class="form-control" required></textarea>
                            <div v-if="form.errors.description" class="error-msg">{{ form.errors.description }}</div>
                        </div>

                        <div v-if="ticket.detail && ticket.detail.file_path" class="current-file">
                            <i class="fas fa-paperclip"></i>
                            <span>Arquivo atual: <strong>{{ ticket.detail.file_path.split('/').pop() }}</strong></span>
                            <a :href="'/storage/' + ticket.detail.file_path" target="_blank" class="view-link">Visualizar</a>
                        </div>

                        <div class="form-group">
                            <label>Substituir Anexo (Opcional)</label>
                            <div class="file-upload-wrapper" @click="fileInput.click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p id="file-name" :class="{'file-selected': form.attachment}">{{ fileName }}</p>
                                <span style="font-size: 0.8rem; color: var(--gray-medium);">Formatos aceitos: .json, .txt, .pdf</span>
                                <input type="file" ref="fileInput" style="display: none;" @change="handleFileUpload">
                            </div>
                            <div v-if="form.errors.attachment" class="error-msg">{{ form.errors.attachment }}</div>
                        </div>

                        <div class="btn-group">
                            <Link :href="route('dashboard')" class="btn btn-outline">VOLTAR</Link>
                            <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                {{ form.processing ? 'SALVANDO...' : 'ATUALIZAR TICKET' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Reaproveitando seus estilos e adicionando melhorias */
.form-container { max-width: 800px; margin: 40px auto; }
.file-upload-wrapper {
    border: 2px dashed #ddd;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    background: #fdfdfd;
    transition: all 0.3s;
}
.file-upload-wrapper:hover { border-color: #00338d; background: #f0f4f8; }
.file-selected { color: #00338d; font-weight: bold; }
.error-msg { color: #d32f2f; font-size: 0.85rem; margin-top: 5px; }

.current-file {
    background: #f0f7ff;
    border: 1px solid #cce3ff;
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.view-link {
    margin-left: auto;
    color: #00338d;
    font-size: 0.8rem;
    text-transform: uppercase;
    font-weight: bold;
}

/* Garante consistência com os botões do seu CSS global */
.btn-group { display: flex; gap: 15px; justify-content: flex-end; margin-top: 30px; }
</style>