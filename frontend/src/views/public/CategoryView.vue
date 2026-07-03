<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { RouterLink, useRoute, useRouter } from 'vue-router'
import { 
  ChevronRight, 
  MapPin, 
  Star,
  Crown,
  TrendingUp,
  LayoutGrid,
  Filter,
  Leaf, 
  Croissant, 
  Shirt, 
  Tractor, 
  Sofa, 
  CheckCircle2,
  ArrowRight,
  ShieldCheck,
  Truck,
  Clock,
  ShoppingBag,
  Package,
  Coffee,
  Hammer,
  Palette,
  Scissors,
  Store,
  Utensils,
  Watch,
  Sprout,
  Carrot,
  Home,
  Wrench,
  Camera,
  Smartphone,
  Monitor,
  Headphones,
  Sparkles,
  Gift,
  Book,
  Briefcase
} from 'lucide-vue-next'

const iconMap: Record<string, any> = {
  'Tractor': Tractor,
  'Leaf': Leaf,
  'ShoppingBag': ShoppingBag,
  'Package': Package,
  'Coffee': Coffee,
  'Shirt': Shirt,
  'Hammer': Hammer,
  'Palette': Palette,
  'Scissors': Scissors,
  'Sofa': Sofa,
  'Croissant': Croissant,
  'Store': Store,
  'Utensils': Utensils,
  'Watch': Watch,
  'Sprout': Sprout,
  'Carrot': Carrot,
  'Home': Home,
  'Wrench': Wrench,
  'Camera': Camera,
  'Smartphone': Smartphone,
  'Monitor': Monitor,
  'Headphones': Headphones,
  'Sparkles': Sparkles,
  'Gift': Gift,
  'Book': Book,
  'Briefcase': Briefcase,
  'Truck': Truck,
  'Star': Star,
  'LayoutGrid': LayoutGrid
}

const getIcon = (iconName: string) => {
  if (!iconName) return LayoutGrid;
  return iconMap[iconName] || LayoutGrid;
}

// Database State
const categories = ref<any[]>([])
const allProducts = ref<any[]>([])
const isLoading = ref(true)
const activeCategoryId = ref<number | string>('semua')
const settings = ref<any>({
  featured_trending: '[]',
  featured_bestseller: '[]',
  featured_recommended: '[]'
})

const fetchAllData = async () => {
  isLoading.value = true
  try {
    const [resCat, resProd, resSet] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/categories'),
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/settings')
    ])
    
    const dataCat = await resCat.json()
    const dataProd = await resProd.json()
    const dataSet = resSet.ok ? await resSet.json() : null
    
    categories.value = Array.isArray(dataCat) ? dataCat : (dataCat.data || [])
    allProducts.value = Array.isArray(dataProd) ? dataProd : (dataProd.data || [])

    if (dataSet) {
      if (dataSet.featured_trending) settings.value.featured_trending = dataSet.featured_trending
      if (dataSet.featured_bestseller) settings.value.featured_bestseller = dataSet.featured_bestseller
      if (dataSet.featured_recommended) settings.value.featured_recommended = dataSet.featured_recommended
    }

  } catch (error) {
    console.error("Gagal menarik data kategori:", error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchAllData()
})

const getCuratedList = (settingsKey: string) => {
  try {
    const ids = JSON.parse(settings.value[settingsKey] || '[]')
    if (!Array.isArray(ids)) return []
    return ids.map(id => allProducts.value.find(p => p.id == id)).filter(p => p && p.is_featured)
  } catch (e) {
    return []
  }
}

const trendingProducts = computed(() => getCuratedList('featured_trending'))
const bestsellerProducts = computed(() => getCuratedList('featured_bestseller'))
const recommendedProducts = computed(() => getCuratedList('featured_recommended'))

const route = useRoute()
const router = useRouter()

watch(() => route.query, (query) => {
  if (query.filter) {
    activeCategoryId.value = query.filter as string
  } else if (query.categoryId) {
    activeCategoryId.value = Number(query.categoryId)
  }
}, { immediate: true, deep: true })

const displayedProducts = computed(() => {
  let products = allProducts.value
  
  if (activeCategoryId.value === 'bestseller') {
    products = bestsellerProducts.value
  } else if (activeCategoryId.value === 'recommended') {
    products = recommendedProducts.value
  } else if (activeCategoryId.value === 'trending') {
    products = trendingProducts.value
  } else if (activeCategoryId.value !== 'semua') {
    products = products.filter(p => p.category_id === activeCategoryId.value)
  }

  const query = route.query.q?.toString().toLowerCase()
  if (query) {
    products = products.filter(p => 
        p.name.toLowerCase().includes(query)
    )
  }

  return products
})

