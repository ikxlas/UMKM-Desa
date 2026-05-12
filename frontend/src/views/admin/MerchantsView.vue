<script setup lang="ts">
import { ref } from 'vue'
import { 
  Plus, 
  Search, 
  Edit, 
  Trash2, 
  ArrowLeft,
  Image as ImageIcon,
  Save,
  MapPin,
  Phone,
  Instagram,
  Facebook
} from 'lucide-vue-next'

// State untuk mengontrol mode tampilan (List atau Form)
const isFormMode = ref(false)
const editingMerchantId = ref<number | null>(null)

// Mock Data Merchant
const merchants = ref([
  {
    id: 1,
    name: 'Bengkel Kayu Jati Purwoasri',
    contact: '+62 812-3456-7890',
    address: 'Dusun Krajan RT 01/RW 02',
    logo: '/images/merchant.png'
  },
  {
    id: 2,
    name: 'Pak Budi Snack & Kripik',
    contact: '+62 857-1122-3344',
    address: 'Dusun Sumberjo RT 03/RW 01',
    logo: '/images/kripik_main.png'
  }
])

const openAddForm = () => {
  editingMerchantId.value = null
  isFormMode.value = true
}

const openEditForm = (id: number) => {
  editingMerchantId.value = id
  isFormMode.value = true
}

const closeForm = () => {
  isFormMode.value = false
  editingMerchantId.value = null
}
</script>

<template>
  <div class="space-y-6 pb-12">
    <!-- MODE DAFTAR (LIST) -->
    <div v-if="!isFormMode" class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Manajemen Merchant</h1>
          <p class="text-sm text-gray-500 mt-1">Kelola data toko dan pelaku UMKM Desa Purwoasri.</p>
        </div>
        <button @click="openAddForm" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
          <Plus class="w-5 h-5" /> Tambah Merchant
        </button>
      </div>

      <!-- Filter & Search Bar -->
      <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-96">
          <Search class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input 
            type="text" 
            placeholder="Cari nama toko atau pemilik..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
          />
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                <th class="py-4 px-6">Nama Usaha</th>
                <th class="py-4 px-6">Kontak (WA)</th>
                <th class="py-4 px-6">Alamat Lengkap</th>
                <th class="py-4 px-6 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
              <tr v-for="merchant in merchants" :key="merchant.id" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-4 px-6 flex items-center gap-4">
                  <img :src="merchant.logo" alt="Logo" class="w-10 h-10 rounded-full object-cover border border-gray-200" />
                  <span class="font-bold text-gray-900">{{ merchant.name }}</span>
                </td>
                <td class="py-4 px-6 font-medium text-gray-600">{{ merchant.contact }}</td>
                <td class="py-4 px-6 text-gray-500 truncate max-w-xs">{{ merchant.address }}</td>
                <td class="py-4 px-6 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button @click="openEditForm(merchant.id)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                      <Edit class="w-4 h-4" />
                    </button>
                    <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- MODE FORMULIR (FORM) -->
    <div v-else class="space-y-6 max-w-5xl mx-auto">
      <!-- Form Header -->
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <button @click="closeForm" class="p-2 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
            <ArrowLeft class="w-5 h-5 text-gray-600" />
          </button>
          <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ editingMerchantId ? 'Edit Merchant' : 'Tambah Merchant Baru' }}</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi profil dan informasi kontak UMKM.</p>
          </div>
        </div>
        <div class="hidden md:flex gap-3">
          <button @click="closeForm" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
            Batal
          </button>
          <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 flex items-center gap-2 transition-colors">
            <Save class="w-4 h-4" /> Simpan Merchant
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Profil & Kontak -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Informasi Usaha -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Profil Usaha</h3>
            
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Nama Usaha UMKM <span class="text-red-500">*</span></label>
              <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Contoh: Bengkel Kayu Jati Purwoasri" />
            </div>

            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Deskripsi Usaha</label>
              <textarea rows="5" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Ceritakan sejarah, keunggulan, atau jenis produk yang dijual oleh UMKM ini..."></textarea>
            </div>
          </div>

          <!-- Kontak & Lokasi -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Kontak & Lokasi</h3>
            
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                <MapPin class="w-4 h-4 text-gray-500" /> Alamat Lengkap <span class="text-red-500">*</span>
              </label>
              <textarea rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Contoh: Jl. Diponegoro RT 02 / RW 04, Desa Purwoasri..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <Phone class="w-4 h-4 text-emerald-600" /> Nomor WhatsApp
                </label>
                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="0812-3456-7890" />
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <Instagram class="w-4 h-4 text-pink-600" /> Link Instagram
                </label>
                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="https://instagram.com/..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <Facebook class="w-4 h-4 text-blue-600" /> Link Facebook
                </label>
                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="https://facebook.com/..." />
              </div>
            </div>
          </div>

        </div>

        <!-- Kolom Kanan: Media Gambar -->
        <div class="space-y-6">
          
          <!-- Unggah Logo -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Logo Usaha</h3>
            <div class="border-2 border-dashed border-gray-300 rounded-full w-32 h-32 mx-auto flex flex-col items-center justify-center hover:bg-gray-50 transition-colors cursor-pointer group">
              <ImageIcon class="w-6 h-6 text-gray-400 mb-1 group-hover:text-emerald-500" />
              <span class="text-[10px] text-gray-500 font-medium uppercase tracking-wider">Unggah</span>
            </div>
            <p class="text-xs text-center text-gray-400 mt-2">Disarankan gambar berbentuk persegi (1:1)</p>
          </div>

          <!-- Unggah Foto Fisik Usaha -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Foto Tempat Usaha</h3>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
              <ImageIcon class="w-8 h-8 text-gray-400 mx-auto mb-2 group-hover:text-emerald-500" />
              <p class="text-sm text-gray-600 font-medium">Unggah foto toko/bengkel</p>
              <p class="text-xs text-gray-400 mt-1">Ini akan mempermudah pembeli menemukan lokasi.</p>
            </div>
          </div>

        </div>
      </div>

      <!-- Mobile Actions (Sticky Bottom) -->
      <div class="md:hidden fixed bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 flex gap-3 z-50">
        <button @click="closeForm" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
          Batal
        </button>
        <button class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 flex items-center justify-center gap-2 transition-colors">
          <Save class="w-4 h-4" /> Simpan
        </button>
      </div>

    </div>
  </div>
</template>
