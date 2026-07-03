<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { 
  Leaf, 
  Croissant, 
  Shirt, 
  Tractor, 
  Sofa, 
  LayoutGrid, 
  MapPin, 
  Star,
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

// Database State
const categories = ref<any[]>([])
const merchants = ref<any[]>([])
const allProducts = ref<any[]>([])
const settings = ref<any>({
  hero_headline: 'Produk Lokal Terbaik Desa',
  hero_subtitle: 'Dukung pengrajin dan petani lokal Desa Purwoasri dengan produk berkualitas tinggi langsung dari sumbernya.',
  hero_image: '/images/hero.png',
  promo_is_active: false,
  promo_title: '',
  promo_link: '',
  promo_image: '',
  featured_trending: '[]',
  featured_bestseller: '[]',
  featured_recommended: '[]'
})

// Loading State
const isLoading = ref(true)

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
  return iconMap[iconName] || LayoutGrid
}

const fetchAllData = async () => {
  isLoading.value = true
  try {
    const [resSet, resCat, resProd, resMer] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/settings'),
      fetch('http://127.0.0.1:8000/api/categories'),
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/merchants')
    ])
    
    const dataSet = await resSet.json()
    const dataCat = await resCat.json()
    const dataProd = await resProd.json()
    const dataMer = await resMer.json()
    
    // Set Settings for Curated Products Only
    if (dataSet) {
      if (dataSet.featured_trending) settings.value.featured_trending = dataSet.featured_trending
      if (dataSet.featured_bestseller) settings.value.featured_bestseller = dataSet.featured_bestseller
      if (dataSet.featured_recommended) settings.value.featured_recommended = dataSet.featured_recommended
    }

    categories.value = Array.isArray(dataCat) ? dataCat : (dataCat.data || [])
    allProducts.value = Array.isArray(dataProd) ? dataProd : (dataProd.data || [])
    merchants.value = Array.isArray(dataMer) ? dataMer : (dataMer.data || [])

  } catch (error) {
    console.error("Gagal menarik data utama:", error)
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

const features = [
  {
    title: 'Pengiriman Aman',
    desc: 'Layanan pengiriman lokal yang handal langsung ke depan pintu Anda.',
    icon: Truck
  },
  {
    title: 'Kualitas Terjamin',
    desc: 'Semua produk telah melewati kurasi standar desa yang ketat.',
    icon: ShieldCheck
  },
  {
    title: 'Layanan 24/7',
    desc: 'Siap membantu Anda setiap saat melalui pusat bantuan kami.',
    icon: Clock
  }
]

const getProductImage = (p: any) => {
  if (p.image) return p.image
  if (p.galleries && p.galleries.length > 0) return p.galleries[0]
  return '/images/prod_1.png'
}

const getMerchantImage = (m: any) => {
  return m.storefront_image || '/images/merchant.png'
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
}

const getRandomSoldInfo = (id: number) => {
  // Hanya mockup dummy untuk rating karena tidak ada di DB aslinya
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
  <main class="min-h-screen bg-gray-50 pb-20">
    
    <div v-if="isLoading" class="min-h-[500px] flex items-center justify-center">
      <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
    </div>
    
    <div v-else>
      <!-- Hero Section -->
      <div class="px-4 md:px-12 pt-4 md:pt-8">
        <div class="relative w-full h-[250px] md:h-[450px] rounded-2xl overflow-hidden shadow-lg">
          <img :src="settings.hero_image" alt="Hero Banner" class="absolute inset-0 w-full h-full object-cover object-center" />
          <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
          <div class="absolute inset-0 flex flex-col justify-center px-6 md:px-16 max-w-2xl">
            <h1 class="text-2xl md:text-5xl font-bold text-white leading-tight mb-3 md:mb-4">
              {{ settings.hero_headline }}
            </h1>
            <p class="text-gray-200 text-sm md:text-lg mb-5 md:mb-8 leading-relaxed line-clamp-3 md:line-clamp-none">
              {{ settings.hero_subtitle }}
            </p>
            <div>
              <RouterLink to="/category" class="inline-block bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-2.5 md:py-3 px-6 md:px-8 rounded-lg shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5 text-sm md:text-base">
                Belanja Sekarang
              </RouterLink>
            </div>
          </div>
        </div>
      </div>

      <!-- Categories Section -->
      <div v-if="categories.length" class="px-4 md:px-12 mt-8 md:mt-12">
        <div class="flex items-center justify-start gap-8 md:gap-16 overflow-x-auto pb-4 hide-scrollbar">
          <RouterLink :to="`/category?categoryId=${cat.id}`" v-for="cat in categories" :key="cat.id" class="flex flex-col items-center gap-3 cursor-pointer group min-w-[80px]">
            <div class="w-16 h-16 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-emerald-600 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-colors overflow-hidden">
               <img v-if="cat.icon_type === 'custom'" :src="cat.icon_value" class="w-full h-full object-cover rounded-full" />
               <component v-else :is="getIcon(cat.icon_value)" class="w-7 h-7" />
            </div>
            <span class="text-xs font-semibold text-gray-700 group-hover:text-emerald-700 transition-colors capitalize">{{ cat.name }}</span>
          </RouterLink>
        </div>
      </div>

      <!-- Produk Terlaris Section (Using Bestseller filter from DB) -->
      <div v-if="bestsellerProducts.length" class="px-4 md:px-12 mt-10 md:mt-16">
        <div class="flex items-end justify-between mb-5 md:mb-8">
          <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">Produk Terlaris</h2>
            <p class="text-gray-500 text-sm">Koleksi favorit pelanggan minggu ini</p>
          </div>
          <RouterLink to="/category?filter=bestseller" class="text-emerald-600 font-semibold text-sm hover:text-emerald-700">Lihat Semua</RouterLink>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-6">
          <RouterLink :to="`/product/${p.id}`" v-for="(p, idx) in bestsellerProducts.slice(0, 5)" :key="p.id" class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group">
            <div class="relative h-32 md:h-48 overflow-hidden bg-gray-100">
              <img :src="getProductImage(p)" :alt="p.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div v-if="p.stock <= 0" class="absolute top-2 left-2 md:top-3 md:left-3 bg-red-100 text-red-800 text-[9px] md:text-[10px] font-bold px-1.5 md:px-2 py-0.5 md:py-1 rounded">
                KOSONG
              </div>
            </div>
            <div class="p-3 md:p-4">
              <h3 class="text-xs md:text-sm font-medium text-gray-900 mb-1.5 md:mb-2 truncate">{{ p.name }}</h3>
              <p class="text-emerald-600 font-bold text-sm md:text-lg mb-2 md:mb-3">{{ formatPrice(p.price) }}</p>
              
              <div class="hidden md:flex items-center gap-1 text-gray-500 text-xs mb-3">
                <MapPin class="w-3.5 h-3.5" />
                <span class="truncate">{{ p.merchant?.address || 'Purwoasri, Kediri' }}</span>
              </div>
              

            </div>
          </RouterLink>
        </div>
      </div>

      <!-- Produk Rekomendasi Section -->
      <div v-if="recommendedProducts.length" class="px-4 md:px-12 mt-10 md:mt-16">
        <div class="flex items-end justify-between mb-5 md:mb-8">
          <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">Rekomendasi Kami</h2>
            <p class="text-gray-500 text-xs md:text-sm">Produk kurasi terbaik dari admin</p>
          </div>
          <RouterLink to="/category?filter=recommended" class="text-emerald-600 font-semibold text-sm hover:text-emerald-700">Lihat Semua</RouterLink>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-6">
          <RouterLink :to="`/product/${p.id}`" v-for="(p, idx) in recommendedProducts.slice(0, 5)" :key="p.id" class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group">
            <div class="relative h-32 md:h-48 overflow-hidden bg-gray-100">
              <img :src="getProductImage(p)" :alt="p.name" class="w-full h-full object-cover" />
            </div>
            <div class="p-3 md:p-4">
              <h3 class="text-xs md:text-sm font-medium text-gray-900 mb-1.5 md:mb-2 truncate">{{ p.name }}</h3>
              <p class="text-emerald-600 font-bold text-sm md:text-lg mb-2 md:mb-3">{{ formatPrice(p.price) }}</p>
              
              <div class="hidden md:flex items-center gap-1 text-gray-500 text-xs mb-3">
                <MapPin class="w-3.5 h-3.5" />
                <span class="truncate">{{ p.merchant?.address || 'Purwoasri, Kediri' }}</span>
              </div>
              

            </div>
          </RouterLink>
        </div>
      </div>

      <!-- Produk Sedang Trending Section -->
      <div v-if="trendingProducts.length" class="px-4 md:px-12 mt-10 md:mt-16">
        <div class="flex items-end justify-between mb-5 md:mb-8">
          <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">Banyak Dicari</h2>
            <p class="text-gray-500 text-xs md:text-sm">Produk yang sedang trending saat ini</p>
          </div>
          <RouterLink to="/category?filter=trending" class="text-emerald-600 font-semibold text-sm hover:text-emerald-700">Lihat Semua</RouterLink>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-6">
          <RouterLink :to="`/product/${p.id}`" v-for="(p, idx) in trendingProducts.slice(0, 5)" :key="p.id" class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group">
            <div class="relative h-32 md:h-48 overflow-hidden bg-gray-100">
              <img :src="getProductImage(p)" :alt="p.name" class="w-full h-full object-cover" />
            </div>
            <div class="p-3 md:p-4">
              <h3 class="text-xs md:text-sm font-medium text-gray-900 mb-1.5 md:mb-2 truncate">{{ p.name }}</h3>
              <p class="text-emerald-600 font-bold text-sm md:text-lg mb-2 md:mb-3">{{ formatPrice(p.price) }}</p>
              
              <div class="hidden md:flex items-center gap-1 text-gray-500 text-xs mb-3">
                <MapPin class="w-3.5 h-3.5" />
                <span class="truncate">{{ p.merchant?.address || 'Purwoasri, Kediri' }}</span>
              </div>
              

            </div>
          </RouterLink>
        </div>
      </div>

      <!-- Merchant Pilihan Section -->
      <div v-if="merchants.length" class="px-4 md:px-12 mt-12 md:mt-20">
        <div class="flex items-center gap-2 mb-5 md:mb-8">
          <CheckCircle2 class="w-5 md:w-6 h-5 md:h-6 text-emerald-600" />
          <h2 class="text-xl md:text-2xl font-bold text-gray-900">Merchant Pilihan</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
          <!-- Main Merchant Card (Index 0) -->
          <RouterLink :to="`/merchant/${merchants[0].id}`" v-if="merchants[0]" class="block lg:col-span-2 relative rounded-2xl overflow-hidden h-[200px] md:h-[300px] shadow-lg group cursor-pointer">
            <img :src="getMerchantImage(merchants[0])" :alt="merchants[0].name" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 p-5 md:p-8 w-full">
              <div class="bg-emerald-500/90 backdrop-blur text-white text-[9px] md:text-[10px] font-bold px-2.5 md:px-3 py-1 md:py-1.5 rounded-full inline-flex items-center gap-1.5 mb-2 md:mb-3 uppercase tracking-wider">
                <Star class="w-3 h-3 fill-white" /> Local Merchant Terbaik
              </div>
              <h3 class="text-xl md:text-3xl font-bold text-white mb-1.5 md:mb-2">{{ merchants[0].name }}</h3>
              <p class="text-gray-200 text-xs md:text-sm max-w-md mb-4 md:mb-6 leading-relaxed line-clamp-2">{{ merchants[0].description || 'Spesialis produk desa dengan kualitas ekspor.' }}</p>
              <button class="bg-white text-gray-900 font-semibold px-4 md:px-6 py-2 md:py-2.5 rounded-lg text-xs md:text-sm hover:bg-gray-50 transition-colors">
                Lihat Toko
              </button>
            </div>
          </RouterLink>

          <!-- Smaller Merchant Cards (Index 1 & 2) -->
          <div class="flex flex-col gap-4 md:gap-6">
            <RouterLink :to="`/merchant/${m.id}`" v-for="(m, idx) in merchants.slice(1, 3)" :key="m.id" class="block bg-white rounded-2xl p-4 md:p-6 border border-gray-100 hover:shadow-lg transition-shadow cursor-pointer relative overflow-hidden group">
              <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3 md:mb-4 group-hover:bg-emerald-100 transition-colors">
                <img v-if="m.logo || m.image" :src="m.logo || m.image" class="w-7 h-7 md:w-8 md:h-8 rounded-full object-cover" />
                <Store v-else class="w-5 h-5 md:w-6 md:h-6" />
              </div>
              <h4 class="text-base md:text-lg font-bold text-gray-900 mb-1.5 md:mb-2">{{ m.name }}</h4>
              <p class="text-gray-500 text-xs md:text-sm mb-4 md:mb-6 leading-relaxed line-clamp-2">{{ m.description || 'Pilar utama produk lokal berkualitas.' }}</p>
              <div class="flex items-center gap-2 text-emerald-600 font-semibold text-sm border-t border-gray-50 pt-2">
                Kunjungi Merchant <ArrowRight class="w-4 h-4" />
              </div>
              <LayoutGrid class="absolute -bottom-4 -right-4 w-32 h-32 text-gray-50 opacity-50 group-hover:scale-110 transition-transform duration-500 pointer-events-none" />
            </RouterLink>
          </div>
        </div>
      </div>

      <!-- Features Section -->
      <div class="px-4 md:px-12 mt-12 md:mt-20">
        <div class="bg-white border border-gray-100 rounded-2xl p-5 md:p-8 shadow-sm">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <div v-for="(feat, i) in features" :key="i" class="flex gap-3 md:gap-4 items-start pt-4 md:pt-0 first:pt-0 md:px-6 first:px-0">
              <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0">
                <component :is="feat.icon" class="w-5 h-5 md:w-6 md:h-6" />
              </div>
              <div>
                <h4 class="font-bold text-gray-900 mb-1 text-sm md:text-base">{{ feat.title }}</h4>
                <p class="text-xs md:text-sm text-gray-500 leading-relaxed">{{ feat.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
</template>

<style>
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
.hide-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
