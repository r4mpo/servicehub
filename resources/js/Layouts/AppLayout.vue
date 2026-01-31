<script setup>
import { ref, computed } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'
import logoUrl from '@/../../public/images/logo.svg';

defineProps({
    title: String,
})

const showingNavigationDropdown = ref(false)
const page = usePage()

// Computed robusta: Resolve o erro de undefined e garante o fallback
const user = computed(() => {
    return page.props.auth?.user || { name: 'Consultor KPMG' }
})

// Gera as iniciais com segurança (evita erro de split em undefined)
const userInitials = computed(() => {
    const name = user.value.name || 'Consultor KPMG'
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase()
})

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-[#00338D] border-b border-blue-900 shadow-lg">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        
                        <div class="flex items-center">
                            <Link :href="route('dashboard')">
                                <img :src="logoUrl" 
                                     alt="KPMG Logo" 
                                     class="h-8 w-auto brightness-0 invert">
                            </Link>
                        </div>

                        <div class="hidden sm:flex sm:items-center gap-4">
                            <span class="text-sm text-white">
                                Olá, <strong>{{ user.name }}</strong>
                            </span>

                            <div class="w-10 h-10 rounded-full bg-white text-[#00338D] flex items-center justify-center font-bold border-2 border-blue-400">
                                {{ userInitials }}
                            </div>

                            <button @click="logout" class="text-white hover:text-blue-200 ml-2 transition">
                                <i class="fas fa-sign-out-alt"></i>
                            </button>
                        </div>

                        <div class="sm:hidden flex items-center">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="text-white">
                                <i class="fas" :class="showingNavigationDropdown ? 'fa-times' : 'fa-bars'"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div v-show="showingNavigationDropdown" class="sm:hidden bg-[#002a75] px-4 py-4 border-t border-blue-800">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-white text-[#00338D] flex items-center justify-center font-bold">
                            {{ userInitials }}
                        </div>
                        <span class="text-white font-medium">{{ user.name }}</span>
                    </div>
                    <button @click="logout" class="text-red-300 text-sm font-bold flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> SAIR
                    </button>
                </div>
            </nav>

            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main>
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
nav {
    background-color: #00338D; 
}
</style>