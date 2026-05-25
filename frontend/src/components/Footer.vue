<script setup lang="ts">
import { RouterLink } from 'vue-router'
import { Leaf, Share2, Globe } from 'lucide-vue-next'
</script>

<template>
  <footer class="bg-gray-50 pt-16 pb-8 border-t border-gray-200 mt-20 px-6 md:px-12">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
      <!-- Brand & Description -->
      <div class="col-span-1 md:col-span-1 pr-8">
        <h3 class="text-emerald-700 font-bold text-xl tracking-tight mb-4">UMKM Desa Purwoasri</h3>
        <p class="text-gray-500 text-sm leading-relaxed mb-6">
          Platform marketplace resmi untuk mempromosikan produk-produk unggulan karya warga Desa Purwoasri.
        </p>
        <div class="flex items-center gap-3">
          <button class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-200 transition-colors">
            <Leaf class="w-5 h-5" />
          </button>
          <button class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 hover:bg-emerald-200 transition-colors">
            <Share2 class="w-5 h-5" />
          </button>
        </div>
      </div>

      <!-- Links -->
      <div>
        <h4 class="font-bold text-gray-900 mb-6 text-sm uppercase tracking-wider">Layanan</h4>
        <ul class="space-y-4">
          <li><RouterLink to="/help" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Cara Belanja</RouterLink></li>
          <li><RouterLink to="/help" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Pembayaran</RouterLink></li>
          <li><RouterLink to="/help" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Pengiriman</RouterLink></li>
        </ul>
      </div>

      <div>
        <h4 class="font-bold text-gray-900 mb-6 text-sm uppercase tracking-wider">Tentang Kami</h4>
        <ul class="space-y-4">
          <li><RouterLink to="/about" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Profil Desa</RouterLink></li>
          <li><RouterLink to="/about" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Program UMKM</RouterLink></li>
          <li><RouterLink to="/about" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Kontak Kami</RouterLink></li>
        </ul>
      </div>

      <div>
        <h4 class="font-bold text-gray-900 mb-6 text-sm uppercase tracking-wider">Bantuan</h4>
        <ul class="space-y-4">
          <li><RouterLink to="/help" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Pusat Bantuan</RouterLink></li>
          <li><RouterLink to="/help" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Syarat & Ketentuan</RouterLink></li>
          <li><RouterLink to="/help" class="text-gray-500 hover:text-emerald-600 text-sm transition-colors">Kebijakan Privasi</RouterLink></li>
        </ul>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="max-w-7xl mx-auto pt-8 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between gap-4">
      <p class="text-gray-500 text-sm">
        &copy; {{ new Date().getFullYear() }} UMKM Desa Purwoasri. Semua Hak Dilindungi.
      </p>
      <div class="flex items-center gap-2 text-gray-500 text-sm">
        <Globe class="w-4 h-4" />
        <span>Indonesia (ID)</span>
      </div>
    </div>
  </footer>
</template>
