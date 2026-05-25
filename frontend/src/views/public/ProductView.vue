<script setup lang="ts">
import { ref, onMounted, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { 
  ChevronRight, 
  Store, 
  Star, 
  CheckCircle2, 
  ShoppingBag,
  MessageCircle,
  Users,
  Leaf
} from 'lucide-vue-next'

const route = useRoute()
const isLoading = ref(true)
const product = ref<any>(null)
const allProducts = ref<any[]>([])

// Default Image Display
const activeImage = ref('')

const fetchProductData = async () => {
  isLoading.value = true
  try {
    const id = route.params.id
    
    // Fetch Single Product + All Products for "Related"
    const [resProd, resAll] = await Promise.all([
      fetch(`http://127.0.0.1:8000/api/products/${id}`),
      fetch(`http://127.0.0.1:8000/api/products`)
    ])
    
    if (resProd.ok) {
        product.value = await resProd.json()
        activeImage.value = getProductImage(product.value)
    }

    if (resAll.ok) {
        const dataAll = await resAll.json()
        allProducts.value = Array.isArray(dataAll) ? dataAll : (dataAll.data || [])
    }
  } catch (err) {
    console.error("Gagal menarik data produk detail:", err)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchProductData()
})

// Watch route id if user clicks related product
watch(() => route.params.id, () => {
  fetchProductData()
})

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
}

const getProductImage = (p: any) => {
  if (p?.image) return p.image
  if (p?.gallery_images && p.gallery_images.length > 0) return p.gallery_images[0]
  return '/images/prod_1.png'
}

