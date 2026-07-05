<script setup lang="ts">
import { ref, onMounted, computed, watch, nextTick } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { Search, X as XIcon, Store, ShoppingBag, Menu } from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()
const searchQuery = ref(route.query.q?.toString() || '')

const allProducts = ref<any[]>([])
const allMerchants = ref<any[]>([])
const showDropdown = ref(false)
const isNavigating = ref(false)
const mobileMenuOpen = ref(false)
const mobileSearchOpen = ref(false)
const mobileSearchInput = ref<HTMLInputElement | null>(null)

onMounted(async () => {
  try {
    const [prodRes, merchantRes] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/merchants')
    ])
    if (prodRes.ok) {
      const data = await prodRes.json()
      allProducts.value = Array.isArray(data) ? data : (data.data || [])
    }
    if (merchantRes.ok) {
      allMerchants.value = await merchantRes.json()
    }
  } catch (e) {
    console.error("Gagal load data pencarian", e)
  }
})

const getProductImage = (p: any) => {
  if (p.image) return p.image
  if (p.galleries && p.galleries.length) return p.galleries[0]
  return '/images/prod_1.png'
}

// Konteks halaman saat ini
const isOnCategory = computed(() => route.path === '/category')
const isOnStores = computed(() => route.path === '/stores')
const showProducts = computed(() => !isOnStores.value)
const showMerchants = computed(() => !isOnCategory.value)

const productResults = computed(() => {
  if (!showProducts.value) return []
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return []
  return allProducts.value.filter(p =>
    p.name.toLowerCase().includes(q)
  ).slice(0, 5)
})

const merchantResults = computed(() => {
  if (!showMerchants.value) return []
  const q = searchQuery.value.toLowerCase().trim()
  if (!q) return []
  return allMerchants.value.filter(m =>
    m.name.toLowerCase().includes(q)
  ).slice(0, 5)
})

const hasResults = computed(() => productResults.value.length || merchantResults.value.length)

const searchPlaceholder = computed(() => {
  if (isOnCategory.value) return 'Cari produk...'
  if (isOnStores.value) return 'Cari toko...'
  return 'Cari produk atau toko...'
})

// Real-time auto sync ke URL
watch(searchQuery, (newVal) => {
  if (isNavigating.value) return
  showDropdown.value = newVal.trim().length > 0

  if (route.path === '/category') {
    router.replace({ path: '/category', query: { ...route.query, q: newVal.trim() || undefined } })
  }
  if (route.path === '/stores') {
    router.replace({ path: '/stores', query: { ...route.query, q: newVal.trim() || undefined } })
  }
})

watch(() => route.query.q, (newQ) => {
  if (isNavigating.value) return
  searchQuery.value = newQ?.toString() || ''
})

// Tutup mobile menu saat navigasi
watch(() => route.path, () => {
  mobileMenuOpen.value = false
  mobileSearchOpen.value = false
  showDropdown.value = false
})

const handleSearch = () => {
  if (searchQuery.value.trim()) {
    showDropdown.value = false
    mobileSearchOpen.value = false
    router.push({ path: '/category', query: { q: searchQuery.value.trim() } })
  }
}

const handleBlur = () => {
  setTimeout(() => {
    showDropdown.value = false
  }, 200)
}

const clearSearch = () => {
  searchQuery.value = ''
  showDropdown.value = false
}

const navigateToProduct = (id: string) => {
  isNavigating.value = true
  showDropdown.value = false
  searchQuery.value = ''
  mobileSearchOpen.value = false
  mobileMenuOpen.value = false
  router.push(`/product/${id}`).then(() => {
    isNavigating.value = false
  })
}

const navigateToMerchant = (id: number) => {
  isNavigating.value = true
  showDropdown.value = false
  searchQuery.value = ''
  mobileSearchOpen.value = false
  mobileMenuOpen.value = false
  router.push(`/merchant/${id}`).then(() => {
    isNavigating.value = false
  })
}

const isActive = (path: string) => {
  return route.path === path || route.path.startsWith(path + '/')
}

const toggleMobileSearch = () => {
  mobileSearchOpen.value = !mobileSearchOpen.value
  mobileMenuOpen.value = false
  if (mobileSearchOpen.value) {
    nextTick(() => {
      mobileSearchInput.value?.focus()
    })
  }
}

const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value
  mobileSearchOpen.value = false
}
</script>

