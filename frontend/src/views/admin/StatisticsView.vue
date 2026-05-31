<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { 
  MessageCircle, 
  ShoppingBag, 
  Store,
  Eye,
  TrendingUp,
  MousePointerClick,
  Award
} from 'lucide-vue-next'

const clickMetrics = ref([
  { id: 'wa', name: 'WhatsApp', total: 0, color: 'bg-emerald-500', icon: MessageCircle, trend: 'Aktif' },
  { id: 'grab', name: 'GrabFood', total: 0, color: 'bg-green-500', icon: ShoppingBag, trend: 'Aktif' },
  { id: 'go', name: 'GoFood', total: 0, color: 'bg-red-500', icon: ShoppingBag, trend: 'Aktif' },
  { id: 'shopee', name: 'Shopee', total: 0, color: 'bg-orange-500', icon: Store, trend: 'Aktif' },
])

const totalClicks = computed(() => {
  const sum = clickMetrics.value.reduce((s, item) => s + item.total, 0)
  return sum === 0 ? 1 : sum // prevent division by zero
})
const realTotal = computed(() => clickMetrics.value.reduce((s, item) => s + item.total, 0))

const topViewedProducts = ref<any[]>([])
const isLoading = ref(true)

onMounted(async () => {
  try {
    const [prodRes, merchRes] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/merchants')
    ])
    
    const pData = await prodRes.json()
    const mData = await merchRes.json()
    
    const productsList = Array.isArray(pData) ? pData : (pData.data || [])
    const merchantsList = Array.isArray(mData) ? mData : (mData.data || [])

    let waCount = 0; let grabCount = 0; let goCount = 0; let shopeeCount = 0;

    productsList.forEach((p: any) => {
      waCount += p.wa_clicks || 0
      grabCount += p.grab_clicks || 0
      goCount += p.go_clicks || 0
      shopeeCount += p.shopee_clicks || 0
    })

    if (clickMetrics.value[0]) clickMetrics.value[0].total = waCount
    if (clickMetrics.value[1]) clickMetrics.value[1].total = grabCount
    if (clickMetrics.value[2]) clickMetrics.value[2].total = goCount
    if (clickMetrics.value[3]) clickMetrics.value[3].total = shopeeCount

    const mappedProducts = productsList.map((p: any) => {
      return {
        id: p.id,
        name: p.name,
        merchant: merchantsList.find((m: any) => m.id == p.merchant_id)?.name || 'Unknown',
        views: p.view_count || 0,
        image: p.image || '/images/prod_1.png'
      }
    })

    mappedProducts.sort((a: any, b: any) => b.views - a.views)
    topViewedProducts.value = mappedProducts.slice(0, 5)

  } catch (err) {
    console.error('Error fetching statistics:', err)
  } finally {
    isLoading.value = false
  }
})
</script>

<template>
  <div class="space-y-8 pb-12">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Statistik Lengkap</h1>
        <p class="text-sm text-gray-500 mt-1">Pantau analitik jumlah klik pesanan dan total tayangan produk secara riil.</p>
      </div>
      <div class="bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center gap-2">
        <span class="text-sm text-gray-500">Total Interaksi:</span>
        <span class="font-bold text-gray-900">{{ realTotal.toLocaleString('id-ID') }} Klik</span>
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
              <span class="font-bold">Insight:</span> Proporsi klik pemesanan yang paling difavoritkan pembeli adalah 
              <span class="font-bold">Shopee ({{ Math.round(((clickMetrics[3]?.total || 0) / totalClicks) * 100) }}%)</span> dan 
              <span class="font-bold">GoFood ({{ Math.round(((clickMetrics[2]?.total || 0) / totalClicks) * 100) }}%)</span> dibandingkan yang lain, di luar WhatsApp.
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
                <tr v-if="isLoading">
                  <td colspan="4" class="text-center py-12">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-600 mx-auto"></div>
                  </td>
                </tr>
                <tr v-else-if="topViewedProducts.length === 0">
                  <td colspan="4" class="text-center py-12 text-gray-500">Belum ada produk yang terdaftar.</td>
                </tr>
                <tr v-else v-for="(prod, index) in topViewedProducts" :key="prod.id" class="hover:bg-gray-50/50 transition-colors">
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
