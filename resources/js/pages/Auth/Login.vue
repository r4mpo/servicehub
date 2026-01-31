<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import logoUrl from '../../../../public/images/logo.svg';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<<template>
    <div class="login-page">
        <div class="login-card">
            <div class="login-logo">
                <img :src="logoUrl" alt="KPMG Logo"/>
            </div>

            <h2 class="title">ServiceHub</h2>
            <p class="subtitle">Acesse sua conta para gerenciar ordens de serviço.</p>
            <div v-if="status" class="status-message">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope icon"></i>
                        <input id="email" type="email" v-model="form.email" placeholder="seu@email.com" required
                            autofocus autocomplete="username" />
                    </div>
                    <p v-if="form.errors.email" class="error">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock icon"></i>
                        <input id="password" type="password" v-model="form.password" placeholder="••••••••" required
                            autocomplete="current-password" />
                    </div>
                    <p v-if="form.errors.password" class="error">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="form-row">
                    <label class="remember">
                        <input type="checkbox" v-model="form.remember" />
                        Lembrar-me
                    </label>
                </div>

                <button type="submit" class="btn-primary" :disabled="form.processing">
                    Entrar
                </button>
            </form>
        </div>
    </div>
</template>