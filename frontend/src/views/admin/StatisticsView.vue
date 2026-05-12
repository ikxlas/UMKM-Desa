<script setup lang="ts">
import { ref } from 'vue'
import { 
  MessageCircle, 
  ShoppingBag, 
  Store,
  Eye,
  TrendingUp,
  MousePointerClick,
  Award
} from 'lucide-vue-next'

// Mock Data Metrik Klik (Biasanya dari API Analytics Backend)
const clickMetrics = ref([
  { id: 'wa', name: 'WhatsApp', total: 1245, color: 'bg-emerald-500', icon: MessageCircle, trend: '+12% minggu ini' },
  { id: 'grab', name: 'GrabFood', total: 856, color: 'bg-green-500', icon: ShoppingBag, trend: '+5% minggu ini' },
  { id: 'go', name: 'GoFood', total: 920, color: 'bg-red-500', icon: ShoppingBag, trend: '+8% minggu ini' },
  { id: 'shopee', name: 'Shopee', total: 1540, color: 'bg-orange-500', icon: Store, trend: '+24% minggu ini' },
])

// Total keseluruhan untuk kalkulasi persentase bar
const totalClicks = clickMetrics.value.reduce((sum, item) => sum + item.total, 0)

// Mock Data Produk Terpopuler (Leaderboard)
const topViewedProducts = ref([
  { id: 1, name: 'Kripik Tempe Renyah', merchant: 'Pak Budi Snack', views: 5430, image: '/images/kripik_main.png' },
  { id: 2, name: 'Anyaman Bambu Premium', merchant: 'Bengkel Kayu Jati', views: 4215, image: '/images/prod_bamboo.png' },
  { id: 3, name: 'Emping Melinjo Super', merchant: 'Ibu Siti Kerupuk', views: 3890, image: '/images/emping_main.png' },
  { id: 4, name: 'Kerajinan Kayu Ukir', merchant: 'Bengkel Kayu Jati', views: 2750, image: '/images/merchant_wood.png' },
  { id: 5, name: 'Madu Hutan Asli', merchant: 'Kebun Purwo', views: 1980, image: '/images/merchant.png' },
])
</script>

<template>
  <div class="space-y-8 pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Statistik Lengkap</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau analitik klik pembelian dan popularitas produk di website.</p>
      </div>
      <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center gap-2">
        <span class="text-sm text-gray-500">Total Interaksi:</span>
        <span class="font-bold text-gray-900">{{ totalClicks.toLocaleString('id-ID') }} Klik</span>
      </div>
    </div>

    <!-- 4 KARTU METRIK UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="metric in clickMetrics" :key="metric.id" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <!-- Aksen Dekoratif -->
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full opacity-10 transition-transform group-hover:scale-110" :class="metric.color"></div>
        
        <div class="flex items-start justify-between relative z-10">
          <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Klik {{ metric.name }}</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ metric.total.toLocaleString('id-ID') }}</h3>
          </div>
          <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white shadow-sm" :class="metric.color">
            <component :is="metric.icon" class="w-6 h-6" />
          </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5 text-xs font-medium text-gray-500 relative z-10">
          <TrendingUp class="w-4 h-4 text-emerald-500" />
          <span class="text-emerald-600">{{ metric.trend }}</span>
        </div>
      </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- KOLOM KIRI: GRAFIK PERBANDINGAN -->
      <div class="lg:col-span-1 space-y-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
          <div class="flex items-center gap-3 mb-6">
            <MousePointerClick class="w-6 h-6 text-blue-500" />
            <h2 class="text-lg font-bold text-gray-900">Perbandingan Platform</h2>
          </div>
          
          <div class="space-y-5">
            <div v-for="metric in clickMetrics" :key="`bar-${metric.id}`" class="space-y-2">
              <div class="flex justify-between text-sm">
                <span class="font-medium text-gray-700">{{ metric.name }}</span>
                <span class="font-bold text-gray-900">{{ Math.round((metric.total / totalClicks) * 100) }}%</span>
              </div>
              <!-- Custom Progress Bar -->
              <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div 
                  class="h-full rounded-full transition-all duration-1000 ease-out" 
                  :class="metric.color" 
                  :style="{ width: `${(metric.total / totalClicks) * 100}%` }"
                ></div>
              </div>
            </div>
          </div>
          
          <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100">
            <p class="text-sm text-blue-800 leading-relaxed">
              <span class="font-bold">Insight:</span> Sebagian besar pengunjung lebih suka melakukan transaksi melalui 
              <span class="font-bold">Shopee ({{ Math.round((1540 / totalClicks) * 100) }}%)</span> dan 
              <span class="font-bold">WhatsApp ({{ Math.round((1245 / totalClicks) * 100) }}%)</span>.
            </p>
          </div>
        </div>
      </div>

      <!-- KOLOM KANAN: LEADERBOARD PRODUK (DILIHAT) -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col h-full">
          <div class="border-b border-gray-100 p-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <Award class="w-6 h-6 text-amber-500" />
              <h2 class="text-lg font-bold text-gray-900">Peringkat Produk Paling Sering Dilihat</h2>
            </div>
            <span class="text-sm text-gray-500 font-medium">Top 5</span>
          </div>
          
          <div class="p-0 overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                  <th class="py-4 px-6 w-16 text-center">Rank</th>
                  <th class="py-4 px-6">Informasi Produk</th>
                  <th class="py-4 px-6">Merchant (Toko)</th>
                  <th class="py-4 px-6 text-right">Total Tayangan</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                <tr v-for="(prod, index) in topViewedProducts" :key="prod.id" class="hover:bg-gray-50/50 transition-colors">
                  <td class="py-4 px-6 text-center">
                    <!-- Medal untuk Top 3 -->
                    <div v-if="index === 0" class="w-8 h-8 mx-auto bg-amber-100 text-amber-600 rounded-full flex items-center justify-center font-bold">1</div>
                    <div v-else-if="index === 1" class="w-8 h-8 mx-auto bg-gray-200 text-gray-600 rounded-full flex items-center justify-center font-bold">2</div>
                    <div v-else-if="index === 2" class="w-8 h-8 mx-auto bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">3</div>
                    <div v-else class="w-8 h-8 mx-auto text-gray-400 flex items-center justify-center font-bold">{{ index + 1 }}</div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="flex items-center gap-4">
                      <img :src="prod.image" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                      <span class="font-bold text-gray-900">{{ prod.name }}</span>
                    </div>
                  </td>
                  <td class="py-4 px-6 text-gray-600">
                    {{ prod.merchant }}
                  </td>
                  <td class="py-4 px-6 text-right">
                    <div class="flex items-center justify-end gap-2 text-emerald-600 font-bold bg-emerald-50 px-3 py-1.5 rounded-lg inline-flex">
                      <Eye class="w-4 h-4" />
                      {{ prod.views.toLocaleString('id-ID') }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>
