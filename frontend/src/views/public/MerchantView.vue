<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { 
  ChevronRight, 
  Store, 
  MapPin,
  Phone,
  LayoutGrid,
  Info,
  CalendarDays,
  Instagram,
  Facebook
} from 'lucide-vue-next'

const route = useRoute()
const isLoading = ref(true)
const merchant = ref<any>(null)
const merchantProducts = ref<any[]>([])

const fetchMerchantData = async () => {
  isLoading.value = true
  try {
    const id = route.params.id
    
    // Fetch Single Merchant + All Products
    const [resMerch, resProducts] = await Promise.all([
      fetch(`http://127.0.0.1:8000/api/merchants/${id}`),
      fetch(`http://127.0.0.1:8000/api/products`)
    ])
    
    if (resMerch.ok) {
        merchant.value = await resMerch.json()
    }

    if (resProducts.ok) {
        const dataAll = await resProducts.json()
        const parsedProducts = Array.isArray(dataAll) ? dataAll : (dataAll.data || [])
        // Filter products belonging strictly to this merchant
        merchantProducts.value = parsedProducts.filter((p: any) => p.merchant_id == id)
    }
  } catch (err) {
    console.error("Gagal menarik data merchant:", err)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchMerchantData()
})

// Jika user menekan tombol Kunjungi Toko lain saat sedang di halaman toko
watch(() => route.params.id, () => {
  fetchMerchantData()
})

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
}

const getProductImage = (p: any) => {
  if (p?.image) return p.image
  if (p?.gallery_images && p.gallery_images.length > 0) return p.gallery_images[0]
  return '/images/prod_1.png'
}

const getWhatsAppLink = (m: any) => {
    let phone = '6281234567890';
    if(m && m.phone) phone = m.phone;
    
    if (phone.includes('wa.me') || phone.startsWith('http')) {
        return phone;
    }
    
    phone = phone.replace(/[^0-9]/g, '');
    if(phone.startsWith('0')) phone = '62' + phone.substring(1);
    const text = `Halo Admin ${m?.name || 'Toko'}, saya ingin bertanya seputar produk di toko Anda.`;
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
}

const socialMedia = computed(() => {
  let social = { instagram: '', facebook: '', tiktok: '' };
  if (merchant.value?.social_media) {
    try {
      social = typeof merchant.value.social_media === 'string' 
                ? JSON.parse(merchant.value.social_media) 
                : merchant.value.social_media;
    } catch (e) {}
  }
  return social;
});

