<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { 
  TrendingUp,
  Crown,
  Star,
  Plus,
  Trash2,
  GripVertical
} from 'lucide-vue-next'

const allProducts = ref<any[]>([])
const settings = ref<any>({
  featured_trending: '[]',
  featured_bestseller: '[]',
  featured_recommended: '[]'
})

const newTrendingId = ref('')
const newBestsellerId = ref('')
const newRecommendedId = ref('')

const isLoading = ref(false)

const fetchData = async () => {
  isLoading.value = true
  try {
    const [prodRes, setRes] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/settings')
    ])
    const rawProducts = await prodRes.json()
    allProducts.value = rawProducts.filter((p: any) => p.is_featured == 1 || p.is_featured === true)
    const set = await setRes.json()
    if(set.featured_trending) settings.value.featured_trending = set.featured_trending
    if(set.featured_bestseller) settings.value.featured_bestseller = set.featured_bestseller
    if(set.featured_recommended) settings.value.featured_recommended = set.featured_recommended
  } catch (error) {
    console.error('Error fetching data:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchData()
})

const getProductsFromIds = (jsonIds: string): any[] => {
  try {
    const ids = JSON.parse(jsonIds)
    if (!Array.isArray(ids)) return []
    return ids.map((id: number) => allProducts.value.find(p => p.id === id)).filter(Boolean)
  } catch(e) {
    return []
  }
}

const trendingProducts = computed(() => getProductsFromIds(settings.value.featured_trending))
const bestSellerProducts = computed(() => getProductsFromIds(settings.value.featured_bestseller))
const recommendedProducts = computed(() => getProductsFromIds(settings.value.featured_recommended))

const saveSettings = async () => {
  const formData = new FormData()
  formData.append('featured_trending', settings.value.featured_trending)
  formData.append('featured_bestseller', settings.value.featured_bestseller)
  formData.append('featured_recommended', settings.value.featured_recommended)

  try {
    await fetch('http://127.0.0.1:8000/api/settings', {
      method: 'POST',
      body: formData
    })
  } catch (e) {
    console.error(e)
  }
}

const addProduct = (settingKey: string, category: string) => {
  let idStr = ''
  if (category === 'trending') idStr = newTrendingId.value
  if (category === 'bestseller') idStr = newBestsellerId.value
  if (category === 'recommended') idStr = newRecommendedId.value

  const id = parseInt(idStr)
  if(!id) return

  let currentIds: number[] = []
  try { currentIds = JSON.parse(settings.value[settingKey]) } catch(e) {}
  
  if(!currentIds.includes(id)) {
    currentIds.push(id)
    settings.value[settingKey] = JSON.stringify(currentIds)
    saveSettings()
  }

  if (category === 'trending') newTrendingId.value = ''
  if (category === 'bestseller') newBestsellerId.value = ''
  if (category === 'recommended') newRecommendedId.value = ''
}

const removeProduct = (settingKey: string, id: number) => {
  let currentIds = []
  try { currentIds = JSON.parse(settings.value[settingKey]) } catch(e) {}
  
  const updatedIds = currentIds.filter((item: number) => item !== id)
  settings.value[settingKey] = JSON.stringify(updatedIds)
  saveSettings()
}
</script>

