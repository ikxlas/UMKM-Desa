<script setup lang="ts">
import { ref } from 'vue'
import { 
  Plus, 
  Search, 
  Edit, 
  Trash2, 
  Save,
  X,
  Leaf, 
  Croissant, 
  Shirt, 
  Tractor, 
  Sofa, 
  LayoutGrid,
  Coffee,
  Palette,
  Package,
  ShoppingBag,
  Utensils,
  Carrot,
  Gift,
  Camera,
  Scissors,
  Star,
  Watch,
  Headphones,
  Book,
  Wrench,
  Monitor,
  Store,
  Home,
  Briefcase,
  Sparkles,
  Sprout,
  Truck,
  Smartphone
} from 'lucide-vue-next'

// State untuk mengontrol modal
const isModalOpen = ref(false)
const editingCategoryId = ref<number | null>(null)

// State form
const formName = ref('')
const formIconName = ref('')
const formIconType = ref<'preset' | 'upload'>('preset')

// Mock Data Kategori
const categories = ref([
  { id: 1, name: 'Kerajinan', isCustom: false, icon: Leaf, iconName: 'Leaf', customIconUrl: '', productCount: 45 },
  { id: 2, name: 'Makanan', isCustom: false, icon: Croissant, iconName: 'Croissant', customIconUrl: '', productCount: 120 },
  { id: 3, name: 'Pakaian', isCustom: false, icon: Shirt, iconName: 'Shirt', customIconUrl: '', productCount: 32 },
  { id: 4, name: 'Pertanian', isCustom: false, icon: Tractor, iconName: 'Tractor', customIconUrl: '', productCount: 18 },
  { id: 5, name: 'Furnitur', isCustom: false, icon: Sofa, iconName: 'Sofa', customIconUrl: '', productCount: 12 },
  { id: 6, name: 'Lainnya', isCustom: false, icon: LayoutGrid, iconName: 'LayoutGrid', customIconUrl: '', productCount: 8 },
])

// Pilihan Ikon untuk Form
const availableIcons = [
  { name: 'Store', component: Store },
  { name: 'ShoppingBag', component: ShoppingBag },
  { name: 'Package', component: Package },
  { name: 'Utensils', component: Utensils },
  { name: 'Coffee', component: Coffee },
  { name: 'Croissant', component: Croissant },
  { name: 'Shirt', component: Shirt },
  { name: 'Watch', component: Watch },
  { name: 'Leaf', component: Leaf },
  { name: 'Sprout', component: Sprout },
  { name: 'Carrot', component: Carrot },
  { name: 'Tractor', component: Tractor },
  { name: 'Sofa', component: Sofa },
  { name: 'Home', component: Home },
  { name: 'Palette', component: Palette },
  { name: 'Scissors', component: Scissors },
  { name: 'Wrench', component: Wrench },
  { name: 'Camera', component: Camera },
  { name: 'Smartphone', component: Smartphone },
  { name: 'Monitor', component: Monitor },
  { name: 'Headphones', component: Headphones },
  { name: 'Sparkles', component: Sparkles },
  { name: 'Gift', component: Gift },
  { name: 'Book', component: Book },
  { name: 'Briefcase', component: Briefcase },
  { name: 'Truck', component: Truck },
  { name: 'Star', component: Star },
  { name: 'LayoutGrid', component: LayoutGrid },
]

const openAddModal = () => {
  editingCategoryId.value = null
  formName.value = ''
  formIconName.value = 'Package'
  formIconType.value = 'preset'
  isModalOpen.value = true
}