</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 md:px-12">
      
      <div v-if="isLoading" class="flex justify-center py-32">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
      </div>

      <div v-else-if="!merchant" class="text-center py-32">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Toko Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-6">Merchant yang Anda cari mungkin telah ditutup atau dihapus.</p>
        <RouterLink to="/" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-medium">Kembali ke Beranda</RouterLink>
      </div>

      <div v-else>
        <!-- Breadcrumbs -->
        <nav class="flex items-center gap-1.5 md:gap-2 text-xs md:text-sm text-gray-500 mb-4 md:mb-6">
          <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
          <ChevronRight class="w-4 h-4" />
          <span class="text-emerald-700 font-medium truncate max-w-xs">{{ merchant.name }}</span>
        </nav>

        <!-- Merchant Header Profile -->
        <div class="bg-white rounded-2xl md:rounded-3xl overflow-hidden shadow-sm border border-gray-100 mb-8 md:mb-12">
          <!-- Banner -->
          <div class="h-36 md:h-64 relative bg-gray-900">
            <img :src="merchant.storefront_image || '/images/merchant.png'" alt="Storefront Banner" class="w-full h-full object-cover opacity-90" />
            <div class="absolute inset-0 bg-black/10"></div>
          </div>
          
          <div class="relative px-4 md:px-12 pb-5 md:pb-8">
            <div class="flex flex-col md:flex-row gap-6 relative z-10">
              
              <!-- Avatar (Tarik ke atas banner) -->
              <div class="-mt-12 md:-mt-16 w-24 h-24 md:w-32 md:h-32 rounded-xl md:rounded-2xl border-4 border-white overflow-hidden bg-white shadow-lg shrink-0">
                <img :src="merchant.logo || '/images/bumn.png'" alt="Merchant Logo" class="w-full h-full object-cover" />
              </div>

              <!-- Info & Button (Tetap di area putih) -->
              <div class="flex-1 mt-2 md:mt-4">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                  <div>
                    <h1 class="text-xl md:text-3xl font-extrabold text-gray-900 mb-1.5 md:mb-2">{{ merchant.name }}</h1>
                    <div class="flex flex-wrap gap-2 md:gap-4 text-xs md:text-sm text-gray-600 font-medium">
                       <div class="flex items-center gap-1.5"><Store class="w-4 h-4 text-emerald-600" /> Pemilik: <span class="font-bold">{{ merchant.owner_name || '-' }}</span></div>
                       <div class="flex items-center gap-1.5"><LayoutGrid class="w-4 h-4 text-emerald-600" /> {{ merchantProducts.length }} Produk</div>
                    </div>
                  </div>
                  <div class="w-full md:w-auto shrink-0 mt-2 md:mt-0">
                    <a :href="getWhatsAppLink(merchant)" target="_blank" class="w-full md:w-auto bg-[#25D366] hover:bg-[#20b858] text-white font-bold py-3.5 px-6 rounded-xl flex items-center justify-center gap-2 transition-default shadow-sm border border-[#20b858]">
                      <img src="https://cdn.simpleicons.org/whatsapp/white" class="w-5 h-5" /> Hubungi Toko
                    </a>
                  </div>
                </div>
              </div>
            </div>

            <!-- Content Grid: Description & Contacts -->
            <div class="mt-5 md:mt-8 grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">
              
              <!-- Description Box -->
              <div class="lg:col-span-2 bg-emerald-50/50 rounded-xl md:rounded-2xl p-4 md:p-6 border border-emerald-100">
                <h3 class="flex items-center gap-2 text-sm font-bold text-gray-900 mb-3">
                  <Info class="w-4 h-4 text-emerald-600" /> Tentang Usaha Kami
                </h3>
                <div class="text-gray-700 text-sm sm:text-base leading-relaxed whitespace-pre-wrap min-h-[120px] max-h-[400px] overflow-y-auto pr-2" style="scrollbar-width: thin; scrollbar-color: #34d399 transparent;">
                  {{ merchant.description || 'Toko UMKM ini belum menuliskan deskripsi profil usahanya. Dukung terus produk lokal desa ini!' }}
                </div>
              </div>

              <!-- Contact & Location Box -->
              <div class="bg-white rounded-xl md:rounded-2xl p-4 md:p-6 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] h-fit">
                <h3 class="flex items-center gap-2 text-base font-bold text-gray-900 mb-5 relative pb-3">
                  <Phone class="w-5 h-5 text-emerald-600" /> Lokasi & Kontak
                  <div class="absolute bottom-0 left-0 w-12 h-1 bg-emerald-500 rounded-full"></div>
                </h3>
                
                <div class="space-y-6">
                  <!-- Alamat -->
                  <div class="flex gap-4 group">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 group-hover:bg-emerald-100 transition-colors">
                      <MapPin class="w-5 h-5 text-emerald-600" />
                    </div>
                    <div>
                      <p class="text-sm font-bold text-gray-900">Alamat Lengkap</p>
                      <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ merchant.address || 'Alamat tidak tersedia' }}</p>
                    </div>
                  </div>
                  
                  <!-- Social Media Section -->
                  <div v-if="socialMedia.instagram || socialMedia.facebook || socialMedia.tiktok" class="pt-6 border-t border-gray-100">
                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4">Terhubung Bersama Kami</p>
                    
                    <div class="flex flex-col gap-3">
                      <!-- Instagram -->
                      <a v-if="socialMedia.instagram" :href="socialMedia.instagram" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-pink-500 hover:shadow-md transition-all duration-300 group">
                        <div class="w-9 h-9 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center shrink-0 group-hover:border-pink-200 transition-colors">
                          <Instagram class="w-4 h-4 text-pink-500" />
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-pink-600 transition-colors">Instagram</span>
                      </a>
                      
                      <!-- Facebook -->
                      <a v-if="socialMedia.facebook" :href="socialMedia.facebook" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-blue-500 hover:shadow-md transition-all duration-300 group">
                        <div class="w-9 h-9 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center shrink-0 group-hover:border-blue-200 transition-colors">
                          <Facebook class="w-4 h-4 text-blue-600" />
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-blue-700 transition-colors">Facebook</span>
                      </a>
                      
                      <!-- TikTok -->
                      <a v-if="socialMedia.tiktok" :href="socialMedia.tiktok" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50/50 hover:bg-white hover:border-gray-900 hover:shadow-md transition-all duration-300 group">
                        <div class="w-9 h-9 rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center shrink-0 group-hover:border-gray-300 transition-colors">
                          <img src="https://cdn.simpleicons.org/tiktok/currentColor" class="w-4 h-4 text-gray-800 group-hover:text-black" />
                        </div>
                        <span class="text-sm font-bold text-gray-700 group-hover:text-black transition-colors">TikTok</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            
          </div>
        </div>

        <!-- Catalog Section -->
        <div class="mb-10 flex items-center justify-between border-b border-gray-200 pb-4">
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Katalog Produk Toko</h2>
        </div>

        <div v-if="merchantProducts.length === 0" class="text-center py-20 bg-white rounded-2xl border border-gray-100">
             <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
               <Package class="w-8 h-8 text-gray-400" />
             </div>
             <h3 class="text-lg font-bold text-gray-900 mb-2">Toko Masih Kosong</h3>
             <p class="text-gray-500 text-sm max-w-sm mx-auto">Merchant ini belum menambahkan produk apa pun ke dalam katalognya.</p>
        </div>
        
        <div v-else class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-6">
          <RouterLink 
            v-for="product in merchantProducts" 
            :key="product.id" 
            :to="`/product/${product.id}`"
            class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group flex flex-col"
          >
            <div class="relative aspect-square overflow-hidden bg-gray-100">
              <img :src="getProductImage(product)" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div v-if="product.stock > 0" class="absolute top-3 left-3 bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-1 rounded">
                READY STOCK
              </div>
              <div v-else class="absolute top-3 left-3 bg-red-100 text-red-800 text-[10px] font-bold px-2 py-1 rounded">
                KOSONG
              </div>
            </div>
            <div class="p-3 md:p-4 flex flex-col flex-grow">
              <h3 class="text-xs md:text-sm font-medium text-gray-900 mb-1.5 md:mb-2 line-clamp-2">{{ product.name }}</h3>
              <p class="text-emerald-600 font-bold text-sm md:text-lg mb-1 mt-auto">{{ formatPrice(product.price) }}</p>
            </div>
          </RouterLink>
        </div>

      </div>
    </div>
  </main>
</template>
