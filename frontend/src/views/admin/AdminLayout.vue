<script setup lang="ts">
import { ref } from 'vue'
import { RouterView, useRoute, useRouter } from 'vue-router'
import { 
  LayoutDashboard, 
  Package, 
  Store, 
  Tags, 
  Image as ImageIcon, 
  Star, 
  Info, 
  Settings, 
  MessageSquare, 
  TrendingUp,
  Search,
  Bell,
  LogOut,
  Menu,
  X
} from 'lucide-vue-next'

const route = useRoute()
const isSidebarOpen = ref(false) // Ditutup secara default untuk layar HP
const backendStatus = ref('Menghubungkan...')
const isConnected = ref(false)

import { onMounted } from 'vue'

onMounted(async () => {
  try {
    const response = await fetch('http://127.0.0.1:8000/api/ping', {
      method: 'GET',
      headers: { 'Accept': 'application/json' }
    });
    
    if (response.ok) {
      const data = await response.json();
      backendStatus.value = 'Backend Terhubung';
      isConnected.value = true;
      console.log(data.message);
    } else {
      backendStatus.value = 'Backend Error';
    }
  } catch (error) {
    backendStatus.value = 'Backend Offline';
    console.error('Gagal terhubung ke backend Laravel:', error);
  }
})

const adminMenus = [
  { name: 'Dashboard', path: '/admin', icon: LayoutDashboard },
  { name: 'Manajemen Produk', path: '/admin/products', icon: Package },
  { name: 'Manajemen Merchant', path: '/admin/merchants', icon: Store },
  { name: 'Kategori Produk', path: '/admin/categories', icon: Tags },
  { name: 'Produk Unggulan', path: '/admin/featured', icon: Star },
  { name: 'Statistik Lengkap', path: '/admin/statistics', icon: TrendingUp },
  { name: 'Pengaturan Profil', path: '/admin/profile', icon: Settings },
]

const router = useRouter()

const handleLogout = async () => {
  try {
    await fetch('http://127.0.0.1:8000/api/logout', {
      method: 'POST'
    })
  } catch (e) {
    console.error(e)
  } finally {
    localStorage.removeItem('admin_token')
    localStorage.removeItem('admin_user')
    router.push('/admin/login')
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Mobile Sidebar Overlay -->
    <div 
      v-if="isSidebarOpen" 
      class="fixed inset-0 bg-gray-900/50 z-40 lg:hidden"
      @click="isSidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside 
      class="fixed inset-y-0 left-0 bg-white border-r border-gray-200 w-64 z-50 transform transition-transform duration-300 lg:translate-x-0 lg:static lg:block flex flex-col"
      :class="isSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <!-- Sidebar Header -->
      <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100">
        <div class="text-emerald-700 font-bold text-lg tracking-tight truncate">
          Admin UMKM
        </div>
        <button @click="isSidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600">
          <X class="w-5 h-5" />
        </button>
      </div>

      <!-- Sidebar Menu -->
      <div class="flex-1 overflow-y-auto py-4 px-3 space-y-1 custom-scrollbar">
        <RouterLink 
          v-for="menu in adminMenus" 
          :key="menu.path"
          :to="menu.path"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
          :class="route.path === menu.path ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
        >
          <component :is="menu.icon" class="w-5 h-5" :class="route.path === menu.path ? 'text-emerald-600' : 'text-gray-400'" />
          {{ menu.name }}
        </RouterLink>
      </div>

      <!-- Sidebar Footer -->
      <div class="p-4 border-t border-gray-100">
        <button @click="handleLogout" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
          <LogOut class="w-5 h-5" />
          Keluar (Logout)
        </button>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
      <!-- Top Header -->
      <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 lg:px-8 z-30">
        <div class="flex items-center gap-4">
          <button @click="isSidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700">
            <Menu class="w-6 h-6" />
          </button>
          
          <div class="hidden md:flex items-center relative">
            <Search class="w-4 h-4 text-gray-400 absolute left-3" />
            <input 
              type="text" 
              placeholder="Cari menu atau data..." 
              class="pl-10 pr-4 py-2 bg-gray-50 border-transparent rounded-lg text-sm focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 w-64 transition-all"
            />
          </div>
        </div>

        <div class="flex items-center gap-5">
          <div class="h-8 w-8 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700 font-bold text-sm">
            A
          </div>
        </div>
      </header>

      <!-- Main Area -->
      <main class="flex-1 overflow-y-auto p-4 lg:p-8">
        <RouterView />
      </main>
    </div>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #e5e7eb;
  border-radius: 4px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
  background: #d1d5db;
}
</style>
