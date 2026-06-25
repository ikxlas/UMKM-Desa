<script setup lang="ts">
import { ref, computed } from 'vue'
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

import { onMounted } from 'vue'

// State untuk mengontrol mode tampilan
const isFormMode = ref(false)
const editingProductId = ref<number | null>(null)
const isLoading = ref(false)

// Data API
const products = ref<any[]>([])
const merchants = ref<any[]>([])
const categories = ref<any[]>([])

// Filter State
const searchQuery = ref('')
const selectedCategory = ref('')

const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                        (p.merchant?.name && p.merchant.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
    const matchCat = selectedCategory.value === '' ? true : p.category_id == selectedCategory.value
    return matchSearch && matchCat
  })
})

const imageFile = ref<File | null>(null)
const imagePreviewUrl = ref('')
const galleryFiles = ref<File[]>([])
const galleryPreviewUrls = ref<string[]>([])

// State Form
const formData = ref({
  name: '',
  category_id: '',
  merchant_id: '',
  price: 0,
  description: '',
  stock: 0,
  unit: 'pcs',
  image: '/images/kripik_main.png',
  gallery_images: [] as string[],
  is_active: true,
  is_featured: false,
  buy_links: { whatsapp: '', grabfood: '', gofood: '', shopee: '' }
})

const fetchData = async () => {
  isLoading.value = true
  try {
    const [prodRes, merchRes, catRes] = await Promise.all([
      fetch('http://127.0.0.1:8000/api/products'),
      fetch('http://127.0.0.1:8000/api/merchants'),
      fetch('http://127.0.0.1:8000/api/categories')
    ])
    
    products.value = await prodRes.json()
    merchants.value = await merchRes.json()
    categories.value = await catRes.json()
  } catch (error) {
    console.error('Error fetching data:', error)
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchData()
})

const openAddForm = () => {
  editingProductId.value = null
  formData.value = {
    name: '', category_id: '', merchant_id: '',
    description: '',
    price: 0,
    stock: 0,
    unit: 'pcs',
    image: '/images/kripik_main.png',
    gallery_images: [],
    is_active: true,
    is_featured: false,
    buy_links: { whatsapp: '', grabfood: '', gofood: '', shopee: '' }
  }
  isFormMode.value = true
}

const openEditForm = (product: any) => {
  editingProductId.value = product.id
  
  let parsedBuyLinks = { whatsapp: '', grabfood: '', gofood: '', shopee: '' }
  if (product.buy_links) {
    try {
      parsedBuyLinks = typeof product.buy_links === 'string' ? JSON.parse(product.buy_links) : product.buy_links
    } catch(e) {}
  }
  
  let parsedGallery = []
  if (product.gallery_images) {
    try {
      parsedGallery = typeof product.gallery_images === 'string' ? JSON.parse(product.gallery_images) : product.gallery_images
    } catch(e) {}
  }

  formData.value = {
    name: product.name,
    category_id: product.category_id,
    merchant_id: product.merchant_id,
    price: product.price,
    description: product.description || '',
    stock: product.stock,
    unit: product.unit || 'pcs',
    image: product.image || '/images/kripik_main.png',
    gallery_images: parsedGallery,
    is_active: product.is_active,
    is_featured: product.is_featured,
    buy_links: parsedBuyLinks
  }
  imagePreviewUrl.value = ''
  galleryPreviewUrls.value = []
  imageFile.value = null
  galleryFiles.value = []
  isFormMode.value = true
}

const closeForm = () => {
  isFormMode.value = false
  editingProductId.value = null
  imageFile.value = null
  imagePreviewUrl.value = ''
  galleryFiles.value = []
  galleryPreviewUrls.value = []
}

const handleImageUpload = (e: any) => {
  const file = e.target.files[0]
  if (file) {
    imageFile.value = file
    imagePreviewUrl.value = URL.createObjectURL(file)
  }
}

const handleGalleryUpload = (e: any) => {
  if (!e.target.files) return
  const files = Array.from(e.target.files) as File[]
  // limit to 4
  const existingCount = (formData.value.gallery_images || []).length
  const availableSlots = 4 - existingCount - galleryFiles.value.length
  if (availableSlots <= 0) return

  const newFiles = files.slice(0, availableSlots)
  
  newFiles.forEach(f => {
    galleryFiles.value.push(f)
    galleryPreviewUrls.value.push(URL.createObjectURL(f))
  })
}

const removeGalleryPreview = (index: number) => {
  galleryFiles.value.splice(index, 1)
  galleryPreviewUrls.value.splice(index, 1)
}

