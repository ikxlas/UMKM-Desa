<script setup lang="ts">
import { ref } from 'vue'
import { 
  ChevronRight, 
  MapPin, 
  Star,
  Leaf, 
  Croissant, 
  Shirt, 
  Tractor, 
  Sofa, 
  LayoutGrid,
  Filter
} from 'lucide-vue-next'

const categories = [
  { id: 'semua', name: 'Semua Produk', icon: LayoutGrid },
  { id: 'kerajinan', name: 'Kerajinan', icon: Leaf },
  { id: 'makanan', name: 'Makanan & Minuman', icon: Croissant },
  { id: 'pakaian', name: 'Pakaian', icon: Shirt },
  { id: 'pertanian', name: 'Hasil Pertanian', icon: Tractor },
  { id: 'furnitur', name: 'Furnitur & Kayu', icon: Sofa },
]

const activeCategory = ref('semua')

const products = [
  {
    id: 1,
    name: 'Anyaman Bambu Premium',
    price: 'Rp 75.000',
    location: 'Purwoasri, Kediri',
    rating: 4.8,
    sold: '120+',
    image: '/images/prod_1.png',
    readyStock: true,
    category: 'kerajinan'
  },
  {
    id: 2,
    name: 'Kripik Tempe Renyah',
    price: 'Rp 15.000',
    location: 'Purwoasri, Kediri',
    rating: 4.8,
    sold: '120+',
    image: '/images/kripik_main.png',
    readyStock: true,
    category: 'makanan'
  },
  {
    id: 3,
    name: 'Kain Batik Tulis Solo',
    price: 'Rp 350.000',
    location: 'Purwoasri, Kediri',
    rating: 4.9,
    sold: '45',
    image: '/images/prod_1.png',
    readyStock: false,
    category: 'pakaian'
  },
  {
    id: 4,
    name: 'Emping Melinjo Asli',
    price: 'Rp 22.000',
    location: 'Purwoasri, Kediri',
    rating: 4.7,
    sold: '210',
    image: '/images/emping_main.png',
    readyStock: false,
    category: 'makanan'
  },
  {
    id: 5,
    name: 'Kerupuk Sayur Organik',
    price: 'Rp 15.000',
    location: 'Purwoasri, Kediri',
    rating: 4.8,
    sold: '500+',
    image: '/images/prod_1.png',
    readyStock: false,
    category: 'makanan'
  },
  {
    id: 6,
    name: 'Meja Jati Ukir',
    price: 'Rp 1.250.000',
    location: 'Purwoasri, Kediri',
    rating: 5.0,
    sold: '12',
    image: '/images/merchant.png',
    readyStock: false,
    category: 'furnitur'
  }
]
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
        <ChevronRight class="w-4 h-4" />
        <span class="text-emerald-700 font-medium">Kategori Produk</span>
      </nav>

      <!-- Layout Grid -->
      <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <aside class="w-full lg:w-64 shrink-0">
          <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
              <Filter class="w-5 h-5 text-emerald-600" /> Filter Kategori
            </h3>
            <ul class="space-y-2">
              <li v-for="cat in categories" :key="cat.id">
                <button 
                  @click="activeCategory = cat.id"
                  class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategory === cat.id ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <component :is="cat.icon" class="w-4 h-4" :class="activeCategory === cat.id ? 'text-emerald-600' : 'text-gray-400'" />
                  {{ cat.name }}
                </button>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Main Content (Products Grid) -->
        <div class="flex-1">
          <!-- Header -->
          <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">
              {{ categories.find(c => c.id === activeCategory)?.name }}
            </h1>
            <span class="text-sm text-gray-500">
              Menampilkan {{ activeCategory === 'semua' ? products.length : products.filter(p => p.category === activeCategory).length }} produk
            </span>
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            <RouterLink 
              v-for="product in (activeCategory === 'semua' ? products : products.filter(p => p.category === activeCategory))" 
              :key="product.id" 
              to="/product"
              class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group"
            >
              <div class="relative h-48 overflow-hidden bg-gray-100">
                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div v-if="product.readyStock" class="absolute top-3 left-3 bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-1 rounded">
                  READY STOCK
                </div>
              </div>
              <div class="p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2 truncate">{{ product.name }}</h3>
                <p class="text-emerald-600 font-bold text-lg mb-3">{{ product.price }}</p>
                
                <div class="flex items-center gap-1 text-gray-500 text-xs mb-3">
                  <MapPin class="w-3.5 h-3.5 shrink-0" />
                  <span class="truncate">{{ product.location }}</span>
                </div>
                
                <div class="flex items-center justify-between border-t border-gray-100 pt-3">
                  <div class="flex items-center gap-1 text-xs font-medium text-gray-700">
                    <Star class="w-3.5 h-3.5 text-orange-400 fill-orange-400 shrink-0" />
                    <span>{{ product.rating }}</span>
                  </div>
                  <span class="text-xs text-gray-500">Terjual {{ product.sold }}</span>
                </div>
              </div>
            </RouterLink>
          </div>

          <!-- Empty State -->
          <div v-if="activeCategory !== 'semua' && products.filter(p => p.category === activeCategory).length === 0" class="text-center py-20 bg-white rounded-2xl border border-gray-100 mt-6">
            <LayoutGrid class="w-16 h-16 text-gray-200 mx-auto mb-4" />
            <h3 class="text-lg font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 text-sm">Belum ada produk di kategori ini. Silakan cek kategori lain.</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
