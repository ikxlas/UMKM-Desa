<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { User as UserIcon, Lock, Save, Eye, EyeOff, ShieldCheck } from 'lucide-vue-next'

const user = ref({
  name: '',
  email: ''
})

const passwordData = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const isSaving = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

onMounted(() => {
  const localUser = localStorage.getItem('admin_user')
  if (localUser) {
    const parsed = JSON.parse(localUser)
    user.value.name = parsed.name || ''
    user.value.email = parsed.email || ''
  }
})

const saveProfile = async () => {
  if (passwordData.value.new_password && passwordData.value.new_password !== passwordData.value.new_password_confirmation) {
    errorMessage.value = 'Kata sandi baru tidak cocok dengan konfirmasi'
    setTimeout(() => errorMessage.value = '', 5000)
    return
  }

  isSaving.value = true
  errorMessage.value = ''
  successMessage.value = ''

  try {
    const payload: any = {
      name: user.value.name,
      email: user.value.email
    }

    if (passwordData.value.new_password) {
      payload.current_password = passwordData.value.current_password
      payload.password = passwordData.value.new_password
    }

    const res = await fetch('http://127.0.0.1:8000/api/profile', {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    })

    const data = await res.json()

    if (!res.ok || !data.success) {
      throw new Error(data.message || 'Gagal memperbarui profil.')
    }

    successMessage.value = 'Profil berhasil diperbarui!'
    localStorage.setItem('admin_user', JSON.stringify(data.user))
    
    // reset password fields
    passwordData.value.current_password = ''
    passwordData.value.new_password = ''
    passwordData.value.new_password_confirmation = ''
    
    setTimeout(() => successMessage.value = '', 5000)
  } catch (error: any) {
    errorMessage.value = error.message
    setTimeout(() => errorMessage.value = '', 5000)
  } finally {
    isSaving.value = false
  }
}
</script>

<template>
  <div class="space-y-10 pb-16 max-w-5xl mx-auto">
    
    <!-- HEADER -->
    <div class="mb-8">
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pengaturan Profil</h1>
      <p class="text-base text-gray-500 mt-2">Perbarui nama layar, alamat email, dan pastikan akun Anda tetap aman dengan kata sandi yang kuat.</p>
    </div>

    <!-- Alert Messages -->
    <div v-if="successMessage" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm transition-all">
      <ShieldCheck class="w-6 h-6 text-emerald-500 flex-shrink-0" />
      <span class="font-medium">{{ successMessage }}</span>
    </div>

    <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl flex items-center gap-3 shadow-sm transition-all">
      <div class="w-1.5 h-6 bg-red-500 rounded-full flex-shrink-0"></div>
      <span class="font-medium">{{ errorMessage }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
      
      <!-- Kolom 1 (Data Diri) -->
      <div class="lg:col-span-6 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-500 p-6 relative overflow-hidden flex items-center gap-4">
          <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
          <div class="relative z-10 bg-white/20 p-3 rounded-xl backdrop-blur-sm text-white">
            <UserIcon class="w-6 h-6 stroke-[1.5]" />
          </div>
          <div class="relative z-10">
            <h2 class="text-xl font-bold text-white tracking-wide">Informasi Pribadi</h2>
            <p class="text-blue-50 text-sm opacity-90 drop-shadow-sm">Ganti detail identitas Anda.</p>
          </div>
        </div>

        <div class="p-8 space-y-6">
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
            <input v-model="user.name" type="text" placeholder="Administrator" class="w-full px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-gray-800" />
          </div>
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Alamat Email Log In</label>
            <input v-model="user.email" type="email" placeholder="admin@desa.com" class="w-full px-5 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all text-gray-800" />
          </div>
        </div>
      </div>

      <!-- Kolom 2 (Keamanan) -->
      <div class="lg:col-span-6 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow overflow-hidden group">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-500 p-6 relative overflow-hidden flex items-center gap-4">
          <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay"></div>
          <div class="relative z-10 bg-white/20 p-3 rounded-xl backdrop-blur-sm text-white">
            <Lock class="w-6 h-6 stroke-[1.5]" />
          </div>
          <div class="relative z-10">
            <h2 class="text-xl font-bold text-white tracking-wide">Area Keamanan</h2>
            <p class="text-emerald-50 text-sm opacity-90 drop-shadow-sm">Ganti sandi rahasia Anda di sini.</p>
          </div>
        </div>

        <div class="p-8 space-y-6">
          <div class="space-y-1.5 focus-within:text-emerald-600 transition-colors">
            <label class="text-sm font-semibold text-gray-700">Kata Sandi Saat Ini</label>
            <p class="text-xs text-gray-400 font-medium pb-1.5">Wajib diisi jika Anda ingin menyetel sandi baru</p>
            <div class="relative">
              <input v-model="passwordData.current_password" :type="showCurrentPassword ? 'text' : 'password'" placeholder="••••••••" class="w-full pl-5 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-gray-800" />
              <button @click="showCurrentPassword = !showCurrentPassword" type="button" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-emerald-600">
                <EyeOff v-if="!showCurrentPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
          </div>
          
          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Kata Sandi Baru (Opsional)</label>
            <div class="relative">
              <input v-model="passwordData.new_password" :type="showNewPassword ? 'text' : 'password'" placeholder="••••••••" class="w-full pl-5 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-gray-800" />
              <button @click="showNewPassword = !showNewPassword" type="button" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-emerald-600">
                <EyeOff v-if="!showNewPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-semibold text-gray-700">Ulangi Kata Sandi Baru</label>
            <div class="relative">
              <input v-model="passwordData.new_password_confirmation" :type="showNewPassword ? 'text' : 'password'" placeholder="••••••••" class="w-full pl-5 pr-12 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all text-gray-800" />
              <button @click="showNewPassword = !showNewPassword" type="button" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-emerald-600">
                <EyeOff v-if="!showNewPassword" class="h-4 w-4" />
                <Eye v-else class="h-4 w-4" />
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- Tombol Simpan (Fixed / Floating di tengah) -->
    <div class="flex justify-start lg:justify-end pt-4">
      <button @click="saveProfile" :disabled="isSaving" class="bg-gray-900 hover:bg-emerald-600 text-white font-semibold py-4 px-10 rounded-2xl flex items-center justify-center min-w-[240px] gap-3 transition-all duration-300 disabled:opacity-70 shadow-lg hover:shadow-emerald-500/30 overflow-hidden relative group">
        <span class="absolute inset-0 w-full h-full opacity-0 group-hover:opacity-30 bg-gradient-to-t from-black via-transparent to-transparent pointer-events-none transition-opacity"></span>
        <Save class="w-5 h-5 flex-shrink-0 relative z-10" :class="{'animate-spin text-gray-400': isSaving}" /> 
        <span class="relative z-10">{{ isSaving ? 'Merekam ke Server...' : 'Simpan Semua Perubahan' }}</span>
      </button>
    </div>

  </div>
</template>
