<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { RouterLink } from 'vue-router'
import { ChevronRight, ChevronLeft, Calendar, MapPin } from 'lucide-vue-next'

// Original Slides List
const originalSlides = [
  '/images/hero.png',
  '/images/emping_main.png',
  '/images/kripik_main.png',
  '/images/merchant.png'
]

// To handle seamless infinite horizontal scrolling, we clone boundary slides:
// [last_slide, slide_1, slide_2, slide_3, slide_4, slide_1]
const slides = [
  originalSlides[originalSlides.length - 1],
  ...originalSlides,
  originalSlides[0]
]

// activeSlide starts at 1, which represents originalSlides[0] (slide_1)
const activeSlide = ref(1)
const isTransitioning = ref(true)
const isMoving = ref(false) // Throttle double clicks
let slideInterval: any = null

const startSlideTimer = () => {
  stopSlideTimer()
  slideInterval = setInterval(() => {
    nextSlide()
  }, 5000) // Auto-slide every 5 seconds
}

const stopSlideTimer = () => {
  if (slideInterval) {
    clearInterval(slideInterval)
  }
}

const nextSlide = () => {
  if (isMoving.value) return
  isMoving.value = true
  activeSlide.value++
  startSlideTimer()
}

const prevSlide = () => {
  if (isMoving.value) return
  isMoving.value = true
  activeSlide.value--
  startSlideTimer()
}

const goToSlide = (idx: number) => {
  if (isMoving.value) return
  isMoving.value = true
  activeSlide.value = idx + 1
  startSlideTimer()
}

// Compute the current active dot based on extended index
const currentOriginalIndex = computed(() => {
  if (activeSlide.value === slides.length - 1) return 0
  if (activeSlide.value === 0) return originalSlides.length - 1
  return activeSlide.value - 1
})

const handleTransitionEnd = () => {
  isMoving.value = false // release double click lock
  
  // If we transitioned to the cloned first slide at the end (index 5)
  if (activeSlide.value === slides.length - 1) {
    isTransitioning.value = false
    activeSlide.value = 1
    // Force a small reflow to make browser execute transition state changes separately
    setTimeout(() => {
      isTransitioning.value = true
    }, 50)
  }
  // If we transitioned to the cloned last slide at the start (index 0)
  else if (activeSlide.value === 0) {
    isTransitioning.value = false
    activeSlide.value = slides.length - 2
    setTimeout(() => {
      isTransitioning.value = true
    }, 50)
  }
}

// Countdown state
const days = ref('00')
const hours = ref('00')
const minutes = ref('00')
const seconds = ref('00')
const isEventRunning = ref(false)

let countdownInterval: any = null

const calculateCountdown = () => {
  const now = new Date()
  
  const currentDay = now.getDay() // 0 = Sunday, 1 = Monday, etc.
  const currentHours = now.getHours()
  const currentMinutes = now.getMinutes()
  
  // Check if it is Sunday between 06:00 and 10:00
  const isSunday = currentDay === 0
  const isBetweenEventHours = (currentHours > 6 || (currentHours === 6 && currentMinutes >= 0)) && (currentHours < 10)
  const isCurrentlyEventActive = isSunday && isBetweenEventHours
  
  if (isCurrentlyEventActive) {
    days.value = '00'
    hours.value = '00'
    minutes.value = '00'
    seconds.value = '00'
    isEventRunning.value = true
    return
  }
  
  isEventRunning.value = false
  
  // Find the next upcoming Sunday at 06:00:00 AM
  const nextSunday = new Date()
  let daysToAdd = 0
  
  if (isSunday) {
    if (currentHours < 6) {
      // It is Sunday before 06:00 AM, target Sunday 06:00 AM today
      daysToAdd = 0
    } else {
      // It is Sunday after 10:00 AM, target next Sunday
      daysToAdd = 7
    }
  } else {
    // Monday to Saturday
    daysToAdd = 7 - currentDay
  }
  
  nextSunday.setDate(now.getDate() + daysToAdd)
  nextSunday.setHours(6, 0, 0, 0)
  
  const diffMs = nextSunday.getTime() - now.getTime()
  
  if (diffMs <= 0) {
    days.value = '00'
    hours.value = '00'
    minutes.value = '00'
    seconds.value = '00'
    return
  }
  
  const totalSeconds = Math.floor(diffMs / 1000)
  const d = Math.floor(totalSeconds / (3600 * 24))
  const h = Math.floor((totalSeconds % (3600 * 24)) / 3600)
  const m = Math.floor((totalSeconds % 3600) / 60)
  const s = totalSeconds % 60
  
  days.value = d.toString().padStart(2, '0')
  hours.value = h.toString().padStart(2, '0')
  minutes.value = m.toString().padStart(2, '0')
  seconds.value = s.toString().padStart(2, '0')
}

onMounted(() => {
  startSlideTimer()
  calculateCountdown()
  countdownInterval = setInterval(calculateCountdown, 1000)
})