const openEditModal = (cat: any) => {
  editingCategoryId.value = cat.id
  formName.value = cat.name
  formIconType.value = cat.isCustom ? 'upload' : 'preset'
  formIconName.value = cat.iconName
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const saveCategory = () => {
  // Logika simpan data akan di sini
  closeModal()
}
</script>

<template>
  <div class="space-y-6 pb-12 relative">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Kategori Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola jenis kategori barang jualan warga desa.</p>
      </div>
      <button @click="openAddModal" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-4 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm">
        <Plus class="w-5 h-5" /> Tambah Kategori
      </button>
    </div>

    <!-- FILTER & SEARCH -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
      <div class="relative w-full md:w-96">
        <Search class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
        <input 
          type="text" 
          placeholder="Cari nama kategori..." 
          class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm bg-gray-50 focus:bg-white transition-colors"
        />
      </div>
    </div>

    <!-- GRID VIEW UNTUK KATEGORI (Daripada tabel, grid lebih cantik untuk kategori) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="cat in categories" :key="cat.id" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group flex items-start justify-between">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors overflow-hidden shrink-0">
            <component v-if="!cat.isCustom" :is="cat.icon" class="w-7 h-7" />
            <img v-else :src="cat.customIconUrl" alt="Custom Icon" class="w-8 h-8 object-contain" />
          </div>
          <div class="min-w-0 flex-1">
            <h3 class="font-bold text-gray-900 text-lg truncate">{{ cat.name }}</h3>
            <p class="text-sm text-gray-500 truncate">{{ cat.productCount }} Produk terhubung</p>
          </div>
        </div>
        <div class="flex flex-col gap-2">
          <button @click="openEditModal(cat)" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
            <Edit class="w-4 h-4" />
          </button>
          <button class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
            <Trash2 class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL POPUP FORM KATEGORI -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <!-- Backdrop -->
      <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm" @click="closeModal"></div>
      
      <!-- Modal Content -->
      <div class="bg-white rounded-2xl shadow-xl w-full max-w-md relative z-10 flex flex-col max-h-[90vh]">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-100">
          <h2 class="text-xl font-bold text-gray-900">{{ editingCategoryId ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h2>
          <button @click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto custom-scrollbar">
          <div class="space-y-6">
            
            <!-- Input Nama -->
            <div class="space-y-2">
              <label class="text-sm font-medium text-gray-700">Nama Kategori <span class="text-red-500">*</span></label>
              <input 
                v-model="formName"
                type="text" 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" 
                placeholder="Contoh: Sayuran Organik" 
              />
            </div>

            <!-- Tipe Ikon Toggle -->
            <div class="space-y-3">
              <label class="text-sm font-medium text-gray-700">Tipe Ikon</label>
              <div class="flex flex-wrap gap-4 bg-gray-50 p-2 rounded-lg border border-gray-100">
                <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded-md shadow-sm border border-gray-100 flex-1 justify-center transition-all hover:border-emerald-200" :class="formIconType === 'preset' ? 'ring-2 ring-emerald-500' : ''">
                  <input type="radio" v-model="formIconType" value="preset" class="hidden" />
                  <span class="text-sm font-medium" :class="formIconType === 'preset' ? 'text-emerald-700' : 'text-gray-600'">Ikon Bawaan</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer bg-white px-4 py-2 rounded-md shadow-sm border border-gray-100 flex-1 justify-center transition-all hover:border-emerald-200" :class="formIconType === 'upload' ? 'ring-2 ring-emerald-500' : ''">
                  <input type="radio" v-model="formIconType" value="upload" class="hidden" />
                  <span class="text-sm font-medium" :class="formIconType === 'upload' ? 'text-emerald-700' : 'text-gray-600'">Unggah Sendiri</span>
                </label>
              </div>
            </div>

            <!-- Pilih Ikon (Hanya muncul jika tipe 'preset') -->
            <div v-if="formIconType === 'preset'" class="space-y-3">
              <label class="text-sm font-medium text-gray-700 flex justify-between">
                Pilihan Ikon <span class="text-emerald-600 text-xs">Pilih salah satu</span>
              </label>
              <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                <button 
                  v-for="icon in availableIcons" 
                  :key="icon.name"
                  @click="formIconName = icon.name"
                  type="button"
                  class="aspect-square flex items-center justify-center rounded-xl border-2 transition-all"
                  :class="formIconName === icon.name ? 'border-emerald-500 bg-emerald-50 text-emerald-600' : 'border-gray-100 bg-white text-gray-500 hover:border-emerald-200 hover:bg-emerald-50/50'"
                >
                  <component :is="icon.component" class="w-6 h-6" />
                </button>
              </div>
            </div>

            <!-- Unggah Ikon (Hanya muncul jika tipe 'upload') -->
            <div v-else class="space-y-3">
              <label class="text-sm font-medium text-gray-700">Unggah Gambar Ikon</label>
              <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:bg-gray-50 transition-colors cursor-pointer group">
                <ImageIcon class="w-8 h-8 text-gray-400 mx-auto mb-3 group-hover:text-emerald-500 transition-colors" />
                <p class="text-sm text-emerald-600 font-medium">Klik untuk memilih gambar</p>
                <p class="text-xs text-gray-400 mt-1">Disarankan format PNG/SVG transparan</p>
                <p class="text-xs text-gray-400">Rasio 1:1, Maksimal 1MB</p>
              </div>
            </div>

          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 border-t border-gray-100 flex gap-3 bg-gray-50 rounded-b-2xl">
          <button @click="closeModal" class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 bg-white rounded-lg font-medium hover:bg-gray-50 transition-colors">
            Batal
          </button>
          <button @click="saveCategory" class="flex-1 px-4 py-2.5 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 flex items-center justify-center gap-2 transition-colors">
            <Save class="w-4 h-4" /> Simpan
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
</style>
