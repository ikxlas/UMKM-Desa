<script setup lang="ts">
import { ref } from 'vue'
import { 
  TrendingUp,
  Crown,
  Star,
  Search,
  Plus,
  Trash2,
  GripVertical
} from 'lucide-vue-next'

// Mockup Data untuk masing-masing list
const trendingProducts = ref([
  { id: 1, name: 'Kripik Tempe Renyah', merchant: 'Pak Budi Snack', image: '/images/kripik_main.png' },
  { id: 2, name: 'Emping Melinjo Super', merchant: 'Ibu Siti Kerupuk', image: '/images/emping_main.png' }
])

const bestSellerProducts = ref([
  { id: 3, name: 'Anyaman Bambu Premium', merchant: 'Bengkel Kayu Jati', image: '/images/prod_bamboo.png' },
  { id: 1, name: 'Kripik Tempe Renyah', merchant: 'Pak Budi Snack', image: '/images/kripik_main.png' },
  { id: 4, name: 'Madu Hutan Asli', merchant: 'Kebun Purwo', image: '/images/merchant.png' }
])

const recommendedProducts = ref([
  { id: 5, name: 'Kerajinan Kayu Ukir', merchant: 'Bengkel Kayu Jati', image: '/images/merchant_wood.png' }
])

// Komponen Card yang dapat digunakan berulang (Reusable)
// Karena Vue 3 di SFC membutuhkan registrasi jika beda file, kita tulis langsung bloknya di template.
</script>

<template>
  <div class="space-y-8 pb-12 max-w-5xl">
    
    <!-- HEADER -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Kurasi Produk Unggulan</h1>
      <p class="text-sm text-gray-500 mt-1">Atur dan tentukan produk mana saja yang masuk ke dalam daftar khusus di halaman depan.</p>
    </div>

    <!-- KARTU 1: TRENDING -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <!-- Header Kartu -->
      <div class="border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white p-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
            <TrendingUp class="w-5 h-5" />
          </div>
          <div>
            <h2 class="text-lg font-bold text-gray-900">Produk Trending</h2>
            <p class="text-xs text-gray-500">Produk yang sedang naik daun atau banyak dicari minggu ini.</p>
          </div>
        </div>
        <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
          {{ trendingProducts.length }} Produk
        </span>
      </div>
      
      <!-- Body Kartu -->
      <div class="p-6 space-y-6">
        <!-- Fitur Tambah Produk -->
        <div class="flex gap-2">
          <div class="relative flex-1">
            <Search class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
            <input 
              type="text" 
              placeholder="Ketik nama produk untuk menambahkan ke daftar trending..." 
              class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-shadow"
            />
          </div>
          <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors border border-gray-200 flex items-center gap-2">
            <Plus class="w-4 h-4" /> Tambah
          </button>
        </div>

        <!-- Daftar Terpilih -->
        <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50/50">
          <ul class="divide-y divide-gray-100">
            <li v-if="trendingProducts.length === 0" class="p-8 text-center text-gray-500 text-sm">
              Belum ada produk di daftar ini.
            </li>
            <li v-for="(prod, index) in trendingProducts" :key="prod.id" class="flex items-center justify-between p-4 hover:bg-white transition-colors group bg-white">
              <div class="flex items-center gap-4">
                <GripVertical class="w-5 h-5 text-gray-300 cursor-move hover:text-gray-500" />
                <span class="text-sm font-bold text-gray-400 w-4">{{ index + 1 }}</span>
                <img :src="prod.image" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                <div>
                  <p class="text-sm font-bold text-gray-900">{{ prod.name }}</p>
                  <p class="text-xs text-gray-500">{{ prod.merchant }}</p>
                </div>
              </div>
              <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                <Trash2 class="w-4 h-4" />
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- KARTU 2: TERLARIS -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <!-- Header Kartu -->
      <div class="border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white p-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="bg-amber-100 p-2 rounded-lg text-amber-600">
            <Crown class="w-5 h-5" />
          </div>
          <div>
            <h2 class="text-lg font-bold text-gray-900">Produk Terlaris</h2>
            <p class="text-xs text-gray-500">Produk jagoan dengan penjualan tertinggi sepanjang masa.</p>
          </div>
        </div>
        <span class="text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1 rounded-full border border-amber-100">
          {{ bestSellerProducts.length }} Produk
        </span>
      </div>
      
      <!-- Body Kartu -->
      <div class="p-6 space-y-6">
        <div class="flex gap-2">
          <div class="relative flex-1">
            <Search class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
            <input 
              type="text" 
              placeholder="Ketik nama produk untuk menambahkan ke daftar terlaris..." 
              class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none transition-shadow"
            />
          </div>
          <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors border border-gray-200 flex items-center gap-2">
            <Plus class="w-4 h-4" /> Tambah
          </button>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50/50">
          <ul class="divide-y divide-gray-100">
            <li v-for="(prod, index) in bestSellerProducts" :key="prod.id" class="flex items-center justify-between p-4 hover:bg-white transition-colors group bg-white">
              <div class="flex items-center gap-4">
                <GripVertical class="w-5 h-5 text-gray-300 cursor-move hover:text-gray-500" />
                <span class="text-sm font-bold text-gray-400 w-4">{{ index + 1 }}</span>
                <img :src="prod.image" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                <div>
                  <p class="text-sm font-bold text-gray-900">{{ prod.name }}</p>
                  <p class="text-xs text-gray-500">{{ prod.merchant }}</p>
                </div>
              </div>
              <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                <Trash2 class="w-4 h-4" />
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- KARTU 3: REKOMENDASI ADMIN -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <!-- Header Kartu -->
      <div class="border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white p-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600">
            <Star class="w-5 h-5 fill-emerald-600" />
          </div>
          <div>
            <h2 class="text-lg font-bold text-gray-900">Rekomendasi Admin</h2>
            <p class="text-xs text-gray-500">Produk pilihan khusus yang ingin Anda promosikan minggu ini.</p>
          </div>
        </div>
        <span class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
          {{ recommendedProducts.length }} Produk
        </span>
      </div>
      
      <!-- Body Kartu -->
      <div class="p-6 space-y-6">
        <div class="flex gap-2">
          <div class="relative flex-1">
            <Search class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
            <input 
              type="text" 
              placeholder="Ketik nama produk untuk direkomendasikan..." 
              class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow"
            />
          </div>
          <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors border border-gray-200 flex items-center gap-2">
            <Plus class="w-4 h-4" /> Tambah
          </button>
        </div>

        <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50/50">
          <ul class="divide-y divide-gray-100">
            <li v-for="(prod, index) in recommendedProducts" :key="prod.id" class="flex items-center justify-between p-4 hover:bg-white transition-colors group bg-white">
              <div class="flex items-center gap-4">
                <GripVertical class="w-5 h-5 text-gray-300 cursor-move hover:text-gray-500" />
                <span class="text-sm font-bold text-gray-400 w-4">{{ index + 1 }}</span>
                <img :src="prod.image" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                <div>
                  <p class="text-sm font-bold text-gray-900">{{ prod.name }}</p>
                  <p class="text-xs text-gray-500">{{ prod.merchant }}</p>
                </div>
              </div>
              <button class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                <Trash2 class="w-4 h-4" />
              </button>
            </li>
          </ul>
        </div>
      </div>
    </div>

  </div>
</template>
