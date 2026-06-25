<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { Store, MapPin, ChevronRight, Package, Search } from 'lucide-vue-next'

const route = useRoute()

const merchants = ref<any[]>([])
const isLoading = ref(true)

onMounted(async () => {
    try {
        const res = await fetch('http://127.0.0.1:8000/api/merchants')
        if (res.ok) {
            merchants.value = await res.json()
        }
    } catch (e) {
        console.error(e)
    } finally {
        isLoading.value = false
    }
})

const displayedMerchants = computed(() => {
    const query = route.query.q?.toString().toLowerCase()
    if (!query) return merchants.value
    return merchants.value.filter(m =>
        m.name.toLowerCase().includes(query)
    )
})

const displayTitle = computed(() => {
    if (route.query.q) {
        return `Pencarian "${route.query.q}" di Toko UMKM`
    }
    return 'Daftar Toko UMKM Desa'
})
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-10">
    <div class="max-w-7xl mx-auto px-4 md:px-12">
      
      <!-- Header -->
      <div class="mb-10 text-center max-w-2xl mx-auto">
        <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 mb-3 md:mb-4">{{ displayTitle }}</h1>
        <p class="text-gray-500 leading-relaxed text-sm md:text-base">
          Temukan berbagai pengrajin lokal dan toko binaan Desa Purwoasri. Dukung usaha mereka dengan membeli produk unggulan langsung dari sumbernya.
        </p>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
      </div>

      <!-- Empty State -->
      <div v-else-if="displayedMerchants.length === 0" class="text-center py-20 bg-white rounded-2xl border border-gray-100">
         <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
           <Store class="w-8 h-8 text-gray-400" />
         </div>
         <h3 class="text-lg font-bold text-gray-900 mb-2" v-if="route.query.q">Tidak ada toko yang cocok</h3>
         <h3 class="text-lg font-bold text-gray-900 mb-2" v-else>Belum Ada Toko Terdaftar</h3>
         <p class="text-gray-500 text-sm max-w-sm mx-auto" v-if="route.query.q">Coba kata kunci pencarian yang berbeda.</p>
         <p class="text-gray-500 text-sm max-w-sm mx-auto" v-else>Saat ini belum ada data UMKM yang terdaftar di sistem. Silakan kembali lagi nanti.</p>
      </div>

      <!-- Merchant Grid -->
      <div v-else>
        <div class="flex items-center justify-between mb-6">
          <span class="text-sm text-gray-500">Menampilkan {{ displayedMerchants.length }} toko</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
          <RouterLink 
            v-for="m in displayedMerchants" 
            :key="m.id" 
            :to="`/merchant/${m.id}`" 
            class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-xl transition-shadow flex flex-col group"
          >
            <!-- Banner Image -->
            <div class="h-32 bg-gray-900 relative">
               <img :src="m.storefront_image || '/images/merchant.png'" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" />
            </div>
            
            <div class="px-4 pb-4 md:px-5 md:pb-5 relative flex-1 flex flex-col">
               <!-- Logo Avatar (Overlapping) -->
               <div class="-mt-10 w-14 h-14 md:w-16 md:h-16 rounded-xl border-4 border-white overflow-hidden bg-white shadow-sm shrink-0 mb-3 z-10 relative">
                  <img :src="m.logo || '/images/bumn.png'" class="w-full h-full object-cover" />
               </div>
               
               <!-- Content -->
               <h3 class="font-extrabold text-gray-900 text-base md:text-lg line-clamp-1 mb-1">{{ m.name }}</h3>
               <div class="flex items-center gap-1.5 text-xs text-gray-500 mb-3 font-medium">
                  <MapPin class="w-3.5 h-3.5 shrink-0" /> <span class="truncate">{{ m.address || 'Purwoasri, Kediri' }}</span>
               </div>
               <p class="text-sm text-gray-600 line-clamp-2 mb-4 md:mb-6 flex-1">
                  {{ m.description || 'Toko UMKM unggulan desa Purwoasri yang berfokus memajukan potensi sumber daya lokal.' }}
               </p>
               
               <!-- Footer -->
               <div class="mt-auto border-t border-gray-100 pt-4 flex items-center justify-between">
                  <span class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                     <Store class="w-3.5 h-3.5" /> <span class="truncate max-w-[100px]">{{ m.owner_name || 'Penjual' }}</span>
                  </span>
                  <span class="text-emerald-600 bg-emerald-50 w-8 h-8 rounded-full flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors shrink-0">
                     <ChevronRight class="w-4 h-4" />
                  </span>
               </div>
            </div>
          </RouterLink>
        </div>
      </div>

    </div>
  </main>
</template>
