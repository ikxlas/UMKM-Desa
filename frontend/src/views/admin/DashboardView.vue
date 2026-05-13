<script setup lang="ts">
import { 
  Package, 
  Store, 
  Tags, 
  TrendingUp,
  ArrowUpRight,
  ArrowDownRight,
  MoreVertical
} from 'lucide-vue-next'

const stats = [
  { title: 'Total Produk', value: '254', trend: '+12.5%', isUp: true, icon: Package, color: 'bg-blue-50 text-blue-600' },
  { title: 'Total Merchant', value: '48', trend: '+5.2%', isUp: true, icon: Store, color: 'bg-emerald-50 text-emerald-600' },
  { title: 'Total Kategori', value: '12', trend: '0%', isUp: true, icon: Tags, color: 'bg-purple-50 text-purple-600' },
  { title: 'Kunjungan Bulan Ini', value: '12.4k', trend: '-2.1%', isUp: false, icon: TrendingUp, color: 'bg-orange-50 text-orange-600' },
]

const recentProducts = [
  { id: 1, name: 'Anyaman Bambu Premium', merchant: 'Bengkel Kayu Jati', price: 'Rp 75.000', category: 'Kerajinan', status: 'Aktif' },
  { id: 2, name: 'Madu Hutan Murni', merchant: 'Kebun Hidroponik', price: 'Rp 125.000', category: 'Makanan', status: 'Draft' },
  { id: 3, name: 'Kain Batik Tulis Solo', merchant: 'Toko Bu Tejo', price: 'Rp 350.000', category: 'Pakaian', status: 'Aktif' },
  { id: 4, name: 'Emping Melinjo Asli', merchant: 'Pak Budi Snack', price: 'Rp 22.000', category: 'Makanan', status: 'Aktif' },
]

const popularProducts = [
  { id: 1, name: 'Kripik Tempe Renyah', sales: 1205, revenue: 'Rp 18.075.000' },
  { id: 2, name: 'Kerupuk Sayur Organik', sales: 840, revenue: 'Rp 12.600.000' },
  { id: 3, name: 'Meja Jati Ukir', sales: 45, revenue: 'Rp 56.250.000' },
]
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
          <button class="text-emerald-600 text-sm font-medium hover:text-emerald-700">Lihat Semua</button>
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
              <tr v-for="prod in recentProducts" :key="prod.id" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-4 px-6">
                  <div class="font-medium text-gray-900">{{ prod.name }}</div>
                  <div class="text-xs text-gray-500 mt-0.5">{{ prod.category }}</div>
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
                  <button class="text-gray-400 hover:text-gray-600">
                    <MoreVertical class="w-5 h-5" />
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Popular Products -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col">
        <div class="p-6 border-b border-gray-100">
          <h2 class="font-bold text-gray-900">Produk Terpopuler</h2>
        </div>
        <div class="p-6 flex-1">
          <div class="space-y-6">
            <div v-for="(prod, i) in popularProducts" :key="prod.id" class="flex items-start gap-4">
              <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 font-bold flex items-center justify-center shrink-0">
                #{{ i + 1 }}
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="font-bold text-gray-900 text-sm truncate mb-1">{{ prod.name }}</h4>
                <div class="flex items-center justify-between text-xs text-gray-500">
                  <span>{{ prod.sales }} terjual</span>
                  <span class="font-medium text-emerald-600">{{ prod.revenue }}</span>
                </div>
                <!-- Progress bar mock -->
                <div class="w-full h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden">
                  <div class="h-full bg-emerald-500 rounded-full" :style="`width: ${(3 - i) * 30}%`"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
