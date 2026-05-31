<script setup lang="ts">
import { 
  Package, 
  Store, 
  Tags, 
  TrendingUp,
  ArrowUpRight,
  ArrowDownRight,
  ExternalLink
} from 'lucide-vue-next'

import { ref, onMounted } from 'vue'

const stats = ref([
  { title: 'Total Produk', value: '-', trend: 'Live', isUp: true, icon: Package, color: 'bg-blue-50 text-blue-600' },
  { title: 'Total Merchant', value: '-', trend: 'Live', isUp: true, icon: Store, color: 'bg-emerald-50 text-emerald-600' },
  { title: 'Total Kategori', value: '-', trend: 'Live', isUp: true, icon: Tags, color: 'bg-purple-50 text-purple-600' },
  { title: 'Sistem Aktif', value: 'Online', trend: 'OK', isUp: true, icon: TrendingUp, color: 'bg-orange-50 text-orange-600' },
])

const recentProducts = ref<any[]>([])
const featuredProducts = ref<any[]>([])
const isLoading = ref(true)

onMounted(async () => {
  try {
    const [prodRes, merchRes, catRes] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/merchants'),
      fetch('http://127.0.0.1:8000/api/categories'),
    ])
    
    const pData = await prodRes.json()
    const mData = await merchRes.json()
    const cData = await catRes.json()

    const productsList = Array.isArray(pData) ? pData : (pData.data || [])
    const merchantsList = Array.isArray(mData) ? mData : (mData.data || [])
    const categoriesList = Array.isArray(cData) ? cData : (cData.data || [])

    if (stats.value[0]) stats.value[0].value = productsList.length.toString()
    if (stats.value[1]) stats.value[1].value = merchantsList.length.toString()
    if (stats.value[2]) stats.value[2].value = categoriesList.length.toString()

    recentProducts.value = [...productsList].sort((a,b) => b.id - a.id).slice(0, 4).map(p => ({
      id: p.id,
      name: p.name,
      merchant: merchantsList.find((m: any) => m.id == p.merchant_id)?.name || 'Unknown',
      price: new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(p.price),
      category: categoriesList.find((c: any) => c.id == p.category_id)?.name || '-',
      status: p.is_active ? 'Aktif' : 'Nonaktif',
      image: p.image || '/images/prod_1.png'
    }))

    featuredProducts.value = [...productsList].filter(p => p.is_featured).slice(0, 4).map(p => ({
      id: p.id,
      name: p.name,
      stock: p.stock,
      price: new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(p.price),
      image: p.image || '/images/prod_1.png'
    }))

  } catch (err) {
    console.error('Error loading dashboard stats:', err)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
      <p class="text-sm text-gray-500 mt-1">Ringkasan statistik dan aktivitas UMKM Desa Purwoasri.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="(stat, i) in stats" :key="i" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-start justify-between mb-4">
          <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', stat.color]">
            <component :is="stat.icon" class="w-6 h-6" />
          </div>
          <div :class="['flex items-center gap-1 text-sm font-medium', stat.isUp ? 'text-emerald-600' : 'text-red-600']">
            <component :is="stat.isUp ? ArrowUpRight : ArrowDownRight" class="w-4 h-4" />
            {{ stat.trend }}
          </div>
        </div>
        <div>
          <h3 class="text-3xl font-bold text-gray-900 mb-1">{{ stat.value }}</h3>
          <p class="text-sm text-gray-500 font-medium">{{ stat.title }}</p>
        </div>
      </div>
    </div>

    <!-- Data Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Recent Products -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
          <h2 class="font-bold text-gray-900">Produk Terbaru</h2>
          <RouterLink to="/admin/products" class="text-emerald-600 text-sm font-medium hover:text-emerald-700">Lihat Semua</RouterLink>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                <th class="py-4 px-6">Produk</th>
                <th class="py-4 px-6">Merchant</th>
                <th class="py-4 px-6">Harga</th>
                <th class="py-4 px-6">Status</th>
                <th class="py-4 px-6 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
              <tr v-if="isLoading">
                <td colspan="5" class="text-center py-8">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-600 mx-auto"></div>
                </td>
              </tr>
              <tr v-else-if="recentProducts.length === 0">
                <td colspan="5" class="text-center py-8 text-gray-500">Belum ada produk.</td>
              </tr>
              <tr v-else v-for="prod in recentProducts" :key="prod.id" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-4 px-6 flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100 shrink-0 bg-gray-50">
                    <img :src="prod.image" alt="Product" class="w-full h-full object-cover" />
                  </div>
                  <div>
                    <div class="font-medium text-gray-900">{{ prod.name }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ prod.category }}</div>
                  </div>
                </td>
                <td class="py-4 px-6">{{ prod.merchant }}</td>
                <td class="py-4 px-6 font-medium">{{ prod.price }}</td>
                <td class="py-4 px-6">
                  <span 
                    class="px-2.5 py-1 rounded-full text-xs font-medium"
                    :class="prod.status === 'Aktif' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600'"
                  >
                    {{ prod.status }}
                  </span>
                </td>
                <td class="py-4 px-6 text-right">
                  <RouterLink to="/admin/products" class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg inline-flex transition-colors" title="Kelola Produk">
                    <ExternalLink class="w-4 h-4" />
                  </RouterLink>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Featured Products -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-100">
          <h2 class="font-bold text-gray-900">Produk Unggulan</h2>
        </div>
        <div class="p-6 flex-1">
          <div v-if="isLoading" class="flex justify-center py-8">
             <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-600"></div>
          </div>
          <div v-else-if="featuredProducts.length === 0" class="text-sm text-center text-gray-500 py-8">
            Belum ada produk unggulan yang ditandai.
          </div>
          <div v-else class="space-y-6">
            <div v-for="(prod, i) in featuredProducts" :key="prod.id" class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg overflow-hidden border border-orange-100 shrink-0 bg-orange-50 relative">
                <img :src="prod.image" alt="Featured" class="w-full h-full object-cover" />
                <div class="absolute -top-1 -right-1 text-xs drop-shadow-sm">⭐</div>
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-bold text-gray-900 text-sm truncate mb-1">{{ prod.name }}</h4>
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>Stok Tersedia: <span class="font-bold text-gray-700">{{ prod.stock }}</span></span>
                  <span class="font-medium text-emerald-600">{{ prod.price }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
