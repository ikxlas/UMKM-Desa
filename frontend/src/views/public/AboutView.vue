<script setup lang="ts">
import { ChevronRight, MapPin, Mail, Phone, Clock, Target, Heart, Award } from 'lucide-vue-next'
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-5 md:mb-8">
        <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
        <ChevronRight class="w-4 h-4" />
        <span class="text-emerald-700 font-medium">Tentang Kami</span>
      </nav>

      <!-- Hero Section -->
      <div class="relative w-full h-[200px] md:h-[400px] rounded-2xl md:rounded-3xl overflow-hidden shadow-lg mb-10 md:mb-16">
        <img src="/images/hero.png" alt="Desa Purwoasri" class="absolute inset-0 w-full h-full object-cover object-center" />
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-900/90 to-emerald-900/40"></div>
        <div class="absolute inset-0 flex flex-col justify-center px-5 md:px-16 max-w-3xl">
          <h1 class="text-2xl md:text-5xl font-bold text-white leading-tight mb-2 md:mb-4">
            Tentang Desa Purwoasri
          </h1>
          <p class="text-emerald-50 text-sm md:text-lg leading-relaxed line-clamp-3 md:line-clamp-none">
            Membangun kemandirian ekonomi desa melalui pemberdayaan pengrajin lokal dan produk unggulan yang berkualitas tinggi.
          </p>
        </div>
      </div>

      <!-- Visi & Misi -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 mb-12 md:mb-20">
        <div class="bg-white p-5 md:p-8 rounded-2xl border border-gray-100 shadow-sm text-center">
          <div class="w-12 h-12 md:w-16 md:h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-4 md:mb-6">
            <Target class="w-6 h-6 md:w-8 md:h-8" />
          </div>
          <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Visi Kami</h3>
          <p class="text-gray-600 leading-relaxed text-xs md:text-sm">Menjadi pusat inovasi dan pemberdayaan ekonomi pedesaan terbaik yang mampu membawa produk lokal ke panggung nasional.</p>
        </div>
        <div class="bg-white p-5 md:p-8 rounded-2xl border border-gray-100 shadow-sm text-center">
          <div class="w-12 h-12 md:w-16 md:h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-4 md:mb-6">
            <Heart class="w-6 h-6 md:w-8 md:h-8" />
          </div>
          <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Pemberdayaan Lokal</h3>
          <p class="text-gray-600 leading-relaxed text-xs md:text-sm">Kami berkomitmen untuk mengangkat kesejahteraan masyarakat desa dengan memberikan wadah distribusi yang adil dan transparan.</p>
        </div>
        <div class="bg-white p-5 md:p-8 rounded-2xl border border-gray-100 shadow-sm text-center">
          <div class="w-12 h-12 md:w-16 md:h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mx-auto mb-4 md:mb-6">
            <Award class="w-6 h-6 md:w-8 md:h-8" />
          </div>
          <h3 class="text-lg md:text-xl font-bold text-gray-900 mb-2 md:mb-3">Kualitas Terjamin</h3>
          <p class="text-gray-600 leading-relaxed text-xs md:text-sm">Setiap produk yang dipasarkan telah melewati proses kurasi ketat untuk menjamin kepuasan dan kepercayaan pelanggan kami.</p>
        </div>
      </div>

      <!-- Sejarah & Cerita Kami -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-12 items-center mb-12 md:mb-20 bg-white p-5 md:p-12 rounded-2xl md:rounded-3xl border border-gray-100 shadow-sm">
        <div>
          <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 md:mb-6">Cerita di Balik UMKM Purwoasri</h2>
          <p class="text-gray-600 leading-relaxed mb-3 md:mb-4 text-sm md:text-base">
            Desa Purwoasri telah lama dikenal sebagai salah satu sentra pengrajin kayu dan makanan tradisional terbaik di Jawa Timur. Namun, akses ke pasar yang lebih luas selalu menjadi tantangan utama bagi para pelaku usaha kecil menengah di desa kami.
          </p>
          <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm md:text-base">
            Di awal tahun 2024, perangkat desa bersama kelompok sadar wisata (Pokdarwis) menginisiasi terbentuknya platform digital ini. Tujuannya sederhana: menghubungkan langsung karya tangan masyarakat desa dengan pembeli di seluruh Indonesia tanpa perantara yang memberatkan.
          </p>
          <div class="flex items-center gap-3 md:gap-4">
            <div class="bg-emerald-50 p-3 md:p-4 rounded-xl">
              <span class="block text-xl md:text-2xl font-bold text-emerald-600">50+</span>
              <span class="text-xs md:text-sm text-gray-600 font-medium">UMKM Bergabung</span>
            </div>
            <div class="bg-emerald-50 p-3 md:p-4 rounded-xl">
              <span class="block text-xl md:text-2xl font-bold text-emerald-600">200+</span>
              <span class="text-xs md:text-sm text-gray-600 font-medium">Produk Lokal</span>
            </div>
          </div>
        </div>
        <div class="relative h-[250px] md:h-[400px] rounded-2xl overflow-hidden">
          <img src="/images/banner.png" alt="Pengrajin Purwoasri" class="absolute inset-0 w-full h-full object-cover" />
        </div>
      </div>

      <!-- Kontak Kami -->
      <div>
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-5 md:mb-8 text-center">Lokasi & Kontak Kami</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 max-w-4xl mx-auto mb-8 md:mb-10">
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
              <MapPin class="w-6 h-6" />
            </div>
            <div>
              <h4 class="font-bold text-gray-900">Alamat</h4>
              <p class="text-sm text-gray-600">Desa Purwoasri, Kec. Singosari,Kabupaten Malang,Jawa Timur</p>
            </div>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
              <Mail class="w-6 h-6" />
            </div>
            <div>
              <h4 class="font-bold text-gray-900">Email</h4>
              <p class="text-sm text-gray-600">halo@purwoasri.desa.id</p>
            </div>
          </div>
          <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 shrink-0">
              <Phone class="w-6 h-6" />
            </div>
            <div>
              <h4 class="font-bold text-gray-900">Telepon / WA</h4>
              <p class="text-sm text-gray-600">+62 812 3456 7890</p>
            </div>
          </div>
        </div>

        <!-- Google Maps Embed with Overlay Link -->
        <div class="max-w-4xl mx-auto rounded-2xl md:rounded-3xl overflow-hidden shadow-sm border border-gray-100 bg-white p-1.5 md:p-2 relative group cursor-pointer">
          <a href="https://maps.app.goo.gl/rai11VHQ7LKjB5An9" target="_blank" rel="noopener noreferrer" class="absolute inset-0 z-20 flex items-center justify-center bg-black/0 group-hover:bg-black/30 transition-all duration-300 rounded-3xl m-2">
             <div class="bg-white shadow-xl text-emerald-700 font-bold px-6 py-3.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center gap-2 transform translate-y-4 group-hover:translate-y-0">
                <MapPin class="w-5 h-5" /> Buka Rute di Google Maps
             </div>
          </a>
          <div class="relative z-10 pointer-events-none">
            <iframe 
              src="https://maps.google.com/maps?q=Kantor+Desa+Purwoasri,+Kediri&t=&z=16&ie=UTF8&iwloc=&output=embed" 
              width="100%" 
              height="280" 
              style="border:0; border-radius: 1.25rem;" 
              allowfullscreen="false" 
              loading="lazy" 
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>

    </div>
  </main>
</template>