const getAllImages = (p: any) => {
  const images = []
  if (p?.image) images.push(p.image)
  if (p?.gallery_images && p.gallery_images.length > 0) {
    images.push(...p.gallery_images)
  }
  if (images.length === 0) images.push('/images/prod_1.png')
  return images
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

const relatedProducts = computed(() => {
  if(!product.value) return []
  return allProducts.value
    .filter(p => p.id !== product.value.id && p.category_id === product.value.category_id)
    .slice(0, 4)
})

const getWhatsAppLink = (m: any, p: any) => {
    let phone = '6281234567890';
    if(m && m.phone) phone = m.phone;
    if(phone.startsWith('0')) phone = '62' + phone.substring(1);
    const text = `Halo Admin ${m?.name || 'Toko'}, saya tertarik untuk memesan produk *${p?.name}* dengan harga ${formatPrice(p?.price || 0)}.`;
    return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
}

</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
      
      <div v-if="isLoading" class="flex justify-center py-32">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
      </div>

      <div v-else-if="!product" class="text-center py-32">
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Produk Tidak Ditemukan</h2>
        <p class="text-gray-500 mb-6">Produk yang Anda cari mungkin telah dihapus atau tidak tersedia.</p>
        <RouterLink to="/" class="bg-emerald-600 text-white px-6 py-2 rounded-lg font-medium">Kembali ke Beranda</RouterLink>
      </div>

      <div v-else>
        <!-- Breadcrumbs -->
        <nav class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-8">
          <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
          <ChevronRight class="w-4 h-4" />
          <RouterLink to="/category" class="hover:text-emerald-600 transition-colors">{{ product.category?.name || 'Katalog' }}</RouterLink>
          <ChevronRight class="w-4 h-4" />
          <span class="text-emerald-700 font-medium truncate max-w-[200px] sm:max-w-xs">{{ product.name }}</span>
        </nav>

        <!-- Main Product Area -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
          <!-- Left: Image Gallery -->
          <div class="flex flex-col gap-4">
            <!-- Main Image -->
            <div class="w-full aspect-square bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm flex items-center justify-center p-2">
              <img :src="activeImage" :alt="product.name" class="w-full h-full object-cover rounded-xl" />
            </div>
            <!-- Thumbnails -->
            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
              <div 
                v-for="(img, idx) in getAllImages(product).slice(0, 5)" 
                :key="idx" 
                @click="activeImage = img"
                class="aspect-square rounded-xl overflow-hidden border-2 cursor-pointer transition-colors"
                :class="activeImage === img ? 'border-emerald-500' : 'border-transparent hover:border-emerald-300 opacity-70 hover:opacity-100 bg-white'"
              >
                <img :src="img" class="w-full h-full object-cover" />
              </div>
            </div>
          </div>

          <!-- Right: Product Info -->
          <div class="flex flex-col">
            <!-- Badges -->
            <div class="flex flex-wrap items-center gap-3 mb-4">
              <div class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-md flex items-center gap-1.5">
                <Store class="w-3.5 h-3.5" />
                {{ product.merchant?.name || 'Local Merchant' }}
              </div>
              <div v-if="product.stock > 0" class="bg-blue-50 border border-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-md flex items-center gap-1">
                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></div> Ready Stock ({{ product.stock }})
              </div>
              <div v-else class="bg-red-50 border border-red-100 text-red-700 text-xs font-bold px-2.5 py-1 rounded-md">
                Stok Kosong
              </div>
              <RouterLink v-if="product.merchant?.id" :to="`/merchant/${product.merchant.id}`" class="ml-auto text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 bg-white border border-emerald-200 hover:border-emerald-500 px-3 py-1 rounded-full transition-colors cursor-pointer shadow-sm">
                Kunjungi Toko <ChevronRight class="w-3 h-3" />
              </RouterLink>
            </div>

            <!-- Title -->
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 leading-tight">{{ product.name }}</h1>



            <!-- Price -->
            <div class="flex items-end gap-2 mb-8 bg-gray-50/50 p-4 border border-gray-100 rounded-2xl w-max">
              <span class="text-4xl font-extrabold text-emerald-600">{{ formatPrice(product.price) }}</span>
              <span class="text-gray-500 mb-1 font-medium">/ {{ product.unit || 'pcs' }}</span>
            </div>

            <!-- Description -->
            <h3 class="font-bold text-gray-900 mb-3 text-lg">Deskripsi Produk</h3>
            <div class="text-gray-600 leading-relaxed mb-8 text-sm sm:text-base whitespace-pre-wrap">
              {{ product.description || 'Tidak ada deskripsi yang tersedia untuk produk ini. Hubungi penjual untuk informasi lebih lanjut.' }}
            </div>

            <!-- Action Buttons -->
            <div class="mt-auto">
              <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                <ShoppingBag class="w-5 h-5 text-emerald-600" /> Opsi Pemesanan Langsung
              </h3>
              <div class="grid grid-cols-2 gap-4">
                <a :href="getWhatsAppLink(product.merchant, product)" target="_blank" class="bg-[#25D366] hover:bg-[#20b858] text-white font-semibold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm shadow-[#25D366]/20">
                  <img src="https://cdn.simpleicons.org/whatsapp/white" alt="WhatsApp" class="w-5 h-5" /> WhatsApp
                </a>
                
                <button class="bg-[#00B14F] hover:bg-[#009643] text-white font-semibold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm shadow-[#00B14F]/20">
                  <img src="https://cdn.simpleicons.org/grab/white" alt="GrabFood" class="w-5 h-5" /> GrabFood
                </button>
                
                <button class="bg-[#EE2737] hover:bg-[#d62130] text-white font-semibold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm shadow-[#EE2737]/20">
                  <img src="https://cdn.simpleicons.org/gojek/white" alt="GoFood" class="w-5 h-5" /> GoFood
                </button>

                <button class="bg-[#EE4D2D] hover:bg-[#d64326] text-white font-semibold py-3.5 px-4 rounded-xl flex items-center justify-center gap-2 transition-colors shadow-sm shadow-[#EE4D2D]/20">
                  <img src="https://cdn.simpleicons.org/shopee/white" alt="Shopee" class="w-5 h-5" /> Shopee
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Supporting Economy Banner -->
        <div class="bg-emerald-700 rounded-3xl overflow-hidden flex flex-col md:flex-row items-stretch mb-20 shadow-xl relative">
          <!-- Pattern Decal -->
          <Leaf class="absolute -bottom-10 -left-10 w-48 h-48 text-emerald-600 opacity-20 pointer-events-none" />
         
          <div class="md:w-1/3 aspect-[4/3] md:aspect-auto self-stretch bg-gray-900 relative hidden md:block">
            <img :src="product.merchant?.storefront_image || '/images/merchant.png'" alt="Pengrajin" class="absolute inset-0 w-full h-full object-cover opacity-80" />
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-emerald-700/90"></div>
          </div>
          
          <div class="md:w-2/3 p-8 lg:p-12 text-white relative z-10">
            <h2 class="text-2xl lg:text-3xl font-bold mb-3 flex items-center gap-2">
              <Store class="w-6 h-6 text-emerald-300" /> Profil Usaha: {{ product.merchant?.name || 'Toko Warga' }}
            </h2>
            <p class="text-emerald-100 font-medium leading-relaxed mb-6 max-w-2xl text-sm sm:text-base">
              Ingin tahu lebih banyak tentang cerita di balik produk ini dan melihat katalog lengkap? 
            </p>
            <div class="flex flex-wrap items-center gap-4">
              <div class="bg-emerald-600 border border-emerald-500/50 px-4 py-2.5 rounded-lg flex items-center gap-2 font-medium text-sm text-emerald-50">
                <Users class="w-4 h-4 text-emerald-300" /> Pemilik: <span class="font-bold">{{ product.merchant?.owner_name || 'Warga Purwoasri' }}</span>
              </div>
              <RouterLink v-if="product.merchant?.id" :to="`/merchant/${product.merchant.id}`" class="bg-white text-emerald-800 hover:text-emerald-900 font-bold px-6 py-2.5 rounded-lg flex items-center gap-2 transition-transform hover:scale-105 shadow-md">
                Kunjungi Profil Toko <ChevronRight class="w-4 h-4" />
              </RouterLink>
            </div>
          </div>
        </div>

        <!-- Related Products -->
        <div v-if="relatedProducts.length > 0">
          <div class="flex items-center justify-between mb-8 border-b border-gray-200 pb-4">
               <h2 class="text-2xl font-bold text-gray-900">Produk Serupa Lainnya</h2>
               <RouterLink to="/category" class="text-emerald-600 font-semibold text-sm hover:text-emerald-700">Lihat Kategori</RouterLink>
          </div>
          
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
            <RouterLink 
              v-for="p in relatedProducts" 
              :key="p.id" 
              :to="`/product/${p.id}`"
              class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group"
            >
              <div class="relative h-48 overflow-hidden bg-gray-100">
                <img :src="getProductImage(p)" :alt="p.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              </div>
              <div class="p-4">
                <p class="text-[10px] font-bold text-gray-400 mb-1 uppercase">{{ p.merchant?.name || 'Toko' }}</p>
                <h3 class="text-sm font-bold text-gray-900 mb-2 truncate">{{ p.name }}</h3>
                <p class="text-emerald-600 font-extrabold">{{ formatPrice(p.price) }}</p>
              </div>
            </RouterLink>
          </div>
        </div>

      </div>
    </div>
  </main>
</template>
