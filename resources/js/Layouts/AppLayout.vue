<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <nav class="w-64 bg-white border-r border-gray-200 shadow-md">
            <!-- Logo -->
            <div class="p-4 border-b">
                <Link :href="route('dashboard')" class="flex items-center space-x-2">
                <ApplicationMark class="block h-10 w-auto" />
                <span class="text-lg font-bold text-gray-800">MonApp</span>
                </Link>
            </div>

            <!-- Navigation Links -->
            <div class="mt-4">
                <ul class="space-y-2">
                    <li>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </NavLink>
                    </li>
                    <li>
                        <NavLink :href="route('tickets.index')" :active="route().current('tickets.index')"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m-3-8h3m-9 0h3" />
                            </svg>
                            <span class="ml-3">Tickets</span>
                        </NavLink>
                    </li>
                    <!-- <li>
                        <NavLink :href="route('clients.index')" :active="route().current('clients.index')"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h11m4 0h3m-7 6h7M3 16h7m0 0v6m0-6l-6 6m6-6l6 6" />
                            </svg>
                            <span class="ml-3">Utilisateurs</span>
                        </NavLink>
                    </li> -->
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="mt-auto p-4 border-t">
                <Dropdown align="left" width="48">
                    <template #trigger>
                        <button
                            class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                            <img v-if="$page.props.auth.user.profile_photo_url"
                                :src="$page.props.auth.user.profile_photo_url" alt="User Avatar"
                                class="w-8 h-8 rounded-full mr-3" />
                            <span>{{ $page.props.auth.user.name }}</span>
                            <svg class="ml-auto w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.show')">
                            Mon Profil
                        </DropdownLink>
                        <form @submit.prevent="logout">
                            <DropdownLink as="button">
                                Déconnexion
                            </DropdownLink>
                        </form>
                    </template>
                </Dropdown>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <slot />
        </main>
    </div>
</template>
