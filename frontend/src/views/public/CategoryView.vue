<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { 
  ChevronRight, 
  MapPin, 
  Star,
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
const activeCategoryId = ref<number | 'semua'>('semua')

const fetchAllData = async () => {
  isLoading.value = true
  try {
    const [resCat, resProd] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/categories'),
      fetch('http://127.0.0.1:8000/api/products')
    ])
    
    const dataCat = await resCat.json()
    const dataProd = await resProd.json()
    
    categories.value = Array.isArray(dataCat) ? dataCat : (dataCat.data || [])
    allProducts.value = Array.isArray(dataProd) ? dataProd : (dataProd.data || [])

  } catch (error) {
    console.error("Gagal menarik data kategori:", error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchAllData()
})

const displayedProducts = computed(() => {
  if (activeCategoryId.value === 'semua') return allProducts.value
  return allProducts.value.filter(p => p.category_id === activeCategoryId.value)
})

const activeCategoryName = computed(() => {
  if (activeCategoryId.value === 'semua') return 'Semua Produk'
  return categories.value.find(c => c.id === activeCategoryId.value)?.name || 'Kategori'
})

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
    <div class="max-w-7xl mx-auto px-6 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
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
          <div class="bg-white rounded-2xl border border-gray-100 p-6 sticky top-28 shadow-sm">
            <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
              <Filter class="w-5 h-5 text-emerald-600" /> Filter Kategori
            </h3>
            <ul class="space-y-2">
              <li>
                <button 
                  @click="activeCategoryId = 'semua'"
                  class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"
                  :class="activeCategoryId === 'semua' ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
                >
                  <LayoutGrid class="w-4 h-4" :class="activeCategoryId === 'semua' ? 'text-emerald-600' : 'text-gray-400'" />
                  Semua Produk
                </button>
              </li>
              <li v-for="cat in categories" :key="cat.id">
                <button 
                  @click="activeCategoryId = cat.id"
                  class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-colors"
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
          <!-- Header -->
          <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900 capitalize">
              {{ activeCategoryName }}
            </h1>
            <span class="text-sm text-gray-500">
              Menampilkan {{ displayedProducts.length }} produk
            </span>
          </div>

          <!-- Products Grid -->
          <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6">
            <RouterLink 
              v-for="product in displayedProducts" 
              :key="product.id" 
              :to="`/product/${product.id}`"
              class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group"
            >
              <div class="relative h-48 overflow-hidden bg-gray-100">
                <img :src="getProductImage(product)" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                <div v-if="product.stock > 0" class="absolute top-3 left-3 bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-1 rounded">
                  READY STOCK
                </div>
                <div v-else class="absolute top-3 left-3 bg-red-100 text-red-800 text-[10px] font-bold px-2 py-1 rounded">
                  KOSONG
                </div>
              </div>
              <div class="p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2 truncate">{{ product.name }}</h3>
                <p class="text-emerald-600 font-bold text-lg mb-3">{{ formatPrice(product.price) }}</p>
                
                <div class="flex items-center gap-1 text-gray-500 text-xs mb-3">
                  <MapPin class="w-3.5 h-3.5 shrink-0" />
                  <span class="truncate">{{ product.merchant?.address || 'Purwoasri' }}</span>
                </div>
                

              </div>
            </RouterLink>
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
