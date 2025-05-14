<script setup>
import HelloWorld from './components/HelloWorld.vue'
</script>

<template>
  <div class="container mt-5">
    <h1 class="text-light">Bootswatch tema radi!</h1>
    <p class="text-secondary">Ovo je test Vue + Bootswatch projekta bez Vuetifyja.</p>
    <button class="btn btn-primary">Klikni me</button>
  </div>
</template>

<script setup>
</script>

<style>
/* Možeš dodati dodatne stilove ako želiš */
</style>


<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