const saveProduct = async () => {
  if (!formData.value.name || !formData.value.category_id || !formData.value.merchant_id || formData.value.price <= 0) {
    alert('Nama, Kategori, Merchant, dan Harga yang valid wajib diisi!')
    return
  }

  try {
    const payload = new FormData()
    payload.append('name', formData.value.name || '')
    payload.append('merchant_id', (formData.value.merchant_id || '').toString())
    payload.append('category_id', (formData.value.category_id || '').toString())
    payload.append('description', formData.value.description || '')
    payload.append('price', (formData.value.price || 0).toString())
    payload.append('stock', (formData.value.stock || 0).toString())
    payload.append('unit', formData.value.unit || 'pcs')
    payload.append('is_active', formData.value.is_active ? '1' : '0')
    payload.append('is_featured', formData.value.is_featured ? '1' : '0')
    payload.append('buy_links', JSON.stringify(formData.value.buy_links))

    if (imageFile.value) {
      payload.append('image_file', imageFile.value)
    }

    galleryFiles.value.forEach((file) => {
      payload.append('gallery_files[]', file)
    })

    if (editingProductId.value) {
      payload.append('_method', 'PUT')
      const response = await fetch(`http://127.0.0.1:8000/api/products/${editingProductId.value}`, {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: payload
      })
      if (!response.ok) {
        const err = await response.json()
        throw new Error(err.message || 'Gagal memperbarui produk')
      }
    } else {
      const response = await fetch('http://127.0.0.1:8000/api/products', {
        method: 'POST',
        headers: { 'Accept': 'application/json' },
        body: payload
      })
      if (!response.ok) {
        const err = await response.json()
        throw new Error(err.message || 'Gagal menyimpan produk')
      }
    }
    await fetchData()
    closeForm()
  } catch (error: any) {
    console.error('Error saving product:', error)
    alert(error.message || 'Terjadi kesalahan saat menyimpan produk.')
  }
}

