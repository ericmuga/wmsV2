<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const sidebarOpen = ref(true); // Toggle sidebar collapse/expand
const openMenu = ref(null); // Track which menu is open (for submenus)

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const toggleMenu = (menu) => {
    openMenu.value = openMenu.value === menu ? null : menu;
};

// Get authenticated user
const { props } = usePage();
const authUser = props.auth.user;
</script>

<template>
    <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside :class="{'w-64': sidebarOpen, 'w-16': !sidebarOpen}" class="transition-all duration-300 bg-white shadow-md dark:bg-gray-800">
            <div class="flex items-center justify-center h-16 border-b border-gray-100 dark:border-gray-700">
                <Link :href="route('dashboard')">
                    <ApplicationLogo :class="{'block h-9 w-auto fill-current text-gray-800 dark:text-gray-200': sidebarOpen, 'hidden': !sidebarOpen}" />
                </Link>
            </div>

            <button @click="toggleSidebar" class="w-full px-4 py-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none">
                <svg v-if="!sidebarOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mx-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mx-auto">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Sidebar Menu -->
            <nav class="mt-6 space-y-2">
                <!-- Menu Item with Submenu -->
                <div>
                    <button @click="toggleMenu('dashboard')" class="flex items-center justify-between w-full px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span v-if="sidebarOpen">Production</span>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- Submenu -->
                    <div v-if="openMenu === 'dashboard'" class="flex flex-col pl-4" >
                        <NavLink :href="route('lairage')" :active="route().current('lairage')" class="block px-2 py-1 text-gray-500">
                            Lairage
                        </NavLink>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="block px-2 py-1 text-gray-500">
                            Slaughter
                        </NavLink>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="block px-2 py-1 text-gray-500">
                            Butchery
                        </NavLink>
                        <NavLink :href="route('profile.edit')" class="block px-2 py-1 text-gray-500">
                           Sausage
                        </NavLink>
                         <NavLink :href="route('profile.edit')" class="block px-2 py-1 text-gray-500">

                        </NavLink>
                    </div>
                </div>

                 <div>
                    <button @click="toggleMenu('admin')" class="flex items-center justify-between w-full px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span v-if="sidebarOpen">Admin</span>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- Submenu -->
                    <div v-if="openMenu === 'admin'" class="flex flex-col pl-4" >
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="block px-2 py-1 text-gray-500">
                            Items
                        </NavLink>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="block px-2 py-1 text-gray-500">
                            Locations
                        </NavLink>
                        <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="block px-2 py-1 text-gray-500">
                            Vendors
                        </NavLink>

                    </div>
                </div>

                <!-- Another Menu Item -->
                <div>
                    <button @click="toggleMenu('settings')" class="flex items-center justify-between w-full px-4 py-2 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <span v-if="sidebarOpen">{{authUser.name}}</span>
                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- Submenu -->
                    <div v-if="openMenu === 'settings'" class="flex flex-col pl-4">
                        <NavLink :href="route('profile.edit')" class="block px-2 py-1 text-gray-500">
                            Profile
                        </NavLink>
                        <NavLink :href="route('logout')" method="post" as="button" class="block px-2 py-1 text-gray-500">
                            Log Out
                        </NavLink>
                    </div>
                </div>
            </nav>

            <!-- User Info & Logout -->
            <!-- <div class="px-4 py-6 mt-auto">
                <div class="text-center text-gray-600 dark:text-gray-400" v-if="sidebarOpen">
                    <div class="text-base font-medium">{{ authUser.name }}</div>
                    <div class="text-sm">{{ authUser.email }}</div>
                </div>
            </div> -->
        </aside>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Page Heading -->
            <header class="bg-white shadow dark:bg-gray-800" v-if="$slots.header">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
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

<style scoped>
/* Sidebar transition */
aside {
    height: 100vh; /* Full height */
    transition: width 0.3s ease;
}

.flex-1 {
    padding-left: 16px;
}
</style>
