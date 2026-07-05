<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { Lock, Mail, ArrowRight, Eye, EyeOff } from 'lucide-vue-next'

const router = useRouter()
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

const handleLogin = async () => {
  if (!email.value || !password.value) {
    errorMessage.value = 'Email dan Sandi harus diisi'
    return
  }

  isLoading.value = true
  errorMessage.value = ''

  try {
    const res = await fetch('http://127.0.0.1:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      })
    })

    const data = await res.json()

    if (!res.ok || !data.success) {
      throw new Error(data.message || 'Gagal masuk, periksa kredensial Anda')
    }

    // Set token to local storage
    localStorage.setItem('admin_token', data.token)
    localStorage.setItem('admin_user', JSON.stringify(data.user))
    
    // Redirect to dashboard
    router.push('/admin')
  } catch (error: any) {
    errorMessage.value = error.message
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    
    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0 pointer-events-none">
      <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-emerald-500/20 blur-3xl rounded-full"></div>
      <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-teal-500/20 blur-3xl rounded-full"></div>
    </div>

    <div class="w-full max-w-sm mx-auto sm:max-w-md relative z-10">
      <div class="flex justify-center mb-6 hover:scale-105 transition-transform duration-300">
        <img src="/images/logo1.png" alt="Logo Admin" class="h-24 sm:h-28 w-auto object-contain drop-shadow-xl" />
      </div>
      <h2 class="text-center text-3xl font-extrabold text-gray-900 tracking-tight">
        Masuk Dasbor Admin
      </h2>
      <p class="mt-2 text-center text-sm text-gray-600">
        Kelola produk, merchant, dan pengaturan portal UMKM Desa
      </p>
    </div>

    <div class="mt-8 w-full max-w-sm mx-auto sm:max-w-md relative z-10">
      <div class="bg-white/80 backdrop-blur-xl py-8 px-5 sm:py-10 sm:px-10 shadow-2xl border border-gray-100 rounded-2xl sm:rounded-3xl">
        <form class="space-y-6" @submit.prevent="handleLogin">
          
          <div v-if="errorMessage" class="bg-red-50/80 backdrop-blur border border-red-200 text-red-600 text-sm p-4 rounded-xl flex items-center gap-3">
             <div class="w-1 h-8 bg-red-500 rounded-full"></div>
             {{ errorMessage }}
          </div>

          <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Email Administratif</label>
            <div class="mt-2 relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Mail class="h-5 w-5 text-gray-400" />
              </div>
              <input v-model="email" id="email" type="email" required placeholder="admin@desa.com" class="appearance-none block w-full pl-12 pr-4 py-3.5 border border-gray-200 rounded-xl bg-white shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all font-medium" />
            </div>
          </div>

          <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
            <div class="mt-2 relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <Lock class="h-5 w-5 text-gray-400" />
              </div>
              <input v-model="password" id="password" :type="showPassword ? 'text' : 'password'" required placeholder="••••••••" class="appearance-none block w-full pl-12 pr-12 py-3.5 border border-gray-200 rounded-xl bg-white shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all font-medium" />
              <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-emerald-600 transition-colors">
                <EyeOff v-if="!showPassword" class="h-5 w-5" />
                <Eye v-else class="h-5 w-5" />
              </button>
            </div>
          </div>

          <div class="pt-2">
            <button type="submit" :disabled="isLoading" class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl text-sm font-bold text-white bg-gray-900 hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 disabled:opacity-70 shadow-lg hover:shadow-emerald-500/30 overflow-hidden">
              <span class="absolute inset-0 w-full h-full -mt-1 rounded-lg opacity-30 bg-gradient-to-b from-transparent via-transparent to-black pointer-events-none"></span>
              <span class="flex items-center gap-2">
                {{ isLoading ? 'Memproses Identitas...' : 'Masuk Sistem' }}
                <ArrowRight v-if="!isLoading" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
              </span>
            </button>
          </div>
          
        </form>

        <div class="mt-8 text-center border-t border-gray-100 pt-6">
          <button @click="router.push('/')" class="text-sm font-medium text-gray-500 hover:text-emerald-600 transition-colors">
            Kembali ke Beranda Desa
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
