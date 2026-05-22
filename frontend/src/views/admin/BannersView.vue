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
  <div class="space-y-10 pb-16 max-w-6xl mx-auto">
    
    <!-- HEADER -->
    <div class="mb-8">
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Desain Beranda</h1>
      <p class="text-base text-gray-500 mt-2">Atur estetika banner utama dan promo khusus untuk memikat pelanggan sejak pandangan pertama.</p>
    </div>

    <!-- KARTU 1: HERO BANNER UTAMA -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
      <!-- Gradient Header -->
      <div class="bg-gradient-to-r from-emerald-600 to-teal-500 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        <div class="relative z-10 flex items-center gap-3">
          <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm text-white">
            <MonitorPlay class="w-6 h-6 stroke-[1.5]" />
          </div>
          <div>
            <h2 class="text-xl font-bold text-white tracking-wide">Hero Banner Utama</h2>
            <p class="text-emerald-50 text-sm opacity-90">Bagian layar eksklusif yang paling pertama dilihat oleh pengunjung.</p>
          </div>
        </div>
      </div>
      
      <!-- Body -->
      <div class="p-8 grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Area Form (Kiri) -->
        <div class="lg:col-span-12 xl:col-span-7 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Judul Utama (Headline)</label>
            <input v-model="settings.hero_headline" type="text" placeholder="Cth: Belanja Produk Desa Kualitas Kota" class="w-full px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-gray-800" />
          </div>
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Subjudul (Deskripsi Singkat)</label>
            <textarea v-model="settings.hero_subtitle" rows="3" placeholder="Deskripsikan keunggulan platform di sini..." class="w-full px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-gray-800 resize-none"></textarea>
          </div>
          
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Unggah Gambar Resolusi Tinggi (16:9)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 h-[120px] hover:bg-emerald-50/50 hover:border-emerald-300 transition-colors cursor-pointer relative flex flex-col items-center justify-center text-center">
              <input type="file" accept="image/*" @change="handleHeroImageChange" class="absolute inset-0 opacity-0 cursor-pointer z-20" />
              
              <template v-if="!heroPreviewUrl && !settings.hero_image">
                <p class="text-sm text-emerald-600 font-semibold mb-1">Klik atau Tarik File Gambar Kesini</p>
                <p class="text-xs text-gray-500">Maksimal 5MB, disarankan format JPG/PNG berkualitas tinggi</p>
              </template>
              <div v-else class="pointer-events-none flex flex-col items-center">
                 <p class="text-sm text-emerald-600 font-bold">✓ Background Latar Terpilih</p>
                 <p class="text-xs text-gray-400 mt-1">Klik di mana saja pada area ini untuk mengganti</p>
              </div>
            </div>
          </div>

          <div class="pt-4">
            <button @click="saveHeroBanner" :disabled="isSavingHero" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 px-8 rounded-xl flex items-center justify-center w-full sm:w-auto gap-2 transition-all disabled:opacity-70 disabled:cursor-not-allowed shadow-md shadow-emerald-500/20">
              <Save class="w-5 h-5 flex-shrink-0" :class="{'animate-spin opacity-50': isSavingHero}" /> 
              {{ isSavingHero ? 'Menyimpan Perubahan...' : 'Terapkan Desain Hero Banner' }}
            </button>
          </div>
        </div>

        <!-- Area Visual Preview (Kanan) -->
        <div class="lg:col-span-12 xl:col-span-5 flex flex-col pt-2 lg:pt-8 rounded-2xl">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Live Preview</p>
          <div class="relative w-full aspect-video rounded-2xl overflow-hidden shadow-lg border border-gray-200/50 bg-gray-200 flex flex-col justify-center items-center text-center group-hover:shadow-2xl transition-all duration-500">
            <img v-if="heroPreviewUrl || settings.hero_image" :src="heroPreviewUrl || settings.hero_image" class="absolute inset-0 w-full h-full object-cover z-0" />
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/40 to-transparent z-10 transition-opacity"></div>
            
            <div class="relative z-20 p-6 flex flex-col justify-end h-full w-full text-left">
              <h3 class="text-white font-extrabold text-xl leading-tight line-clamp-2 drop-shadow-md">
                {{ settings.hero_headline || 'Judul Utama Tampil Di Sini' }}
              </h3>
              <p class="text-gray-200 text-xs mt-2 line-clamp-2 drop-shadow">
                {{ settings.hero_subtitle || 'Subjudul atau deskripsi singkat akan muncul di bagian ini sebagai pelengkap informasi.' }}
              </p>
              <div class="mt-4 pt-4 border-t border-white/20">
                <span class="inline-block bg-white text-gray-900 text-[10px] font-bold px-3 py-1.5 rounded-full shadow-sm">BELANJA SEKARANG</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- KARTU 2: BANNER PROMO SPESIAL -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all overflow-hidden group mt-10 relative" :class="!settings.promo_is_active ? 'opacity-80' : ''">
      
      <!-- Gradient Header Promo -->
      <div class="bg-gradient-to-r from-orange-500 to-amber-400 p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 relative overflow-hidden transition-all duration-300" :class="!settings.promo_is_active ? 'grayscale-[60%]' : ''">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
        
        <div class="relative z-10 flex items-center gap-3">
          <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-sm text-white transition-colors duration-300" :class="!settings.promo_is_active ? 'bg-gray-800/30 text-gray-200' : ''">
            <Tag class="w-6 h-6 stroke-[1.5]" />
          </div>
          <div>
            <h2 class="text-xl font-bold text-white tracking-wide transition-colors duration-300" :class="!settings.promo_is_active ? 'text-gray-100' : ''">Banner Spesial (Promo)</h2>
            <p class="text-white text-sm opacity-90 drop-shadow-sm transition-colors duration-300" :class="!settings.promo_is_active ? 'text-gray-300' : ''">Muncul di tengah tata letak produk layaknya spanduk toko.</p>
          </div>
        </div>

        <!-- Premium Toggle Switch -->
        <label class="relative z-10 flex items-center cursor-pointer bg-white/20 p-1.5 rounded-full pr-4 shadow-sm backdrop-blur-md hover:bg-white/30 transition-colors">
          <div class="relative flex items-center justify-center">
            <input type="checkbox" v-model="settings.promo_is_active" class="sr-only" />
            <div class="block w-12 h-6 rounded-full transition-colors duration-300 shadow-inner" :class="settings.promo_is_active ? 'bg-orange-600' : 'bg-gray-400/50'"></div>
            <div class="absolute left-1 top-[2px] bg-white w-5 h-5 rounded-full transition-transform duration-300 shadow-sm" :class="settings.promo_is_active ? 'translate-x-5' : 'translate-x-0'"></div>
          </div>
          <span class="ml-3 text-xs font-bold text-white uppercase tracking-wider drop-shadow-sm truncate min-w-[50px] text-center" :class="!settings.promo_is_active ? 'text-gray-200' : ''">
            {{ settings.promo_is_active ? 'AKTIF' : 'MATI' }}
          </span>
        </label>
      </div>
      
      <!-- Body Promo -->
      <div class="p-8 grid grid-cols-1 lg:grid-cols-12 gap-10 transition-all duration-300" :class="!settings.promo_is_active ? 'opacity-40 grayscale-[30%] pointer-events-none filter blur-[1px]' : ''">
        
        <div class="lg:col-span-12 xl:col-span-7 space-y-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="space-y-2">
              <label class="text-sm font-semibold text-gray-700">Teks Panggilan (Call To Action)</label>
              <input v-model="settings.promo_title" type="text" placeholder="Cth: Diskon Ongkir Minggu Ini" class="w-full px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all text-gray-800" />
            </div>
            <div class="space-y-2">
              <label class="text-sm font-semibold text-gray-700">URL Tujuan Tautan Promo</label>
              <input v-model="settings.promo_link" type="text" placeholder="https://atau-rute-lokal..." class="w-full px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all text-gray-800" />
            </div>
          </div>
          
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Unggah Grafis Promo Memanjang (Opsional)</label>
            <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 h-[120px] hover:bg-orange-50/50 hover:border-orange-300 transition-colors cursor-pointer relative flex flex-col items-center justify-center text-center">
              <input type="file" accept="image/*" @change="handlePromoImageChange" class="absolute inset-0 opacity-0 cursor-pointer z-20" />
              
              <template v-if="!promoPreviewUrl && !settings.promo_image">
                <p class="text-sm text-orange-600 font-semibold mb-1">Seret kemari atau klik untuk memilih file</p>
                <p class="text-xs text-gray-500">Rekomendasi rasio ekstrim memanjang. Maksimal 5MB</p>
              </template>
              <div v-else class="pointer-events-none flex flex-col items-center">
                 <p class="text-sm text-orange-600 font-bold">✓ Visual Promo Terpasang</p>
                 <p class="text-xs text-gray-400 mt-1">Area aktif, klik untuk mengganti gambar promo</p>
              </div>
            </div>
          </div>

          <div class="pt-4">
            <button @click="savePromoBanner" :disabled="isSavingPromo" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-8 rounded-xl flex items-center justify-center w-full sm:w-auto gap-2 transition-all disabled:opacity-70 shadow-md shadow-orange-500/20">
              <Save class="w-5 h-5 flex-shrink-0" :class="{'animate-spin opacity-50': isSavingPromo}" /> 
              {{ isSavingPromo ? 'Memproses Validasi...' : 'Aktualisasi Data Promo' }}
            </button>
          </div>
        </div>

        <div class="lg:col-span-12 xl:col-span-5 flex flex-col pt-2 lg:pt-8 justify-center">
           <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Tata Letak Strip Promo</p>
           <div class="w-full bg-gray-100 rounded-2xl aspect-[3/1] relative overflow-hidden shadow-inner group-hover:shadow-lg transition-all duration-500 border border-gray-200">
              <img v-if="promoPreviewUrl || settings.promo_image" :src="promoPreviewUrl || settings.promo_image" class="absolute inset-0 w-full h-full object-cover z-10" />
              <div v-if="promoPreviewUrl || settings.promo_image" class="absolute inset-0 bg-black/40 z-20 transition-opacity"></div>
              
              <div class="absolute inset-0 z-30 flex items-center justify-center text-center p-6 w-full h-full">
                <p class="font-extrabold text-lg md:text-xl truncate" :class="!(promoPreviewUrl || settings.promo_image) ? 'text-gray-400' : 'text-white drop-shadow-xl w-full'">
                   {{ settings.promo_title || 'Teks Call-To-Action Muncul Disini' }}
                </p>
              </div>
           </div>
        </div>

      </div>
      
      <!-- Overlay jika switch mati (Tambahan pengaman klik di atas body) -->
      <div v-if="!settings.promo_is_active" class="absolute left-0 bottom-0 top-[88px] w-full z-40 cursor-not-allowed"></div>
    </div>

  </div>
</template>
