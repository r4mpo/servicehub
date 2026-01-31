<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const fileInput = ref(null);
const fileName = ref("Arraste um arquivo ou clique para selecionar");

const props = defineProps({
    projects: {
        type: Array,
        required: true,
    },
});

const form = useForm({
    project_id: '',
    title: '',
    description: '',
    attachment: null,
});

const submit = () => {
    form.post(route('tickets.store'), {
        onSuccess: () => form.reset(),
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
    <AppLayout title="Novo Ticket">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="form-container bg-white p-6 shadow-xl sm:rounded-lg">
                    <div class="breadcrumb">
                        <Link :href="route('dashboard')">Home</Link> / <span>Novo Ticket</span>
                    </div>

                    <h2 class="text-2xl font-bold mb-4">Criar Nova Ordem de Serviço</h2>
                    <p class="mb-6 text-gray-500">Preencha os detalhes abaixo para abrir um novo ticket técnico.</p>

                    <form @submit.prevent="submit">
                        <div class="form-group mb-4">
                            <label class="block font-medium">Projeto Relacionado</label>
                            <select v-model="form.project_id"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Selecione um projeto...</option>

                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                            <div v-if="form.errors.project_id" class="text-red-600 text-sm">{{ form.errors.project_id }}
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label class="block font-medium">Título do Ticket</label>
                            <input v-model="form.title" type="text"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm"
                                placeholder="Ex: Erro ao processar arquivo JSON" required>
                            <div v-if="form.errors.title" class="text-red-600 text-sm">{{ form.errors.title }}</div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="block font-medium">Descrição Detalhada</label>
                            <textarea v-model="form.description"
                                class="form-control w-full border-gray-300 rounded-md shadow-sm min-h-[120px]"
                                required></textarea>
                            <div v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description
                            }}</div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="block font-medium">Anexo (Opcional)</label>
                            <div class="file-upload-wrapper" @click="fileInput.click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p :class="{ 'font-bold text-blue-700': form.attachment }">{{ fileName }}</p>
                                <input type="file" ref="fileInput" class="hidden" @change="handleFileUpload">
                            </div>
                        </div>

                        <div class="btn-group flex justify-end gap-4 mt-6">
                            <Link :href="route('dashboard')" class="px-4 py-2 border rounded-md hover:bg-gray-100">
                                CANCELAR</Link>
                            <button type="submit" :disabled="form.processing"
                                class="px-4 py-2 bg-blue-700 text-white rounded-md hover:bg-blue-800 disabled:opacity-50">
                                {{ form.processing ? 'ENVIANDO...' : 'CRIAR TICKET' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.form-container {
    max-width: 800px;
    margin: 40px auto;
}

.file-upload-wrapper {
    border: 2px dashed var(--border-color);
    padding: 30px;
    text-align: center;
    cursor: pointer;
    transition: border-color 0.3s;
    background: var(--gray-light);
}

.file-upload-wrapper:hover {
    border-color: var(--kpmg-blue);
}

.file-upload-wrapper i {
    font-size: 2rem;
    color: var(--kpmg-blue);
    margin-bottom: 10px;
}

.file-upload-wrapper input[type="file"] {
    display: none;
}

.breadcrumb {
    margin-bottom: 20px;
    font-size: 0.9rem;
    color: var(--gray-medium);
}

.breadcrumb a {
    color: var(--kpmg-blue);
    text-decoration: none;
}

.btn-group {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}
</style>