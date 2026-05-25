<script setup lang="ts">
import { Search, ShoppingCart, Bell, User } from 'lucide-vue-next'
</script>

<template>
  <nav class="bg-white border-b border-gray-100 py-4 px-6 md:px-12 flex items-center justify-between sticky top-0 z-50">
    <!-- Logo -->
    <div class="flex items-center gap-8">
      <RouterLink to="/" class="text-emerald-700 font-bold text-xl tracking-tight">
        UMKM Desa Purwoasri
      </RouterLink>
      
      <!-- Nav Links (Desktop) -->
      <div class="hidden md:flex items-center gap-6 text-sm text-gray-600 font-medium">
        <RouterLink to="/category" class="hover:text-emerald-600 transition-colors">Kategori</RouterLink>
        <RouterLink to="/stores" class="hover:text-emerald-600 transition-colors">Toko</RouterLink>
        <RouterLink to="/about" class="hover:text-emerald-600 transition-colors">Tentang Kami</RouterLink>
        <RouterLink to="/help" class="hover:text-emerald-600 transition-colors">Bantuan</RouterLink>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="hidden md:flex flex-1 max-w-2xl px-8">
      <div class="relative w-full">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <Search class="h-4 w-4 text-gray-400" />
        </div>
        <input 
          type="text" 
          class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 sm:text-sm transition-colors" 
          placeholder="Cari produk desa..." 
        />
      </div>
    </div>

    <!-- Right empty placeholder so flex-1 search bar stays centered if we need it -->
    <div class="hidden md:block w-24"></div>
  </nav>
</template>