onUnmounted(() => {
  stopSlideTimer()
  if (countdownInterval) {
    clearInterval(countdownInterval)
  }
})
</script>

<template>
  <main class="min-h-screen bg-gray-50 pb-20 pt-6">
    <div class="max-w-7xl mx-auto px-4 md:px-12">
      <!-- Breadcrumbs -->
      <nav class="flex items-center gap-2 text-xs md:text-sm text-gray-500 mb-6 md:mb-8">
        <RouterLink to="/" class="hover:text-emerald-600 transition-colors">Home</RouterLink>
        <ChevronRight class="w-4 h-4" />
        <span class="text-emerald-700 font-medium">Event</span>
      </nav>

      <!-- Carousel Banner Section (Automatic & Manual Infinite Slide, No Text Overlay) -->
      <div class="relative w-full h-[250px] md:h-[400px] rounded-3xl overflow-hidden shadow-lg mb-10 md:mb-12 group">
        <!-- Slider Track with Dynamic Transition Class and TransitionEnd event listener -->
        <div 
          class="flex h-full" 
          :class="{ 'transition-transform duration-700 ease-out': isTransitioning }"
          :style="{ transform: `translateX(-${activeSlide * 100}%)` }"
          @transitionend="handleTransitionEnd"
        >
          <div v-for="(img, idx) in slides" :key="idx" class="w-full h-full flex-shrink-0 relative">
            <img :src="img" class="w-full h-full object-cover object-center" />
            <!-- Soft overlay gradient -->
            <div class="absolute inset-0 bg-black/5"></div>
          </div>
        </div>

        <!-- Navigation Arrows (Pristine Hover Reveal) -->
        <button 
          @click="prevSlide" 
          class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/25 hover:bg-white/40 text-white flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"
        >
          <ChevronLeft class="w-6 h-6" />
        </button>
        <button 
          @click="nextSlide" 
          class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/25 hover:bg-white/40 text-white flex items-center justify-center backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10"
        >
          <ChevronRight class="w-6 h-6" />
        </button>

        <!-- Dot Indicators matching originalSlides count -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-10">
          <button 
            v-for="(s, idx) in originalSlides" 
            :key="idx" 
            @click="goToSlide(idx)" 
            class="w-2.5 h-2.5 rounded-full transition-all duration-300"
            :class="currentOriginalIndex === idx ? 'bg-emerald-500 w-6' : 'bg-white/55 hover:bg-white'"
          ></button>
        </div>
      </div>

      <!-- Tentang Program Section -->
      <section class="mb-10 md:mb-14">
        <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 mb-4 tracking-tight">Tentang Program Kami</h2>
        <p class="text-gray-600 text-sm md:text-base leading-relaxed max-w-4xl">
          Temukan berbagai inisiawat pemberdayaan komunitas, lokakarya keterampilan, dan pameran produk lokal yang dirancang untuk mengakselerasi kemakmuran digital para pelaku UMKM di desa kita. Kami berkomitmen untuk membangun ekosistem yang mendukung pertumbuhan ekonomi lokal melalui teknologi dan kolaborasi.
        </p>
      </section>

      <!-- Map & Countdown Section (using custom scoped styles for guaranteed layout width) -->
      <section class="event-section bg-[#F0F6F4] rounded-3xl p-5 md:p-8 border border-emerald-100/50 shadow-sm mb-10 md:mb-16">
        <!-- Google Maps Embed with Overlay Click-to-Route Link -->
        <div class="map-container rounded-2xl overflow-hidden shadow-sm border border-emerald-200/30 relative group/map">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3954.1950481062095!2d112.09117621008688!3d-7.662660192323719!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7845f27cf5c84d%3A0xb35a0f274cbcc63!2sAlun-Alun%20Kecamatan%20Purwoasri!5e0!3m2!1sid!2sid!4v1719730000000!5m2!1sid!2sid"
            class="w-full h-full border-0"
            allowfullscreen="true"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
          <!-- Clickable overlay to directly view directions/route in Google Maps -->
          <a 
            href="https://www.google.com/maps/dir/?api=1&destination=Alun-Alun+Kecamatan+Purwoasri" 
            target="_blank" 
            class="absolute inset-0 bg-black/0 hover:bg-black/10 transition-colors flex items-center justify-center cursor-pointer"
            title="Klik untuk membuka rute di Google Maps"
          >
            <!-- Badge / Floating Indicator -->
            <div class="absolute bottom-4 right-4 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl shadow-lg transition-all transform translate-y-2 opacity-0 group-hover/map:translate-y-0 group-hover/map:opacity-100 flex items-center gap-1.5">
              <MapPin class="w-3.5 h-3.5" />
              <span>Buka Rute di Google Maps</span>
            </div>
          </a>
        </div>

        <!-- Event Details & Countdown -->
        <div class="details-container py-2">
          <!-- Waktu & Lokasi -->
          <div>
            <h4 class="text-xs font-bold text-emerald-800 tracking-wider uppercase mb-4">Waktu & Lokasi</h4>
            <div class="space-y-4 mb-8">
              <div class="flex items-center gap-3 text-gray-700">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0">
                  <Calendar class="w-4 h-4" />
                </div>
                <span class="text-sm font-semibold">Setiap Minggu, 06.00 - 10.00 WIB</span>
              </div>
              <div class="flex items-center gap-3 text-gray-700">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700 shrink-0">
                  <MapPin class="w-4 h-4" />
                </div>
                <span class="text-sm font-semibold">Alun-alun Desa Purwoasri</span>
              </div>
            </div>
          </div>

          <div class="border-t border-emerald-200/50 pt-6">
            <div class="flex items-center gap-2 mb-4">
              <h4 class="text-[11px] font-bold text-emerald-800 tracking-wider uppercase">
                {{ isEventRunning ? 'Acara Sedang Berlangsung' : 'Menuju Acara Berikutnya' }}
              </h4>
              <div v-if="isEventRunning" class="flex items-center gap-1 bg-emerald-600 text-white text-[9px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                <span>LIVE</span>
              </div>
            </div>
            
            <!-- Countdown Timer Blocks (Styled via bulletproof Scoped CSS) -->
            <div class="flex items-center gap-3 sm:gap-4">
              <!-- Days -->
              <div class="flex flex-col items-center">
                <div class="countdown-box shadow-md">
                  {{ days }}
                </div>
                <span class="text-[10px] md:text-xs text-gray-500 font-bold mt-1.5 uppercase">Hari</span>
              </div>
              <!-- Hours -->
              <div class="flex flex-col items-center">
                <div class="countdown-box shadow-md">
                  {{ hours }}
                </div>
                <span class="text-[10px] md:text-xs text-gray-500 font-bold mt-1.5 uppercase">Jam</span>
              </div>
              <!-- Minutes -->
              <div class="flex flex-col items-center">
                <div class="countdown-box shadow-md">
                  {{ minutes }}
                </div>
                <span class="text-[10px] md:text-xs text-gray-500 font-bold mt-1.5 uppercase">Menit</span>
              </div>
              <!-- Seconds -->
              <div class="flex flex-col items-center">
                <div class="countdown-box seconds-box shadow-md">
                  {{ seconds }}
                </div>
                <span class="text-[10px] md:text-xs text-gray-500 font-bold mt-1.5 uppercase">Detik</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Social Media Section -->
      <section class="border-t border-gray-200 pt-8">
        <h4 class="text-xs font-bold text-gray-400 tracking-wider uppercase mb-5">Ikuti Kami Di Media Sosial</h4>
        <div class="flex flex-wrap gap-4">
          <!-- Instagram Brand Button -->
          <a 
            href="https://instagram.com" 
            target="_blank" 
            class="flex items-center gap-3 py-3 px-6 rounded-xl text-white font-bold shadow-md hover:shadow-lg transition-all hover:scale-105 duration-300"
            style="background: linear-gradient(45deg, #f9ce34, #ee2a7b, #6228d7);"
          >
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051C.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
            </svg>
            <span>Instagram</span>
          </a>

          <!-- TikTok Brand Button -->
          <a 
            href="https://tiktok.com" 
            target="_blank" 
            class="flex items-center gap-3 py-3 px-6 rounded-xl text-white font-bold shadow-md hover:shadow-lg hover:shadow-cyan-500/20 hover:scale-105 border border-gray-800 transition-all duration-300"
            style="background: #010101;"
          >
            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-2.3.08-4.73-.83-6.23-2.61-1.66-1.92-2.16-4.72-1.31-7.13.78-2.26 2.72-4.03 5.09-4.5 1.05-.22 2.15-.17 3.2.1v4.12c-.75-.24-1.57-.23-2.31.02-1.12.35-2.02 1.3-2.32 2.4-.41 1.4-.01 3 .99 4.02 1.01 1.05 2.6 1.45 4.02 1.04 1.25-.33 2.22-1.39 2.5-2.65.17-.67.15-1.37.15-2.06.01-5.11-.01-10.22.02-15.32z"/>
            </svg>
            <span>TikTok</span>
          </a>
        </div>
      </section>
    </div>
  </main>
</template>

<style scoped>
.event-section {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}
.map-container {
  width: 100%;
  height: 300px;
  flex-shrink: 0;
}
.details-container {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.countdown-box {
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 0.75rem;
  background-color: #065f46; /* bg-emerald-800 */
  color: #ffffff !important;   /* force white text */
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  font-weight: 900;
}
.seconds-box {
  background-color: #047857; /* bg-emerald-700 */
}

@media (min-width: 768px) {
  .countdown-box {
    width: 4rem;
    height: 4rem;
    font-size: 1.5rem;
  }
}

@media (min-width: 1024px) {
  .event-section {
    flex-direction: row;
    gap: 2rem;
  }
  .map-container {
    width: 58%;
    height: 350px;
  }
  .details-container {
    flex: 1;
  }
}
</style>
