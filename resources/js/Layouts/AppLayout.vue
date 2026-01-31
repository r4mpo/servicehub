<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import ApplicationMark from '@/Components/ApplicationMark.vue'

defineProps({
    title: String,
})

const showingNavigationDropdown = ref(false)

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <div>

        <Head :title="title" />

        <div class="min-h-screen bg-gray-100">
            <!-- Navbar -->
            <nav class="bg-white border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <Link :href="route('dashboard')">
                                <ApplicationMark class="h-9 w-auto" />
                            </Link>
                        </div>

                        <!-- Desktop actions -->
                        <div class="hidden sm:flex sm:items-center gap-4">
                            <span class="text-sm text-gray-600">
                                {{ $page.props.auth.user.name }}
                            </span>

                            <button @click="logout" class="text-sm text-red-600 hover:text-red-800 font-medium">
                                Sair
                            </button>
                        </div>

                        <!-- Mobile menu button -->
                        <div class="flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                                ☰
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div v-if="showingNavigationDropdown" class="sm:hidden border-t border-gray-200">
                    <div class="px-4 py-3 space-y-2">
                        <div class="text-sm text-gray-700">
                            {{ $page.props.auth.user.name }}
                        </div>

                        <button @click="logout" class="block text-sm text-red-600 hover:text-red-800">
                            Sair
                        </button>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>