<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { 
  Image as ImageIcon, 
  Save, 
  Star,
  Tag,
  MonitorPlay
} from 'lucide-vue-next'

// State untuk pengaturan
const settings = ref({
  hero_headline: '',
  hero_subtitle: '',
  hero_image: '/images/hero.png',
  promo_is_active: true,
  promo_title: '',
  promo_link: '',
  promo_image: ''
})

const isSavingHero = ref(false)
const isSavingPromo = ref(false)

const heroImageFile = ref<File | null>(null)
const promoImageFile = ref<File | null>(null)

// Preview images
const heroPreviewUrl = ref('')
const promoPreviewUrl = ref('')

const fetchSettings = async () => {
  try {
    const res = await fetch('http://127.0.0.1:8000/api/settings')
    const data = await res.json()
    
    // Map data to settings
    if(data.hero_headline) settings.value.hero_headline = data.hero_headline
    if(data.hero_subtitle) settings.value.hero_subtitle = data.hero_subtitle
    if(data.hero_image) settings.value.hero_image = data.hero_image
    
    if(data.promo_is_active !== undefined) settings.value.promo_is_active = data.promo_is_active === 'true'
    if(data.promo_title) settings.value.promo_title = data.promo_title
    if(data.promo_link) settings.value.promo_link = data.promo_link
    if(data.promo_image) settings.value.promo_image = data.promo_image

  } catch (e) {
    console.error('Failed to fetch settings', e)
  }
}

onMounted(() => {
  fetchSettings()
})

const handleHeroImageChange = (e: any) => {
  const file = e.target.files[0]
  if(file) {
    heroImageFile.value = file
    heroPreviewUrl.value = URL.createObjectURL(file)
  }
}

const handlePromoImageChange = (e: any) => {
  const file = e.target.files[0]
  if(file) {
    promoImageFile.value = file
    promoPreviewUrl.value = URL.createObjectURL(file)
  }
}

const saveHeroBanner = async () => {
  isSavingHero.value = true
  const formData = new FormData()
  formData.append('hero_headline', settings.value.hero_headline)
  formData.append('hero_subtitle', settings.value.hero_subtitle)
  if(heroImageFile.value) {
    formData.append('hero_image', heroImageFile.value)
  }

  try {
    await fetch('http://127.0.0.1:8000/api/settings', {
      method: 'POST',
      body: formData
    })
    alert('Pengaturan Banner Utama berhasil disimpan!')
    await fetchSettings()
  } catch (e) {
    console.error(e)
  } finally {
    isSavingHero.value = false
  }
}

const savePromoBanner = async () => {
  isSavingPromo.value = true
  const formData = new FormData()
  formData.append('promo_is_active', settings.value.promo_is_active ? 'true' : 'false')
  formData.append('promo_title', settings.value.promo_title)
  formData.append('promo_link', settings.value.promo_link)
  if(promoImageFile.value) {
    formData.append('promo_image', promoImageFile.value)
  }

  try {
    await fetch('http://127.0.0.1:8000/api/settings', {
      method: 'POST',
      body: formData
    })
    alert('Pengaturan Promo Spesial berhasil disimpan!')
    await fetchSettings()
  } catch (e) {
    console.error(e)
  } finally {
    isSavingPromo.value = false
  }
}
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
              <input v-model="settings.hero_headline" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" />
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Subjudul (Deskripsi Singkat)</label>
              <textarea v-model="settings.hero_subtitle" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow"></textarea>
            </div>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-gray-700">Gambar Latar (Background)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 h-[180px] flex flex-col items-center justify-center text-center hover:bg-gray-50 transition-colors cursor-pointer group relative overflow-hidden">
              <input type="file" accept="image/*" @change="handleHeroImageChange" class="absolute inset-0 opacity-0 cursor-pointer z-20" />
              <!-- Preview Gambar Aktif -->
              <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10 pointer-events-none">
                <span class="text-white font-medium text-sm bg-black/50 px-4 py-2 rounded-full">Ganti Gambar</span>
              </div>
              <img :src="heroPreviewUrl || settings.hero_image" alt="Hero Preview" class="absolute inset-0 w-full h-full object-cover" />
            </div>
            <p class="text-xs text-gray-400 mt-1">Rekomendasi rasio 16:9, resolusi 1920x1080px (Maks 2MB).</p>
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
          <button @click="saveHeroBanner" :disabled="isSavingHero" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-colors disabled:opacity-50">
            <Save class="w-4 h-4" /> {{ isSavingHero ? 'Menyimpan...' : 'Simpan Banner Utama' }}
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
            <input type="checkbox" v-model="settings.promo_is_active" class="sr-only" />
            <div class="block w-14 h-8 rounded-full transition-colors" :class="settings.promo_is_active ? 'bg-emerald-500' : 'bg-gray-300'"></div>
            <div class="absolute left-1 top-1 bg-white w-6 h-6 rounded-full transition-transform duration-300 ease-in-out" :class="settings.promo_is_active ? 'translate-x-6' : 'translate-x-0'"></div>
          </div>
          <div class="ml-3 text-sm font-medium" :class="settings.promo_is_active ? 'text-emerald-700' : 'text-gray-500'">
            {{ settings.promo_is_active ? 'Aktif' : 'Nonaktif' }}
          </div>
        </label>
      </div>
      
      <div class="p-6 space-y-6" :class="!settings.promo_is_active ? 'opacity-50 pointer-events-none' : ''">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-4">
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Teks Judul Promo</label>
              <input v-model="settings.promo_title" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" />
            </div>
            <div class="space-y-1.5">
              <label class="text-sm font-medium text-gray-700">Link Tujuan Promo (URL)</label>
              <input v-model="settings.promo_link" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none transition-shadow" placeholder="Contoh: /category?promo=yes" />
            </div>
          </div>
          
          <div class="space-y-1.5">
            <label class="text-sm font-medium text-gray-700">Gambar Banner Promo</label>
            <div class="border-2 border-dashed border-emerald-200 bg-emerald-50 rounded-xl p-6 h-[140px] flex flex-col items-center justify-center text-center hover:bg-emerald-100 transition-colors cursor-pointer group relative overflow-hidden">
              <input type="file" accept="image/*" @change="handlePromoImageChange" class="absolute inset-0 opacity-0 cursor-pointer z-20" />
              
              <div v-if="!promoPreviewUrl && !settings.promo_image" class="pointer-events-none">
                <ImageIcon class="w-8 h-8 text-emerald-400 mx-auto mb-2 group-hover:text-emerald-600" />
                <p class="text-sm text-emerald-600 font-medium">Unggah Banner Promo</p>
              </div>
              <img v-else :src="promoPreviewUrl || settings.promo_image" class="absolute inset-0 w-full h-full object-cover pointer-events-none" />
            </div>
            <p class="text-xs text-gray-400 mt-1">Rekomendasi ukuran memanjang (1200x400px).</p>
          </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-100">
          <button @click="savePromoBanner" :disabled="isSavingPromo" class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-2 px-6 rounded-lg flex items-center gap-2 transition-colors disabled:opacity-50">
            <Save class="w-4 h-4" /> {{ isSavingPromo ? 'Menyimpan...' : 'Simpan Pengaturan Promo' }}
          </button>
        </div>
      </div>
    </div>

  </div>
</template>