<template>
  <div class="space-y-8 pb-12 max-w-5xl">
    
    <!-- HEADER -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Kurasi Produk Unggulan</h1>
      <p class="text-sm text-gray-500 mt-1">Atur dan tentukan produk mana saja yang masuk ke dalam daftar khusus di halaman depan.</p>
    </div>

    <div v-if="isLoading" class="flex justify-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
    </div>

    <template v-else>
      <!-- KARTU 1: TRENDING -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <!-- Header Kartu -->
        <div class="border-b border-gray-100 bg-gradient-to-r from-blue-50 to-white p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="bg-blue-100 p-2 rounded-lg text-blue-600 shrink-0">
              <TrendingUp class="w-5 h-5" />
            </div>
            <div>
              <h2 class="text-lg font-bold text-gray-900">Produk Trending</h2>
              <p class="text-xs text-gray-500">Produk yang sedang naik daun atau banyak dicari minggu ini.</p>
            </div>
          </div>
          <span class="text-sm font-medium text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full border border-blue-100 whitespace-nowrap shrink-0 self-start sm:self-auto">
            {{ trendingProducts.length }} Produk
          </span>
        </div>
        
        <!-- Body Kartu -->
        <div class="p-4 sm:p-6 space-y-6">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <select v-model="newTrendingId" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-shadow bg-white">
                <option value="">Pilih Produk dari Database...</option>
                <option v-for="p in allProducts" :key="p.id" :value="p.id">{{ p.name }} - {{ p.merchant?.name }}</option>
              </select>
            </div>
            <button @click="addProduct('featured_trending', 'trending')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors border border-gray-200 flex items-center gap-2">
              <Plus class="w-4 h-4" /> Tambah
            </button>
          </div>

          <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50/50">
            <ul class="divide-y divide-gray-100">
              <li v-if="trendingProducts.length === 0" class="p-8 text-center text-gray-500 text-sm">
                Belum ada produk di daftar ini.
              </li>
              <li v-for="(prod, index) in trendingProducts" :key="prod.id" class="flex items-center justify-between p-4 hover:bg-white transition-colors group bg-white">
                <div class="flex items-center gap-4">
                  <GripVertical class="w-5 h-5 text-gray-300 cursor-move hover:text-gray-500" />
                  <span class="text-sm font-bold text-gray-400 w-4">{{ index + 1 }}</span>
                  <img :src="prod.image || '/images/kripik_main.png'" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                  <div>
                    <p class="text-sm font-bold text-gray-900">{{ prod.name }}</p>
                    <p class="text-xs text-gray-500">{{ prod.merchant?.name }}</p>
                  </div>
                </div>
                <button @click="removeProduct('featured_trending', prod.id)" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
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
        <div class="border-b border-gray-100 bg-gradient-to-r from-amber-50 to-white p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="bg-amber-100 p-2 rounded-lg text-amber-600 shrink-0">
              <Crown class="w-5 h-5" />
            </div>
            <div>
              <h2 class="text-lg font-bold text-gray-900">Produk Terlaris</h2>
              <p class="text-xs text-gray-500">Produk jagoan dengan penjualan tertinggi sepanjang masa.</p>
            </div>
          </div>
          <span class="text-sm font-medium text-amber-600 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-100 whitespace-nowrap shrink-0 self-start sm:self-auto">
            {{ bestSellerProducts.length }} Produk
          </span>
        </div>
        
        <!-- Body Kartu -->
        <div class="p-4 sm:p-6 space-y-6">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <select v-model="newBestsellerId" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none transition-shadow bg-white">
                <option value="">Pilih Produk dari Database...</option>
                <option v-for="p in allProducts" :key="p.id" :value="p.id">{{ p.name }} - {{ p.merchant?.name }}</option>
              </select>
            </div>
            <button @click="addProduct('featured_bestseller', 'bestseller')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors border border-gray-200 flex items-center gap-2">
              <Plus class="w-4 h-4" /> Tambah
            </button>
          </div>

          <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50/50">
            <ul class="divide-y divide-gray-100">
              <li v-if="bestSellerProducts.length === 0" class="p-8 text-center text-gray-500 text-sm">
                Belum ada produk di daftar ini.
              </li>
              <li v-for="(prod, index) in bestSellerProducts" :key="prod.id" class="flex items-center justify-between p-4 hover:bg-white transition-colors group bg-white">
                <div class="flex items-center gap-4">
                  <GripVertical class="w-5 h-5 text-gray-300 cursor-move hover:text-gray-500" />
                  <span class="text-sm font-bold text-gray-400 w-4">{{ index + 1 }}</span>
                  <img :src="prod.image || '/images/kripik_main.png'" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                  <div>
                    <p class="text-sm font-bold text-gray-900">{{ prod.name }}</p>
                    <p class="text-xs text-gray-500">{{ prod.merchant?.name }}</p>
                  </div>
                </div>
                <button @click="removeProduct('featured_bestseller', prod.id)" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
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
        <div class="border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-white p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <div class="bg-emerald-100 p-2 rounded-lg text-emerald-600 shrink-0">
              <Star class="w-5 h-5 fill-emerald-600" />
            </div>
            <div>
              <h2 class="text-lg font-bold text-gray-900">Rekomendasi Admin</h2>
              <p class="text-xs text-gray-500">Produk pilihan khusus yang ingin Anda promosikan minggu ini.</p>
            </div>
          </div>
          <span class="text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-100 whitespace-nowrap shrink-0 self-start sm:self-auto">
            {{ recommendedProducts.length }} Produk
          </span>
        </div>
        
        <!-- Body Kartu -->
        <div class="p-4 sm:p-6 space-y-6">
          <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
              <select v-model="newRecommendedId" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow bg-white">
                <option value="">Pilih Produk dari Database...</option>
                <option v-for="p in allProducts" :key="p.id" :value="p.id">{{ p.name }} - {{ p.merchant?.name }}</option>
              </select>
            </div>
            <button @click="addProduct('featured_recommended', 'recommended')" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-6 rounded-lg transition-colors border border-gray-200 flex items-center gap-2">
              <Plus class="w-4 h-4" /> Tambah
            </button>
          </div>

          <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50/50">
            <ul class="divide-y divide-gray-100">
              <li v-if="recommendedProducts.length === 0" class="p-8 text-center text-gray-500 text-sm">
                Belum ada produk di daftar ini.
              </li>
              <li v-for="(prod, index) in recommendedProducts" :key="prod.id" class="flex items-center justify-between p-4 hover:bg-white transition-colors group bg-white">
                <div class="flex items-center gap-4">
                  <GripVertical class="w-5 h-5 text-gray-300 cursor-move hover:text-gray-500" />
                  <span class="text-sm font-bold text-gray-400 w-4">{{ index + 1 }}</span>
                  <img :src="prod.image || '/images/kripik_main.png'" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shadow-sm" />
                  <div>
                    <p class="text-sm font-bold text-gray-900">{{ prod.name }}</p>
                    <p class="text-xs text-gray-500">{{ prod.merchant?.name }}</p>
                  </div>
                </div>
                <button @click="removeProduct('featured_recommended', prod.id)" class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                  <Trash2 class="w-4 h-4" />
                </button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>
