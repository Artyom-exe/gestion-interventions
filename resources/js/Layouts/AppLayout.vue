<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <nav class="w-64 bg-white border-r border-gray-200 shadow-lg">
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
                    <li v-if="$page.props.auth.user.role === 'admin'">
                        <NavLink :href="route('admin.dashboard')" :active="route().current('admin.dashboard')"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            <span class="ml-3">Dashboard</span>
                        </NavLink>
                    </li>

                    <li v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'technicien'">
                        <NavLink :href="route('tickets.index')" :active="route().current('tickets.index')"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m-3-8h3m-9 0h3" />
                            </svg>
                            <span class="ml-3">Tickets</span>
                        </NavLink>
                    </li>

                    <li v-if="$page.props.auth.user.role === 'admin'">
                        <NavLink :href="route('users.index')" :active="route().current('users.index')"
                            class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-200 rounded-md transition duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m-3-8h3m-9 0h3" />
                            </svg>
                            <span class="ml-3">Utilisateurs</span>
                        </NavLink>
                    </li>
                </ul>
            </div>

           <!-- User Actions -->
<div class="mt-auto p-4 border-t">
    <!-- Liens d'action -->
    <div class="mt-4">
        <a :href="route('profile.show')"
            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-md transition">
            Mon Profil
        </a>
        <form @submit.prevent="logout" class="mt-1">
            <button type="submit"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-200 rounded-md transition">
                Déconnexion
            </button>
        </form>
    </div>
</div>

        </nav>

        <!-- Main Content -->
        <main class="flex-1 p-6 bg-white shadow-lg rounded-lg">
            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <slot />
        </main>
    </div>
</template>
