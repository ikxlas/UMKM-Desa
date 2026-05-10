<script setup lang="ts">
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
  Clock
} from 'lucide-vue-next'

const categories = [
  { name: 'Kerajinan', icon: Leaf },
  { name: 'Makanan', icon: Croissant },
  { name: 'Pakaian', icon: Shirt },
  { name: 'Pertanian', icon: Tractor },
  { name: 'Furnitur', icon: Sofa },
  { name: 'Lainnya', icon: LayoutGrid },
]

const products = [
  {
    name: 'Anyaman Bambu Premium',
    price: 'Rp 75.000',
    location: 'Purwoasri, Kediri',
    rating: 4.8,
    sold: '120+',
    image: '/images/prod_1.png',
    readyStock: true
  },
  {
    name: 'Madu Hutan Murni',
    price: 'Rp 125.000',
    location: 'Purwoasri, Kediri',
    rating: 5.0,
    sold: '85',
    image: '/images/prod_1.png', // Fallback to bamboo image for now
    readyStock: false
  },
  {
    name: 'Kain Batik Tulis Solo',
    price: 'Rp 350.000',
    location: 'Purwoasri, Kediri',
    rating: 4.9,
    sold: '45',
    image: '/images/prod_1.png',
    readyStock: false
  },
  {
    name: 'Cangkir Keramik Etnik',
    price: 'Rp 45.000',
    location: 'Purwoasri, Kediri',
    rating: 4.7,
    sold: '210',
    image: '/images/prod_1.png',
    readyStock: false
  },
  {
    name: 'Kerupuk Sayur Organik',
    price: 'Rp 15.000',
    location: 'Purwoasri, Kediri',
    rating: 4.8,
    sold: '500+',
    image: '/images/prod_1.png',
    readyStock: false
  }
]

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
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20">
    <!-- Hero Section -->
    <div class="px-6 md:px-12 pt-8">
      <div class="relative w-full h-[400px] md:h-[450px] rounded-2xl overflow-hidden shadow-lg">
        <img src="/images/hero.png" alt="Desa Purwoasri" class="absolute inset-0 w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
        <div class="absolute inset-0 flex flex-col justify-center px-10 md:px-16 max-w-2xl">
          <h1 class="text-4xl md:text-5xl font-bold text-white leading-tight mb-4">
            Produk Lokal Terbaik Desa
          </h1>
          <p class="text-gray-200 text-base md:text-lg mb-8 leading-relaxed">
            Dukung pengrajin dan petani lokal Desa Purwoasri dengan produk berkualitas tinggi langsung dari sumbernya.
          </p>
          <div>
            <button class="bg-emerald-500 hover:bg-emerald-600 text-white font-medium py-3 px-8 rounded-lg shadow-lg shadow-emerald-500/30 transition-all transform hover:-translate-y-0.5">
              Belanja Sekarang
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Categories Section -->
    <div class="px-6 md:px-12 mt-12">
      <div class="flex items-center justify-start gap-8 md:gap-16 overflow-x-auto pb-4 hide-scrollbar">
        <div v-for="(cat, index) in categories" :key="index" class="flex flex-col items-center gap-3 cursor-pointer group min-w-[80px]">
          <div class="w-16 h-16 rounded-full bg-white border border-gray-100 shadow-sm flex items-center justify-center text-emerald-600 group-hover:bg-emerald-50 group-hover:border-emerald-200 transition-colors">
            <component :is="cat.icon" class="w-7 h-7" />
          </div>
          <span class="text-xs font-semibold text-gray-700 group-hover:text-emerald-700 transition-colors">{{ cat.name }}</span>
        </div>
      </div>
    </div>

    <!-- Produk Terlaris Section -->
    <div class="px-6 md:px-12 mt-16">
      <div class="flex items-end justify-between mb-8">
        <div>
          <h2 class="text-2xl font-bold text-gray-900 mb-1">Produk Terlaris</h2>
          <p class="text-gray-500 text-sm">Koleksi favorit pelanggan minggu ini</p>
        </div>
        <a href="#" class="text-emerald-600 font-semibold text-sm hover:text-emerald-700">Lihat Semua</a>
      </div>

      <!-- Products Grid -->
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div v-for="(product, idx) in products" :key="idx" class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group">
          <div class="relative h-48 overflow-hidden bg-gray-100">
            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div v-if="product.readyStock" class="absolute top-3 left-3 bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-1 rounded">
              READY STOCK
            </div>
          </div>
          <div class="p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-2 truncate">{{ product.name }}</h3>
            <p class="text-emerald-600 font-bold text-lg mb-3">{{ product.price }}</p>
            
            <div class="flex items-center gap-1 text-gray-500 text-xs mb-3">
              <MapPin class="w-3.5 h-3.5" />
              <span class="truncate">{{ product.location }}</span>
            </div>
            
            <div class="flex items-center justify-between border-t border-gray-100 pt-3">
              <div class="flex items-center gap-1 text-xs font-medium text-gray-700">
                <Star class="w-3.5 h-3.5 text-orange-400 fill-orange-400" />
                <span>{{ product.rating }}</span>
              </div>
              <span class="text-xs text-gray-500">Terjual {{ product.sold }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Merchant Pilihan Section -->
    <div class="px-6 md:px-12 mt-20">
      <div class="flex items-center gap-2 mb-8">
        <CheckCircle2 class="w-6 h-6 text-emerald-600" />
        <h2 class="text-2xl font-bold text-gray-900">Merchant Pilihan</h2>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Merchant Card -->
        <div class="lg:col-span-2 relative rounded-2xl overflow-hidden h-[300px] shadow-lg group cursor-pointer">
          <img src="/images/merchant.png" alt="Bengkel Kayu Jati" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
          <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
          
          <div class="absolute bottom-0 left-0 p-8 w-full">
            <div class="bg-emerald-500/90 backdrop-blur text-white text-[10px] font-bold px-3 py-1.5 rounded-full inline-flex items-center gap-1.5 mb-3 uppercase tracking-wider">
              <Star class="w-3 h-3 fill-white" /> Local Merchant Terbaik
            </div>
            <h3 class="text-3xl font-bold text-white mb-2">Bengkel Kayu Jati Purwoasri</h3>
            <p class="text-gray-200 text-sm max-w-md mb-6 leading-relaxed">Spesialis furnitur jati ukir tangan dengan kualitas ekspor namun harga lokal yang bersaing.</p>
            <button class="bg-white text-gray-900 font-semibold px-6 py-2.5 rounded-lg text-sm hover:bg-gray-50 transition-colors">
              Lihat Toko
            </button>
          </div>
        </div>

        <!-- Smaller Merchant Cards -->
        <div class="flex flex-col gap-6">
          <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-shadow cursor-pointer relative overflow-hidden group">
            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4 group-hover:bg-emerald-100 transition-colors">
              <Leaf class="w-6 h-6" />
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">Kebun Hidroponik Asri</h4>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">Sayur segar bebas pestisida dipanen setiap pagi.</p>
            <div class="flex items-center gap-2 text-emerald-600 font-semibold text-sm">
              Kunjungi Merchant <ArrowRight class="w-4 h-4" />
            </div>
            <!-- Decorative Background Icon -->
            <Tractor class="absolute -bottom-4 -right-4 w-32 h-32 text-gray-50 opacity-50 group-hover:scale-110 transition-transform duration-500 pointer-events-none" />
          </div>

          <div class="bg-white rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-shadow cursor-pointer relative overflow-hidden group">
            <div class="w-12 h-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4 group-hover:bg-emerald-100 transition-colors">
              <Sofa class="w-6 h-6" />
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">Kopi Lereng Purwo</h4>
            <p class="text-gray-500 text-sm mb-6 leading-relaxed">Biji kopi pilihan yang diproses secara tradisional.</p>
            <div class="flex items-center gap-2 text-emerald-600 font-semibold text-sm">
              Kunjungi Merchant <ArrowRight class="w-4 h-4" />
            </div>
            <!-- Decorative Background Icon -->
            <LayoutGrid class="absolute -bottom-4 -right-4 w-32 h-32 text-gray-50 opacity-50 group-hover:scale-110 transition-transform duration-500 pointer-events-none" />
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="px-6 md:px-12 mt-20">
      <div class="bg-white border border-gray-100 rounded-2xl p-8 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 divide-y md:divide-y-0 md:divide-x divide-gray-100">
          <div v-for="(feat, i) in features" :key="i" class="flex gap-4 items-start pt-6 md:pt-0 first:pt-0 md:px-6 first:px-0">
            <div class="w-12 h-12 rounded-xl bg-emerald-500 flex items-center justify-center text-white shrink-0">
              <component :is="feat.icon" class="w-6 h-6" />
            </div>
            <div>
              <h4 class="font-bold text-gray-900 mb-1">{{ feat.title }}</h4>
              <p class="text-sm text-gray-500 leading-relaxed">{{ feat.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.hide-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>