const currentPage = ref(1)
const itemsPerPage = ref(12)

const totalPages = computed(() => Math.ceil(displayedProducts.value.length / itemsPerPage.value))

const paginatedProducts = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value
  const end = start + itemsPerPage.value
  return displayedProducts.value.slice(start, end)
})

watch(() => [activeCategoryId.value, route.query.q], () => {
  currentPage.value = 1
})

const activeCategoryName = computed(() => {
  let base = 'Semua Produk'
  if (activeCategoryId.value === 'bestseller') base = 'Produk Terlaris'
  else if (activeCategoryId.value === 'recommended') base = 'Rekomendasi Kami'
  else if (activeCategoryId.value === 'trending') base = 'Banyak Dicari'
  else if (activeCategoryId.value !== 'semua') {
      base = categories.value.find(c => c.id === activeCategoryId.value)?.name || 'Kategori'
  }
  
  if (route.query.q) {
      return `Pencarian "${route.query.q}" di ${base}`
  }
  return base
})

const activeCategoryObj = computed(() => {
  return categories.value.find(c => c.id === activeCategoryId.value)
})

const clearSearch = () => {
    router.replace({ path: route.path, query: { ...route.query, q: undefined } })
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
}

const getProductImage = (p: any) => {
  if (p.image) return p.image
  if (p.galleries && p.galleries.length > 0) return p.galleries[0]
  return '/images/prod_1.png'
}

