<script setup lang="ts">
import { RouterView } from 'vue-router'
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
</script>

<template>
  <div class="min-h-screen bg-white font-sans text-gray-900">
    <Navbar />
    <RouterView />
    <Footer />
  </div>
</template>

<style scoped>
</style>
