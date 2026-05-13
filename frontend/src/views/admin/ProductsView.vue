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
  X
} from 'lucide-vue-next'

// State untuk mengontrol mode tampilan (List atau Form)
const isFormMode = ref(false)
const editingProductId = ref<number | null>(null)

// Mock Data Produk
const products = ref([
  {
    id: 1,
    name: 'Kripik Tempe Renyah',
    category: 'Makanan',
    price: 15000,
    merchant: 'Pak Budi Snack',
    stockStatus: 'Tersedia',
    image: '/images/kripik_main.png'
  },
  {
    id: 2,
    name: 'Anyaman Bambu Premium',
    category: 'Kerajinan',
    price: 75000,
    merchant: 'Bengkel Kayu Jati',
    stockStatus: 'Habis',
    image: '/images/prod_1.png'
  }
])

const openAddForm = () => {
  editingProductId.value = null
  isFormMode.value = true
}

const openEditForm = (id: number) => {
  editingProductId.value = id
  isFormMode.value = true
}

const closeForm = () => {
  isFormMode.value = false
  editingProductId.value = null
}

const formatRupiah = (number: number) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number)
}
</script>

<template>
  <div class="space-y-6 pb-12">
    <!-- MODE DAFTAR (LIST) -->
    <div v-if="!isFormMode" class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Manajemen Produk</h1>
          <p class="text-sm text-gray-500 mt-1">Kelola semua produk UMKM, harga, dan ketersediaan.</p>
        </div>
        <button @click="openAddForm" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors">
          <Plus class="w-5 h-5" /> Tambah Produk
        </button>
      </div>

      <!-- Filter & Search Bar -->
      <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 justify-between items-center">
        <div class="relative w-full md:w-96">
          <Search class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input 
            type="text" 
            placeholder="Cari nama produk atau merchant..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
          />
        </div>
        <div class="flex gap-2 w-full md:w-auto">
          <select class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
            <option value="">Semua Kategori</option>
            <option value="Makanan">Makanan</option>
            <option value="Kerajinan">Kerajinan</option>
          </select>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500 font-semibold">
                <th class="py-4 px-6">Produk</th>
                <th class="py-4 px-6">Kategori</th>
                <th class="py-4 px-6">Harga</th>
                <th class="py-4 px-6">UMKM</th>
                <th class="py-4 px-6">Status Stok</th>
                <th class="py-4 px-6 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
              <tr v-for="prod in products" :key="prod.id" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-4 px-6 flex items-center gap-4">
                  <img :src="prod.image" alt="Produk" class="w-12 h-12 rounded-lg object-cover border border-gray-100" />
                  <span class="font-medium text-gray-900">{{ prod.name }}</span>
                </td>
                <td class="py-4 px-6">{{ prod.category }}</td>
                <td class="py-4 px-6 font-medium">{{ formatRupiah(prod.price) }}</td>
                <td class="py-4 px-6">{{ prod.merchant }}</td>
                <td class="py-4 px-6">
                  <span 
                    class="px-2.5 py-1 rounded-full text-xs font-medium"
                    :class="prod.stockStatus === 'Tersedia' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                  >
                    {{ prod.stockStatus }}
                  </span>
                </td>
                <td class="py-4 px-6 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button @click="openEditForm(prod.id)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
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
            <h1 class="text-2xl font-bold text-gray-900">{{ editingProductId ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>
            <p class="text-sm text-gray-500 mt-1">Lengkapi informasi produk UMKM di bawah ini.</p>
          </div>
        </div>
        <div class="hidden md:flex gap-3">
          <button @click="closeForm" class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors">
            Batal
          </button>
          <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 flex items-center gap-2 transition-colors">
            <Save class="w-4 h-4" /> Simpan Produk
          </button>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Informasi Dasar & Link -->
        <div class="lg:col-span-2 space-y-6">
          
          <!-- Informasi Dasar -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Informasi Dasar</h3>
            
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Nama Produk <span class="text-red-500">*</span></label>
              <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Contoh: Kripik Tempe Renyah" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="15000" />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                  <option value="">Pilih Kategori</option>
                  <option value="makanan">Makanan</option>
                  <option value="kerajinan">Kerajinan</option>
                  <option value="pertanian">Pertanian</option>
                  <option value="fashion">Fashion</option>
                  <option value="furnitur">Furnitur</option>
                </select>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
              <textarea rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Tuliskan deskripsi produk yang menarik..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Nama UMKM <span class="text-red-500">*</span></label>
                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Contoh: Pak Budi Snack" />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Lokasi UMKM</label>
                <input type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Contoh: Dusun Krajan, Purwoasri" />
              </div>
            </div>
          </div>

          <!-- Link Pembelian -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Link Pembelian</h3>
            <p class="text-xs text-gray-500 mb-4">Masukkan URL toko/produk agar pembeli bisa langsung memesan. Kosongkan jika tidak ada.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/whatsapp/25D366" class="w-4 h-4" /> WhatsApp
                </label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="https://wa.me/62..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/grab/00B14F" class="w-4 h-4" /> GrabFood
                </label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Link GrabFood..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/gojek/EE2737" class="w-4 h-4" /> GoFood
                </label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Link GoFood..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/shopee/EE4D2D" class="w-4 h-4" /> Shopee
                </label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Link Shopee..." />
              </div>
            </div>
          </div>

        </div>

        <!-- Kolom Kanan: Media & Status -->
        <div class="space-y-6">
          
          <!-- Media Foto -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Media Produk</h3>
            
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Foto Utama</label>
              <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                <ImageIcon class="w-8 h-8 text-gray-400 mx-auto mb-2 group-hover:text-emerald-500" />
                <p class="text-sm text-gray-600 font-medium">Klik untuk unggah foto utama</p>
                <p class="text-xs text-gray-400 mt-1">PNG, JPG maksimal 2MB</p>
              </div>
            </div>

            <div class="space-y-1.5 pt-4">
              <label class="text-sm font-medium text-gray-700">Galeri Foto Tambahan</label>
              <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer">
                <p class="text-sm text-emerald-600 font-medium">+ Tambah Foto (Maks 4)</p>
              </div>
            </div>
          </div>

          <!-- Status & Badge -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Status & Badge</h3>
            
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Status Stok</label>
              <select class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                <option value="tersedia">Tersedia</option>
                <option value="habis">Habis</option>
              </select>
            </div>

            <div class="pt-4 space-y-3">
              <label class="text-sm font-medium text-gray-700">Badge Produk</label>
              
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500" />
                <span class="text-sm text-gray-700">Terlaris (Best Seller)</span>
              </label>
              
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500" />
                <span class="text-sm text-gray-700">Produk Baru (New)</span>
              </label>

              <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500" checked />
                <span class="text-sm text-gray-700">Ready Stock</span>
              </label>
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