const getRandomSoldInfo = (id: number) => {
  const ratings = [4.8, 4.9, 5.0, 4.7, 4.6];
  const solds = ['120+', '85', '45', '210', '500+'];
  const pId = id || 0;
  return {
    rating: ratings[pId % ratings.length],
    sold: solds[pId % solds.length]
  }
}
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-6 md:mb-8">
        <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
        <ChevronRight class="w-4 h-4" />
        <span class="text-emerald-700 font-medium">Kategori Produk</span>
      </nav>

      <div v-if="isLoading" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
      </div>

      <!-- Layout Grid -->
      <div v-else class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filter -->
        <aside class="w-full lg:w-64 shrink-0">
          <div class="bg-white rounded-2xl border border-gray-100 p-4 md:p-6 lg:sticky lg:top-28 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
              <Filter class="w-5 h-5 text-emerald-600" /> Filter Kategori
            </h3>
            <ul class="flex lg:flex-col gap-2 overflow-x-auto pb-2 lg:pb-0 lg:overflow-visible">
              <li>
                <button 
                  @click="activeCategoryId = 'semua'"
                  class="whitespace-nowrap lg:whitespace-normal w-auto lg:w-full flex items-center gap-2 lg:gap-3 px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategoryId === 'semua' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <LayoutGrid class="w-4 h-4" :class="activeCategoryId === 'semua' ? 'text-emerald-600' : 'text-gray-400'" />
                  Semua Produk
                </button>
              </li>
              <li v-if="bestsellerProducts.length > 0">
                <button 
                  @click="activeCategoryId = 'bestseller'"
                  class="whitespace-nowrap lg:whitespace-normal w-auto lg:w-full flex items-center gap-2 lg:gap-3 px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategoryId === 'bestseller' ? 'bg-amber-50 text-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <Crown class="w-4 h-4" :class="activeCategoryId === 'bestseller' ? 'text-amber-600' : 'text-gray-400'" />
                  Produk Terlaris
                </button>
              </li>
              <li v-if="recommendedProducts.length > 0">
                <button 
                  @click="activeCategoryId = 'recommended'"
                  class="whitespace-nowrap lg:whitespace-normal w-auto lg:w-full flex items-center gap-2 lg:gap-3 px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategoryId === 'recommended' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <Star class="w-4 h-4" :class="activeCategoryId === 'recommended' ? 'text-emerald-600' : 'text-gray-400'" />
                  Rekomendasi Kami
                </button>
              </li>
              <li v-if="trendingProducts.length > 0">
                <button 
                  @click="activeCategoryId = 'trending'"
                  class="whitespace-nowrap lg:whitespace-normal w-auto lg:w-full flex items-center gap-2 lg:gap-3 px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategoryId === 'trending' ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <TrendingUp class="w-4 h-4" :class="activeCategoryId === 'trending' ? 'text-blue-600' : 'text-gray-400'" />
                  Banyak Dicari
                </button>
              </li>
              <li v-for="cat in categories" :key="cat.id">
                <button 
                  @click="activeCategoryId = cat.id"
                  class="whitespace-nowrap lg:whitespace-normal w-auto lg:w-full flex items-center gap-2 lg:gap-3 px-3 lg:px-4 py-2 lg:py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategoryId === cat.id ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <img v-if="cat.icon_type === 'custom'" :src="cat.icon_value" class="w-4 h-4 object-cover border border-gray-100 rounded-full" />
                  <component v-else :is="getIcon(cat.icon_value)" class="w-4 h-4" :class="activeCategoryId === cat.id ? 'text-emerald-600' : 'text-gray-400'" />
                  <span class="capitalize">{{ cat.name }}</span>
                </button>
              </li>
            </ul>
          </div>
        </aside>

        <!-- Main Content (Products Grid) -->
        <div class="flex-1">
          <!-- All Products Header -->
          <div class="flex items-center justify-between mb-4 border-b border-gray-200 pb-3">
            <h1 class="text-lg md:text-xl font-bold text-gray-900 capitalize flex items-center gap-2">
              <Crown v-if="activeCategoryId === 'bestseller'" class="w-5 h-5 text-amber-600" />
              <Star v-else-if="activeCategoryId === 'recommended'" class="w-5 h-5 text-emerald-600" />
              <TrendingUp v-else-if="activeCategoryId === 'trending'" class="w-5 h-5 text-blue-600" />
              <LayoutGrid v-else-if="activeCategoryId === 'semua'" class="w-5 h-5 text-emerald-600" />
              <template v-else-if="activeCategoryObj">
                 <img v-if="activeCategoryObj.icon_type === 'custom'" :src="activeCategoryObj.icon_value" class="w-5 h-5 object-cover border border-gray-100 rounded-full" />
                 <component v-else :is="getIcon(activeCategoryObj.icon_value)" class="w-5 h-5 text-emerald-600" />
              </template>
              <LayoutGrid v-else class="w-5 h-5 text-gray-400" />
              {{ activeCategoryName }}
            </h1>
            <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-full border border-gray-100 shadow-sm text-xs font-semibold text-gray-600">
              {{ displayedProducts.length }} Produk
            </div>
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-6">
            <RouterLink 
              v-for="product in paginatedProducts" 
              :key="product.id" 
              :to="`/product/${product.id}`"
              class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group"
            >
              <div class="relative h-32 md:h-48 overflow-hidden bg-gray-100">
                <img :src="getProductImage(product)" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div v-if="product.stock <= 0" class="absolute top-3 left-3 bg-red-100 text-red-800 text-[10px] font-bold px-2 py-1 rounded">
                  KOSONG
                </div>
              </div>
              <div class="p-3 md:p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2 truncate">{{ product.name }}</h3>
                <p class="text-emerald-600 font-bold text-sm md:text-lg mb-2 md:mb-3">{{ formatPrice(product.price) }}</p>
                
                <div class="hidden md:flex items-center gap-1 text-gray-500 text-xs mb-3">
                  <MapPin class="w-3.5 h-3.5 shrink-0" />
                  <span class="truncate">{{ product.merchant?.address || 'Purwoasri' }}</span>
                </div>
                

              </div>
            </RouterLink>
          </div>

          <!-- Pagination Controls -->
          <div v-if="totalPages > 1" class="mt-8 flex justify-center items-center gap-1 sm:gap-2">
            <button @click="currentPage--" :disabled="currentPage === 1" class="px-2 sm:px-3 py-1.5 rounded-lg border border-gray-200 text-xs sm:text-sm font-medium transition-colors" :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-gray-600 hover:bg-gray-50'">Prev</button>
            <div class="flex items-center gap-1">
              <button v-for="p in totalPages" :key="p" @click="currentPage = p" class="w-7 h-7 sm:w-8 sm:h-8 flex items-center justify-center rounded-lg text-xs sm:text-sm font-bold transition-colors" :class="currentPage === p ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-50 border border-transparent hover:border-gray-200'">{{ p }}</button>
            </div>
            <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-2 sm:px-3 py-1.5 rounded-lg border border-gray-200 text-xs sm:text-sm font-medium transition-colors" :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed text-gray-400' : 'text-gray-600 hover:bg-gray-50'">Next</button>
          </div>

          <!-- Empty State -->
          <div v-if="displayedProducts.length === 0" class="text-center py-20 bg-white rounded-2xl border border-gray-100 mt-6">
            <LayoutGrid class="w-16 h-16 text-gray-200 mx-auto mb-4" />
            <h3 class="text-lg font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h3>
            <p class="text-gray-500 text-sm">Belum ada produk di kategori ini. Silakan cek kategori lain.</p>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #d1d5db;
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}
</style>
