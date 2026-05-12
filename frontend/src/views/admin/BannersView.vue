<script setup lang="ts">
import { ref } from 'vue'
import { 
  Image as ImageIcon, 
  Save, 
  Star,
  Tag,
  MonitorPlay
} from 'lucide-vue-next'

// Mock State untuk pengaturan (biasanya ini diambil dari API backend)
const isPromoActive = ref(true)

</script>

<template>
  <div class="space-y-8 pb-12 max-w-4xl">
    
    <!-- HEADER -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Pengaturan Beranda & Banner</h1>
      <p class="text-sm text-gray-500 mt-1">Sesuaikan tampilan halaman depan website pengunjung agar terlihat menarik.</p>
    </div>

    <!-- KARTU 1: HERO BANNER UTAMA -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="border-b border-gray-100 bg-gray-50/50 p-6 flex items-center gap-3">
        <MonitorPlay class="w-6 h-6 text-emerald-600" />
        <h2 class="text-lg font-bold text-gray-900">Hero Banner Utama</h2>
      </div>
      
      <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Judul Utama (Headline)</label>
              <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" value="Pasar Digital Desa Purwoasri" />
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Subjudul (Deskripsi Singkat)</label>
              <textarea rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow">Pusat kerajinan tangan dan produk unggulan asli dari warga Desa Purwoasri, Kediri.</textarea>
            </div>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-gray-700">Gambar Latar (Background)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 h-[180px] flex flex-col items-center justify-center text-center hover:bg-gray-50 transition-colors cursor-pointer group relative overflow-hidden">
              <!-- Preview Gambar Aktif (Bisa diganti logika Vue jika file diunggah) -->
              <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                <span class="text-white font-medium text-sm bg-black/50 px-4 py-2 rounded-full">Ganti Gambar</span>
              </div>
              <img src="/images/hero.png" alt="Hero Preview" class="absolute inset-0 w-full h-full object-cover" />
            </div>
            <p class="text-xs text-gray-400 mt-1">Rekomendasi rasio 16:9, resolusi 1920x1080px (Maks 2MB).</p>
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
          <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-colors">
            <Save class="w-4 h-4" /> Simpan Banner Utama
          </button>
        </div>
      </div>
    </div>

    <!-- KARTU 2: BANNER PROMO SPESIAL -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="border-b border-gray-100 bg-gray-50/50 p-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <Tag class="w-6 h-6 text-orange-500" />
          <h2 class="text-lg font-bold text-gray-900">Promo Spesial (Tengah Beranda)</h2>
        </div>
        
        <!-- Toggle Switch -->
        <label class="flex items-center cursor-pointer">
          <div class="relative">
            <input type="checkbox" v-model="isPromoActive" class="sr-only" />
            <div class="block w-14 h-8 rounded-full transition-colors" :class="isPromoActive ? 'bg-emerald-500' : 'bg-gray-300'"></div>
            <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 ease-in-out" :class="isPromoActive ? 'translate-x-6' : 'translate-x-0'"></div>
          </div>
          <div class="ml-3 text-sm font-medium" :class="isPromoActive ? 'text-emerald-700' : 'text-gray-500'">
            {{ isPromoActive ? 'Aktif' : 'Nonaktif' }}
          </div>
        </label>
      </div>
      
      <div class="p-6 space-y-6" :class="!isPromoActive ? 'opacity-50 pointer-events-none' : ''">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Teks Judul Promo</label>
              <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" value="Diskon Akhir Tahun UMKM!" />
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Link Tujuan Promo (URL)</label>
              <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" placeholder="Contoh: /category?promo=yes" />
            </div>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-gray-700">Gambar Banner Promo</label>
            <div class="border-2 border-dashed border-emerald-200 bg-emerald-50 rounded-xl p-6 h-[140px] flex flex-col items-center justify-center text-center hover:bg-emerald-100 transition-colors cursor-pointer group">
              <ImageIcon class="w-8 h-8 text-emerald-400 mx-auto mb-2 group-hover:text-emerald-600" />
              <p class="text-sm text-emerald-600 font-medium">Unggah Banner Promo</p>
            </div>
            <p class="text-xs text-gray-400 mt-1">Rekomendasi ukuran memanjang (1200x400px).</p>
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
          <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-colors">
            <Save class="w-4 h-4" /> Simpan Pengaturan Promo
          </button>
        </div>
      </div>
    </div>



  </div>
</template>