<template>
  <nav class="bg-white/80 backdrop-blur-xl border-b border-gray-200/50 sticky top-0 z-50 transition-all shadow-sm">
    <!-- Main Bar -->
    <div class="py-3 px-4 md:py-3.5 md:px-12 flex items-center justify-between gap-4">
      <!-- Logo -->
      <div class="flex items-center shrink-0">
        <RouterLink to="/" class="flex items-center font-extrabold text-2xl md:text-[28px] tracking-tight hover:scale-105 transition-transform duration-300">
          <img src="/images/logo.png" alt="Logo" class="w-14 h-14 md:w-16 md:h-16 object-contain drop-shadow-md transform scale-125 md:scale-150" />
          <div class="-ml-1 md:-ml-2 z-10">
            <span class="text-[#00478F]">Purwo</span><span class="text-[#7AB538]">mart</span>
          </div>
        </RouterLink>
      </div>

      <!-- Nav Links (Desktop) - Pill Style -->
      <div class="hidden lg:flex items-center gap-1.5 text-sm font-medium bg-gray-50/80 p-1.5 rounded-full border border-gray-100/80">
        <RouterLink 
          to="/category" 
          class="px-5 py-2.5 rounded-full transition-all duration-300 select-none"
          :class="isActive('/category') ? 'bg-white text-emerald-700 font-bold shadow-sm ring-1 ring-gray-100' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50'"
        >Kategori</RouterLink>
        <RouterLink 
          to="/stores" 
          class="px-5 py-2.5 rounded-full transition-all duration-300 select-none"
          :class="isActive('/stores') ? 'bg-white text-emerald-700 font-bold shadow-sm ring-1 ring-gray-100' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50'"
        >Toko</RouterLink>
        <RouterLink 
          to="/about" 
          class="px-5 py-2.5 rounded-full transition-all duration-300 select-none"
          :class="isActive('/about') ? 'bg-white text-emerald-700 font-bold shadow-sm ring-1 ring-gray-100' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50'"
        >Tentang Kami</RouterLink>
        <RouterLink 
          to="/event" 
          class="px-5 py-2.5 rounded-full transition-all duration-300 select-none"
          :class="isActive('/event') ? 'bg-white text-emerald-700 font-bold shadow-sm ring-1 ring-gray-100' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50'"
        >Event</RouterLink>
        <RouterLink 
          to="/help" 
          class="px-5 py-2.5 rounded-full transition-all duration-300 select-none"
          :class="isActive('/help') ? 'bg-white text-emerald-700 font-bold shadow-sm ring-1 ring-gray-100' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50'"
        >Bantuan</RouterLink>
      </div>

      <!-- Desktop Search Bar -->
      <div class="hidden md:flex flex-1 max-w-sm xl:max-w-md ml-auto relative">
        <div class="relative w-full group">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <Search class="h-4 w-4 text-emerald-600/60 group-focus-within:text-emerald-600 transition-colors" />
          </div>
          <input 
            v-model="searchQuery"
            @keyup.enter="handleSearch"
            @focus="searchQuery.trim() ? showDropdown = true : null"
            @blur="handleBlur"
            type="text" 
            class="block w-full pl-11 pr-10 py-2.5 border border-gray-200/80 rounded-full leading-5 bg-gray-50/50 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 sm:text-sm transition-all duration-300 hover:border-gray-300" 
            :placeholder="searchPlaceholder" 
          />
          <button 
              v-if="searchQuery" 
              @click="clearSearch"
              class="absolute inset-y-0 right-0 pr-3 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors"
          >
              <XIcon class="h-4 w-4" />
          </button>

          <!-- Desktop Dropdown -->
          <div 
            v-if="showDropdown && hasResults" 
            @mousedown.prevent
            class="absolute top-full mt-3 w-full bg-white/95 backdrop-blur-xl rounded-2xl shadow-xl shadow-emerald-900/10 border border-gray-100 overflow-hidden z-50 flex flex-col ring-1 ring-black/5"
          >
              <div v-if="productResults.length">
                  <div class="px-4 pt-3 pb-2 flex items-center gap-2">
                      <ShoppingBag class="w-4 h-4 text-emerald-600" />
                      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Produk</span>
                  </div>
                  <div 
                      v-for="prod in productResults" 
                      :key="'p-' + prod.id"
                      @click.prevent="navigateToProduct(prod.id)"
                      class="flex items-center gap-3 px-4 py-2.5 hover:bg-emerald-50 cursor-pointer transition-colors"
                  >
                      <img :src="getProductImage(prod)" class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-200" alt="" />
                      <div class="flex-1 min-w-0">
                          <h4 class="text-gray-900 font-semibold text-sm truncate">{{ prod.name }}</h4>
                          <p class="text-gray-400 text-xs truncate">{{ prod.merchant?.name || 'Toko UMKM' }}</p>
                      </div>
                  </div>
              </div>
              <div v-if="productResults.length && merchantResults.length" class="border-t border-gray-100 mx-4"></div>
              <div v-if="merchantResults.length">
                  <div class="px-4 pt-3 pb-2 flex items-center gap-2">
                      <Store class="w-4 h-4 text-blue-600" />
                      <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Toko</span>
                  </div>
                  <div 
                      v-for="m in merchantResults" 
                      :key="'m-' + m.id"
                      @click.prevent="navigateToMerchant(m.id)"
                      class="flex items-center gap-3 px-4 py-2.5 hover:bg-blue-50 cursor-pointer transition-colors"
                  >
                      <img :src="m.logo || '/images/bumn.png'" class="w-10 h-10 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-200" alt="" />
                      <div class="flex-1 min-w-0">
                          <h4 class="text-gray-900 font-semibold text-sm truncate">{{ m.name }}</h4>
                          <p class="text-gray-400 text-xs truncate">{{ m.address || 'Purwoasri, Kediri' }}</p>
                      </div>
                  </div>
              </div>
          </div>

          <div 
            v-else-if="showDropdown && searchQuery.trim().length && !hasResults" 
            class="absolute top-full mt-2 w-full bg-white rounded-xl shadow-2xl border border-gray-200 p-4 text-center z-50"
          >
              <p class="text-sm text-gray-400">Pencarian tidak ditemukan.</p>
          </div>
        </div>
      </div>

      <!-- Mobile: Search + Hamburger -->
      <div class="flex lg:hidden items-center gap-2.5">
        <button 
          @click="toggleMobileSearch" 
          class="w-10 h-10 flex items-center justify-center rounded-full text-gray-700 hover:bg-gray-100 transition-all active:scale-95"
          :class="mobileSearchOpen ? 'bg-emerald-100 text-emerald-700 shadow-inner' : 'bg-gray-50 border border-gray-200'"
        >
          <Search v-if="!mobileSearchOpen" class="w-5 h-5" />
          <XIcon v-else class="w-5 h-5" />
        </button>
        <button 
          @click="toggleMobileMenu" 
          class="w-10 h-10 flex items-center justify-center rounded-full text-gray-700 hover:bg-gray-100 transition-all active:scale-95"
          :class="mobileMenuOpen ? 'bg-emerald-100 text-emerald-700 shadow-inner' : 'bg-gray-50 border border-gray-200'"
        >
          <XIcon v-if="mobileMenuOpen" class="w-5 h-5" />
          <Menu v-else class="w-5 h-5" />
        </button>
      </div>

      <!-- Desktop placeholder -->
      <div class="hidden lg:block w-8 shrink-0"></div>
    </div>

    <!-- Mobile Search Bar (slide down) -->
    <div 
      v-if="mobileSearchOpen" 
      class="lg:hidden px-4 pb-4 pt-1 border-t border-gray-100 bg-white/90 backdrop-blur-lg"
    >
      <div class="relative mt-2">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <Search class="h-4 w-4 text-emerald-600/60" />
        </div>
        <input 
          ref="mobileSearchInput"
          v-model="searchQuery"
          @keyup.enter="handleSearch"
          @focus="searchQuery.trim() ? showDropdown = true : null"
          type="text" 
          class="block w-full pl-11 pr-10 py-3 border border-gray-200/80 rounded-full leading-5 bg-white placeholder-gray-400 focus:outline-none focus:border-emerald-400 focus:ring-4 focus:ring-emerald-500/10 text-sm transition-all shadow-sm" 
          :placeholder="searchPlaceholder" 
        />
        <button 
          v-if="searchQuery" 
          @click="clearSearch"
          class="absolute inset-y-0 right-0 pr-3 flex items-center justify-center text-gray-400 hover:text-gray-600"
        >
          <XIcon class="h-4 w-4" />
        </button>
      </div>

      <!-- Mobile Search Results -->
      <div 
        v-if="showDropdown && hasResults" 
        class="mt-2 bg-white rounded-xl border border-gray-200 overflow-hidden shadow-lg"
      >
          <div v-if="productResults.length">
              <div class="px-4 pt-3 pb-1.5 flex items-center gap-2">
                  <ShoppingBag class="w-3.5 h-3.5 text-emerald-600" />
                  <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Produk</span>
              </div>
              <div 
                  v-for="prod in productResults" 
                  :key="'mp-' + prod.id"
                  @click="navigateToProduct(prod.id)"
                  class="flex items-center gap-3 px-4 py-2.5 active:bg-emerald-50 cursor-pointer transition-colors"
              >
                  <img :src="getProductImage(prod)" class="w-9 h-9 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-200" alt="" />
                  <div class="flex-1 min-w-0">
                      <h4 class="text-gray-900 font-semibold text-sm truncate">{{ prod.name }}</h4>
                      <p class="text-gray-400 text-xs truncate">{{ prod.merchant?.name || 'Toko UMKM' }}</p>
                  </div>
              </div>
          </div>
          <div v-if="productResults.length && merchantResults.length" class="border-t border-gray-100 mx-4"></div>
          <div v-if="merchantResults.length">
              <div class="px-4 pt-3 pb-1.5 flex items-center gap-2">
                  <Store class="w-3.5 h-3.5 text-blue-600" />
                  <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Toko</span>
              </div>
              <div 
                  v-for="m in merchantResults" 
                  :key="'mm-' + m.id"
                  @click="navigateToMerchant(m.id)"
                  class="flex items-center gap-3 px-4 py-2.5 active:bg-blue-50 cursor-pointer transition-colors"
              >
                  <img :src="m.logo || '/images/bumn.png'" class="w-9 h-9 rounded-lg object-cover bg-gray-100 shrink-0 border border-gray-200" alt="" />
                  <div class="flex-1 min-w-0">
                      <h4 class="text-gray-900 font-semibold text-sm truncate">{{ m.name }}</h4>
                      <p class="text-gray-400 text-xs truncate">{{ m.address || 'Purwoasri, Kediri' }}</p>
                  </div>
              </div>
          </div>
      </div>
      <div 
        v-else-if="showDropdown && searchQuery.trim().length && !hasResults" 
        class="mt-2 bg-white rounded-xl border border-gray-200 p-4 text-center"
      >
          <p class="text-sm text-gray-400">Pencarian tidak ditemukan.</p>
      </div>
    </div>

    <!-- Mobile Menu (slide down) -->
    <div 
      v-if="mobileMenuOpen" 
      class="lg:hidden border-t border-gray-100 bg-white/95 backdrop-blur-xl absolute w-full shadow-lg"
    >
      <div class="flex flex-col py-2">
        <RouterLink 
          to="/category" 
          class="flex items-center gap-3 px-5 py-3 text-sm font-medium transition-colors"
          :class="isActive('/category') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50 border-l-4 border-transparent'"
        >
          <ShoppingBag class="w-4 h-4" />
          Kategori Produk
        </RouterLink>
        <RouterLink 
          to="/stores" 
          class="flex items-center gap-3 px-5 py-3 text-sm font-medium transition-colors"
          :class="isActive('/stores') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50 border-l-4 border-transparent'"
        >
          <Store class="w-4 h-4" />
          Toko UMKM
        </RouterLink>
        <RouterLink 
          to="/about" 
          class="flex items-center gap-3 px-5 py-3 text-sm font-medium transition-colors"
          :class="isActive('/about') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50 border-l-4 border-transparent'"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Tentang Kami
        </RouterLink>
        <RouterLink 
          to="/event" 
          class="flex items-center gap-3 px-5 py-3 text-sm font-medium transition-colors"
          :class="isActive('/event') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50 border-l-4 border-transparent'"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Event
        </RouterLink>
        <RouterLink 
          to="/help" 
          class="flex items-center gap-3 px-5 py-3 text-sm font-medium transition-colors"
          :class="isActive('/help') ? 'text-emerald-600 bg-emerald-50 border-l-4 border-emerald-600' : 'text-gray-700 hover:bg-gray-50 border-l-4 border-transparent'"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Bantuan
        </RouterLink>
      </div>
    </div>
  </nav>
</template>
