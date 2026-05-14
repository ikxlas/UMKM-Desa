<script setup lang="ts">
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
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-6 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <a href="/" class="hover:text-emerald-600 transition-colors">Home</a>
        <ChevronRight class="w-4 h-4" />
        <a href="#" class="hover:text-emerald-600 transition-colors">Makanan</a>
        <ChevronRight class="w-4 h-4" />
        <span class="text-emerald-700 font-medium">Kripik Tempe</span>
      </nav>

      <!-- Main Product Area -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        <!-- Left: Image Gallery -->
        <div class="flex flex-col gap-4">
          <!-- Main Image -->
          <div class="w-full aspect-square bg-gray-200 rounded-2xl overflow-hidden border border-gray-100">
            <img src="/images/kripik_main.png" alt="Kripik Tempe Renyah" class="w-full h-full object-cover" />
          </div>
          <!-- Thumbnails -->
          <div class="grid grid-cols-4 gap-4">
            <div class="aspect-square rounded-xl overflow-hidden border-2 border-emerald-500 cursor-pointer">
              <img src="/images/kripik_main.png" alt="Thumb 1" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-square rounded-xl overflow-hidden border border-transparent hover:border-emerald-500 cursor-pointer transition-colors opacity-70 hover:opacity-100">
              <img src="/images/emping_main.png" alt="Thumb 2" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-square rounded-xl overflow-hidden border border-transparent hover:border-emerald-500 cursor-pointer transition-colors opacity-70 hover:opacity-100">
              <img src="/images/kripik_main.png" alt="Thumb 3" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-square rounded-xl overflow-hidden relative cursor-pointer group">
              <img src="/images/prod_1.png" alt="Thumb 4" class="w-full h-full object-cover opacity-50" />
              <div class="absolute inset-0 bg-black/40 flex items-center justify-center group-hover:bg-black/50 transition-colors">
                <span class="text-white font-bold text-lg">+3</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right: Product Info -->
        <div class="flex flex-col">
          <!-- Badges -->
          <div class="flex items-center gap-3 mb-4">
            <div class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2.5 py-1 rounded-md flex items-center gap-1.5">
              <Store class="w-3.5 h-3.5" />
              Local Merchant Purwoasri
            </div>
            <div class="bg-gray-200 text-gray-700 text-xs font-bold px-2.5 py-1 rounded-md">
              Ready Stock
            </div>
          </div>

          <!-- Title -->
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Kripik Tempe Renyah Purwoasri</h1>

          <!-- Rating & Sold -->
          <div class="flex items-center gap-2 mb-6 text-sm">
            <div class="flex items-center text-emerald-500">
              <Star class="w-4 h-4 fill-emerald-500" />
              <Star class="w-4 h-4 fill-emerald-500" />
              <Star class="w-4 h-4 fill-emerald-500" />
              <Star class="w-4 h-4 fill-emerald-500" />
              <Star class="w-4 h-4 fill-emerald-100 text-emerald-200" />
            </div>
            <span class="font-medium text-gray-700">(4.8/5)</span>
            <span class="text-gray-300">•</span>
            <span class="text-gray-500">120 Terjual</span>
          </div>

          <!-- Price -->
          <div class="flex items-end gap-2 mb-8">
            <span class="text-4xl font-extrabold text-emerald-600">Rp 15.000</span>
            <span class="text-gray-500 mb-1">/ 250gr</span>
          </div>

          <!-- Description -->
          <h3 class="font-bold text-gray-900 mb-3">Deskripsi Produk</h3>
          <p class="text-gray-600 leading-relaxed mb-6 text-sm">
            Nikmati kelezatan kripik tempe khas Desa Purwoasri yang dibuat dari kedelai pilihan berkualitas tinggi. Diproses secara tradisional oleh pengrajin lokal untuk menghasilkan tekstur yang ekstra renyah dengan bumbu rempah alami yang meresap sempurna. Tanpa bahan pengawet dan MSG tambahan, menjadikannya cemilan sehat untuk seluruh keluarga.
          </p>

          <!-- Specifications List -->
          <ul class="space-y-3 mb-10 text-sm text-gray-600">
            <li class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
              <span>Berat bersih: <span class="font-medium text-gray-900">250 gram</span></span>
            </li>
            <li class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
              <span>Masa simpan: <span class="font-medium text-gray-900">3 Bulan</span></span>
            </li>
            <li class="flex items-center gap-2">
              <CheckCircle2 class="w-4 h-4 text-emerald-500 shrink-0" />
              <span>Kemasan: <span class="font-medium text-gray-900">Standing Pouch Foil</span></span>
            </li>
          </ul>

          <!-- Action Buttons -->
          <div>
            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
              <ShoppingBag class="w-5 h-5" /> Pesan Sekarang Melalui
            </h3>
            <div class="grid grid-cols-2 gap-4">
              <button class="bg-[#25D366] hover:bg-[#20b858] text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition-colors">
                <img src="https://cdn.simpleicons.org/whatsapp/white" alt="WhatsApp" class="w-5 h-5" /> WhatsApp
              </button>
              <button class="bg-[#00B14F] hover:bg-[#009643] text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition-colors">
                <img src="https://cdn.simpleicons.org/grab/white" alt="GrabFood" class="w-5 h-5" /> GrabFood
              </button>
              <button class="bg-[#EE2737] hover:bg-[#d62130] text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition-colors">
                <img src="https://cdn.simpleicons.org/gojek/white" alt="GoFood" class="w-5 h-5" /> GoFood
              </button>
              <button class="bg-[#EE4D2D] hover:bg-[#d64326] text-white font-semibold py-3 rounded-xl flex items-center justify-center gap-2 transition-colors">
                <img src="https://cdn.simpleicons.org/shopee/white" alt="Shopee" class="w-5 h-5" /> Shopee
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Supporting Economy Banner -->
      <div class="bg-emerald-600 rounded-3xl overflow-hidden flex flex-col md:flex-row items-center mb-16 shadow-xl">
        <div class="md:w-1/3 h-64 md:h-auto self-stretch">
          <img src="/images/merchant.png" alt="Pengrajin" class="w-full h-full object-cover" />
        </div>
        <div class="md:w-2/3 p-8 md:p-12 text-white">
          <h2 class="text-2xl md:text-3xl font-bold mb-4">Mendukung Ekonomi Desa Purwoasri</h2>
          <p class="text-emerald-50 leading-relaxed mb-8 max-w-2xl">
            Setiap pembelian produk ini berkontribusi langsung pada kesejahteraan pengrajin tempe dan petani kedelai di Desa Purwoasri. Kami berkomitmen untuk memberdayakan UMKM lokal agar tetap berdaya saing melalui digitalisasi dan akses pasar yang lebih luas.
          </p>
          <div class="flex flex-wrap items-center gap-4">
            <div class="bg-emerald-500/50 backdrop-blur border border-emerald-400/50 px-4 py-2 rounded-lg flex items-center gap-2 font-medium">
              <Users class="w-5 h-5" /> 50+ Pengrajin Terbantu
            </div>
            <div class="bg-emerald-500/50 backdrop-blur border border-emerald-400/50 px-4 py-2 rounded-lg flex items-center gap-2 font-medium">
              <Leaf class="w-5 h-5" /> 100% Bahan Alami
            </div>
          </div>
        </div>
      </div>

      <!-- Related Products -->
      <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Mungkin Anda Juga Suka</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
          
          <!-- Related Product 1 -->
          <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group">
            <div class="relative h-48 overflow-hidden bg-gray-100">
              <img src="/images/emping_main.png" alt="Emping Melinjo" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="p-4">
              <h3 class="text-base font-bold text-gray-900 mb-1">Emping Melinjo</h3>
              <p class="text-emerald-600 font-bold">Rp 22.000</p>
            </div>
          </div>

          <!-- Related Product 2 -->
          <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow cursor-pointer group">
            <div class="relative h-48 overflow-hidden bg-gray-100">
              <img src="/images/prod_1.png" alt="Sale Pisang Madu" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            </div>
            <div class="p-4">
              <h3 class="text-base font-bold text-gray-900 mb-1">Sale Pisang Madu</h3>
              <p class="text-emerald-600 font-bold">Rp 18.000</p>
            </div>
          </div>

          <!-- Promo Package -->
          <div class="col-span-2 bg-gradient-to-br from-emerald-50 to-white rounded-xl border border-emerald-100 p-8 flex flex-col justify-center relative overflow-hidden group cursor-pointer">
            <div class="relative z-10">
              <h3 class="text-2xl font-bold text-emerald-800 mb-2">Paket Oleh-Oleh Purwoasri</h3>
              <p class="text-emerald-600/80 mb-6 max-w-sm text-sm">
                Dapatkan koleksi cemilan terbaik dalam satu kemasan eksklusif.
              </p>
              <button class="bg-emerald-700 hover:bg-emerald-800 text-white font-medium px-6 py-2.5 rounded-lg text-sm transition-colors shadow-lg shadow-emerald-700/20">
                Lihat Paket
              </button>
            </div>
            <ShoppingBag class="absolute -bottom-8 -right-8 w-48 h-48 text-emerald-100/50 group-hover:scale-110 transition-transform duration-700 pointer-events-none" />
          </div>

        </div>
      </div>
    </div>
  </main>
</template>