const deleteProduct = async (id: number) => {
  if (!confirm('Yakin ingin menghapus produk ini?')) return
  try {
    await fetch(`http://127.0.0.1:8000/api/products/${id}`, {
      method: 'DELETE',
      headers: { 'Accept': 'application/json' }
    })
    await fetchData()
  } catch (error) {
    console.error('Error deleting product:', error)
  }
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
            v-model="searchQuery"
            placeholder="Cari nama produk atau merchant..." 
            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
          />
        </div>
        <div class="flex gap-2 w-full md:w-auto">
          <select v-model="selectedCategory" class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
            <option value="">Semua Kategori</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
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
              <tr v-if="isLoading">
                <td colspan="6" class="text-center py-8">
                  <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-600 mx-auto"></div>
                </td>
              </tr>
              <tr v-else-if="filteredProducts.length === 0">
                <td colspan="6" class="text-center py-8 text-gray-500">Belum ada produk.</td>
              </tr>
              <tr v-else v-for="prod in filteredProducts" :key="prod.id" class="hover:bg-gray-50/50 transition-colors">
                <td class="py-4 px-6 flex items-center gap-4">
                  <img :src="prod.image || '/images/kripik_main.png'" alt="Produk" class="w-12 h-12 rounded-lg object-cover border border-gray-100" />
                  <span class="font-medium text-gray-900">{{ prod.name }}</span>
                </td>
                <td class="py-4 px-6">{{ prod.category?.name || '-' }}</td>
                <td class="py-4 px-6 font-medium">{{ formatRupiah(prod.price) }}</td>
                <td class="py-4 px-6">{{ prod.merchant?.name || '-' }}</td>
                <td class="py-4 px-6">
                  <span 
                    class="px-2.5 py-1 rounded-full text-xs font-medium"
                    :class="prod.stock > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'"
                  >
                    {{ prod.stock > 0 ? 'Tersedia' : 'Habis' }}
                  </span>
                </td>
                <td class="py-4 px-6 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button @click="openEditForm(prod)" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                      <Edit class="w-4 h-4" />
                    </button>
                    <button @click="deleteProduct(prod.id)" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
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
    <div v-else class="space-y-6 max-w-5xl mx-auto pb-24 md:pb-0">
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
          <button @click="saveProduct" class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 flex items-center gap-2 transition-colors">
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
              <input v-model="formData.name" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Contoh: Kripik Tempe Renyah" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Harga (Rp) <span class="text-red-500">*</span></label>
                <input v-model="formData.price" type="number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="15000" />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                <select v-model="formData.category_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                  <option value="">Pilih Kategori</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
              </div>
            </div>

            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Deskripsi Lengkap</label>
              <textarea v-model="formData.description" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Tuliskan deskripsi produk yang menarik..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Merchant UMKM <span class="text-red-500">*</span></label>
                <select v-model="formData.merchant_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                  <option value="">Pilih Merchant</option>
                  <option v-for="merch in merchants" :key="merch.id" :value="merch.id">{{ merch.name }}</option>
                </select>
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700">Stok (Jumlah)</label>
                <input v-model="formData.stock" type="number" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Contoh: 100" />
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
                <input v-model="formData.buy_links.whatsapp" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="https://wa.me/62..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/grab/00B14F" class="w-4 h-4" /> GrabFood
                </label>
                <input v-model="formData.buy_links.grabfood" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Link GrabFood..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/gojek/EE2737" class="w-4 h-4" /> GoFood
                </label>
                <input v-model="formData.buy_links.gofood" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Link GoFood..." />
              </div>
              <div class="space-y-1.5">
                <label class="text-sm font-medium text-gray-700 flex items-center gap-2">
                  <img src="https://cdn.simpleicons.org/shopee/EE4D2D" class="w-4 h-4" /> Shopee
                </label>
                <input v-model="formData.buy_links.shopee" type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Link Shopee..." />
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
              <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group relative overflow-hidden h-[200px] flex flex-col justify-center">
                <input type="file" accept="image/*" @change="handleImageUpload" class="absolute inset-0 opacity-0 cursor-pointer z-20" />
                <template v-if="!imagePreviewUrl && !formData.image">
                  <p class="text-sm text-emerald-600 font-medium">Klik untuk memilih gambar</p>
                  <p class="text-xs text-gray-400 mt-1">Disarankan format JPG/PNG kualitas tinggi</p>
                  <p class="text-xs text-gray-400">Rasio 1:1, Maksimal 5MB</p>
                </template>
                <div v-else class="absolute inset-0 pointer-events-none">
                  <img :src="imagePreviewUrl || formData.image" class="w-full h-full object-cover" />
                  <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex flex-col items-center justify-center transition-opacity">
                    <p class="text-sm text-white font-bold">Klik untuk mengganti</p>
                  </div>
                </div>
              </div>
            </div>

            <div class="space-y-1.5 pt-4">
              <label class="text-sm font-medium text-gray-700">Galeri Foto Tambahan</label>
              
              <div class="grid grid-cols-4 gap-2 mb-2">
                <!-- Tampilkan Existing URL dari Database -->
                <div v-for="(url, idx) in (formData.gallery_images || [])" :key="'db-'+idx" class="aspect-square rounded-xl border border-gray-200 overflow-hidden relative group">
                  <img :src="url" class="w-full h-full object-cover" />
                  <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                     <!-- Di aplikasi nyata kita bisa tambah tombol hapus, tapi untuk kesederhanaan, kita biarkan saja -->
                  </div>
                </div>

                <!-- Tampilkan Previews dari file baru -->
                <div v-for="(url, idx) in galleryPreviewUrls" :key="'new-'+idx" class="aspect-square rounded-xl border border-gray-200 overflow-hidden relative group">
                  <img :src="url" class="w-full h-full object-cover" />
                  <button @click="removeGalleryPreview(idx)" type="button" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-all">
                    <X class="w-3 h-3" />
                  </button>
                </div>
              </div>

              <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors cursor-pointer relative overflow-hidden" v-if="((formData.gallery_images || []).length + galleryPreviewUrls.length) < 4">
                <input type="file" accept="image/*" multiple @change="handleGalleryUpload" class="absolute inset-0 opacity-0 cursor-pointer z-20" />
                <p class="text-sm text-emerald-600 font-medium">+ Tambah Foto (Sisa {{ 4 - ((formData.gallery_images || []).length + galleryPreviewUrls.length) }})</p>
              </div>
            </div>
          </div>

          <!-- Status & Badge -->
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-4">
            <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Status & Badge</h3>
            
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Status Stok</label>
              <select v-model="formData.is_active" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all bg-white">
                <option :value="true">Tersedia (Aktif)</option>
                <option :value="false">Habis / Tidak Aktif</option>
              </select>
            </div>

            <div class="pt-4 space-y-3">
              <label class="text-sm font-medium text-gray-700">Badge Produk</label>
              <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" v-model="formData.is_featured" class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500" />
                <span class="text-sm text-gray-700">Tandai Sebagai Produk Unggulan</span>
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
