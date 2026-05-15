<script setup lang="ts">
import { ref } from 'vue'
import { ChevronRight, Search, ChevronDown, Package, CreditCard, Truck } from 'lucide-vue-next'

const faqs = ref([
  {
    question: 'Bagaimana cara melakukan pemesanan?',
    answer: 'Anda dapat memilih produk yang diinginkan, kemudian klik tombol "Pesan Sekarang" dan pilih platform pemesanan yang tersedia seperti WhatsApp, GrabFood, GoFood, atau Shopee. Pesanan Anda akan langsung diproses oleh merchant terkait.',
    isOpen: true
  },
  {
    question: 'Apakah produk dijamin asli dari desa?',
    answer: 'Ya, 100% produk yang ada di platform UMKM Desa Purwoasri adalah hasil karya dan produksi langsung dari warga desa. Kami melakukan kurasi ketat untuk menjaga keaslian dan kualitas produk.',
    isOpen: false
  },
  {
    question: 'Berapa lama estimasi waktu pengiriman?',
    answer: 'Waktu pengiriman bervariasi tergantung lokasi tujuan Anda dan metode pengiriman yang dipilih saat berbelanja melalui platform mitra (GrabFood/GoFood untuk area lokal, atau kurir reguler via Shopee/WA untuk luar daerah).',
    isOpen: false
  },
  {
    question: 'Apakah saya bisa melakukan retur jika barang rusak?',
    answer: 'Tentu. Anda dapat mengajukan klaim retur maksimal 1x24 jam setelah pesanan diterima dengan menyertakan video unboxing. Silakan hubungi kami melalui WhatsApp yang tertera di halaman kontak.',
    isOpen: false
  },
  {
    question: 'Metode pembayaran apa saja yang tersedia?',
    answer: 'Karena pemesanan diteruskan ke platform mitra (seperti Shopee atau GoFood), Anda dapat menggunakan semua metode pembayaran yang didukung oleh platform tersebut, termasuk e-wallet (GoPay, OVO, ShopeePay), transfer bank, maupun COD (Bayar di Tempat).',
    isOpen: false
  }
])

const toggleFaq = (index: number) => {
  if (faqs.value[index]) {
    faqs.value[index].isOpen = !faqs.value[index].isOpen
  }
}
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-4xl mx-auto px-6 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-sm text-gray-500 mb-8">
        <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
        <ChevronRight class="w-4 h-4" />
        <span class="text-emerald-700 font-medium">Pusat Bantuan</span>
      </nav>

      <!-- Header Search -->
      <div class="bg-emerald-600 rounded-3xl p-8 md:p-12 text-center shadow-lg mb-12">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Ada yang bisa kami bantu?</h1>
        <p class="text-emerald-100 mb-8 max-w-xl mx-auto">Cari solusi, panduan belanja, dan jawaban atas pertanyaan umum seputar UMKM Desa Purwoasri.</p>
        
        <div class="relative max-w-2xl mx-auto">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <Search class="h-5 w-5 text-gray-400" />
          </div>
          <input 
            type="text" 
            class="block w-full pl-12 pr-4 py-4 border-0 rounded-2xl leading-5 bg-white shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-400 sm:text-base transition-shadow" 
            placeholder="Ketik kata kunci (misal: pengiriman, pembayaran)..." 
          />
        </div>
      </div>

      <!-- Quick Topics -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow cursor-pointer text-center group">
          <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-4 group-hover:bg-emerald-100 transition-colors">
            <Package class="w-6 h-6" />
          </div>
          <h3 class="font-bold text-gray-900 mb-2">Panduan Belanja</h3>
          <p class="text-sm text-gray-500">Cara mudah memesan produk.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow cursor-pointer text-center group">
          <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-4 group-hover:bg-emerald-100 transition-colors">
            <CreditCard class="w-6 h-6" />
          </div>
          <h3 class="font-bold text-gray-900 mb-2">Pembayaran</h3>
          <p class="text-sm text-gray-500">Informasi metode bayar.</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow cursor-pointer text-center group">
          <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-4 group-hover:bg-emerald-100 transition-colors">
            <Truck class="w-6 h-6" />
          </div>
          <h3 class="font-bold text-gray-900 mb-2">Pengiriman</h3>
          <p class="text-sm text-gray-500">Lacak dan cek estimasi tiba.</p>
        </div>
      </div>

      <!-- FAQ Accordion -->
      <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-gray-100">
          <h2 class="text-2xl font-bold text-gray-900">Pertanyaan Umum (FAQ)</h2>
        </div>
        <div class="divide-y divide-gray-100">
          <div v-for="(faq, index) in faqs" :key="index" class="p-6 md:px-8">
            <button 
              @click="toggleFaq(index)" 
              class="w-full flex justify-between items-center text-left focus:outline-none"
            >
              <h3 class="font-bold text-gray-900 pr-8" :class="{'text-emerald-600': faq.isOpen}">{{ faq.question }}</h3>
              <ChevronDown 
                class="w-5 h-5 text-gray-400 shrink-0 transition-transform duration-300" 
                :class="{'rotate-180 text-emerald-600': faq.isOpen}"
              />
            </button>
            <div 
              v-show="faq.isOpen" 
              class="mt-4 text-gray-600 text-sm leading-relaxed pr-8"
            >
              {{ faq.answer }}
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>
</template>
