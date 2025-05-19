<template>
  <div class="container my-5 ocjenjivanje-box">
    <h2 class="main-title-ocjenjivanje">
      <span class="title-icon"></span> Ocjenjivanje
    </h2>
    <p class="subtitle-ocjenjivanje">Označi kriterije koje projekt zadovoljava. Obvezni kriteriji su označeni zvjezdicom (*).</p>

    <table class="table table-striped table-bordered">
      <thead class="table-light">
        <tr>
          <th style="width: 5%">#</th>
          <th style="width: 60%">Kriterij</th>
          <th style="width: 15%">Obvezan</th>
          <th style="width: 10%">Zadovoljen</th>
          <th style="width: 10%">Bod</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(kriterij, index) in kriteriji" :key="index">
          <td>{{ index + 1 }}</td>
          <td>{{ kriterij.naziv }}</td>
          <td>{{ kriterij.obvezan ? 'Da *' : 'Ne' }}</td>
          <td>
            <input type="checkbox" v-model="kriterij.zadovoljen" />
          </td>
          <td>{{ kriterij.zadovoljen ? kriterij.bod : 0 }}</td>
        </tr>
      </tbody>
    </table>

    <div class="rezultat-box mt-4">
      <p><strong>Ukupno bodova:</strong> {{ ukupniBodovi }} / {{ maksimalniBodovi }}</p>
      <p :class="{ 'text-success': sviObvezniZadovoljeni, 'text-danger': !sviObvezniZadovoljeni }">
        <strong>Status:</strong> {{ sviObvezniZadovoljeni ? 'Svi obvezni kriteriji su zadovoljeni ✅' : 'Nisu zadovoljeni svi obvezni kriteriji ❌' }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, computed } from 'vue'

const kriteriji = reactive([
  { naziv: 'Projekt ima korisničko sučelje', bod: 10, obvezan: true, zadovoljen: false },
  { naziv: 'Funkcionalnost je ispravna', bod: 15, obvezan: true, zadovoljen: false },
  { naziv: 'Dizajn i UX su kvalitetni', bod: 10, obvezan: false, zadovoljen: false },
  { naziv: 'Projekt koristi baze podataka', bod: 15, obvezan: true, zadovoljen: false },
  { naziv: 'Kod je čitljiv i dobro organiziran', bod: 10, obvezan: false, zadovoljen: false },
  { naziv: 'Dokumentacija je potpuna', bod: 5, obvezan: false, zadovoljen: false }
])

const ukupniBodovi = computed(() =>
  kriteriji.reduce((zbroj, k) => zbroj + (k.zadovoljen ? k.bod : 0), 0)
)

const maksimalniBodovi = computed(() =>
  kriteriji.reduce((zbroj, k) => zbroj + k.bod, 0)
)

const sviObvezniZadovoljeni = computed(() =>
  kriteriji.every(k => !k.obvezan || k.zadovoljen)
)
</script>

<style scoped>
/* Uključen tvoj postojeći stil */
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.container {
  font-family: 'Poppins', sans-serif;
  color: #2c3e50;
}

.ocjenjivanje-box {
  padding: 2rem 2.5rem;
  border: 2px solid #2a5298;
  border-radius: 12px;
  background: #f0f4fb;
  box-shadow: 0 8px 20px rgba(42, 82, 152, 0.2);
}

.main-title-ocjenjivanje {
  font-weight: 700;
  font-size: 2.4rem;
  background: linear-gradient(90deg, #1e3c72, #2a5298, #667292);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 0.5rem;
  letter-spacing: 1.2px;
  display: flex;
  align-items: center;
  gap: 12px;
  user-select: none;
}

.subtitle-ocjenjivanje {
  font-size: 1.15rem;
  color: #4a5a6a;
  font-weight: 600;
  margin-top: 0;
  margin-bottom: 2.5rem;
}

.rezultat-box {
  font-size: 1.1rem;
  background-color: #ffffff;
  padding: 1rem 1.5rem;
  border-radius: 10px;
  border: 1px solid #ccc;
}

.text-success {
  color: #2a7d2e;
}

.text-danger {
  color: #a02a2a;
}
</style>
