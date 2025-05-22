<template>
  <div class="container my-5 ocjenjivanje-box">
    <h2 class="main-title-ocjenjivanje">
      <span class="title-icon"></span> Ocjenjivanje
    </h2>

    <!-- Odabir projekta -->
    <div class="form-group mb-4">
      <label for="projekat">Odaberi projekat:</label>
      <select v-model="odabraniProjektId" @change="postaviOdabraniProjekt" class="form-control" id="projekat">
        <option disabled value="">-- Odaberi --</option>
        <option v-for="projekt in projekti" :key="projekt.id" :value="projekt.id">
          {{ projekt.ime }} {{ projekt.prezime }} - {{ projekt.tema_naziv }}
        </option>
      </select>
    </div>

    <!-- Forma za ocjenjivanje -->
    <div v-if="odabraniProjekt">
      <p class="subtitle-ocjenjivanje">
        Označi kriterije koje projekt zadovoljava. Obvezni kriteriji su označeni zvjezdicom (*).
      </p>

      <table class="table table-striped table-bordered">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Kriterij</th>
            <th>Obvezan</th>
            <th>Zadovoljen</th>
            <th>Bod</th>
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
          <strong>Status:</strong>
          {{ sviObvezniZadovoljeni ? 'Svi obvezni kriteriji su zadovoljeni ✅' : 'Ne zadovoljava kriterij ❌' }}
        </p>

        <!-- Prikaz nezadovoljenih obveznih kriterija -->
        <div v-if="nezadovoljeniObvezniKriteriji.length" class="alert alert-warning mt-2">
          <strong>❌ Ne zadovoljava sljedeće obvezne kriterije:</strong>
          <ul class="mb-0">
            <li v-for="(kriterij, index) in nezadovoljeniObvezniKriteriji" :key="index">
              {{ kriterij.naziv }}
            </li>
          </ul>
        </div>

        <button class="btn btn-primary mt-3" @click="spremiOcjenu">Spremi ocjenu</button>
      </div>
    </div>

    <!-- Debug: Prikaz broja učenika -->
    <div class="mt-3">
      <p><strong>Broj učenika u sistemu:</strong> {{ sviUcenici.length }}</p>
    </div>

    <!-- Tabela sa učenicima i ocjenama -->
    <div v-if="sviUcenici.length" class="mt-5">
      <h3>Učenici i njihove ocjene</h3>
      <table class="table table-bordered table-hover">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Ime i prezime</th>
            <th>Projekt</th>
            <th>Ukupno bodova</th>
            <th>Ocjena</th>
            <th>Nezadovoljeni obvezni kriteriji</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(ucenik, index) in sviUcenici" :key="ucenik.id">
            <td>{{ index + 1 }}</td>
            <td>{{ ucenik.ime }} {{ ucenik.prezime }}</td>
            <td>{{ ucenik.tema_naziv }}</td>
            <td>{{ ucenik.ukupno_bodova }}</td>
            <td>{{ izracunajOcjenu(ucenik.ukupno_bodova, ucenik.obvezni_zadovoljeni) }}</td>
            <td>
              <ul v-if="ucenik.nezadovoljeni_kriteriji && ucenik.nezadovoljeni_kriteriji.length">
                <li v-for="(kriterij, idx) in ucenik.nezadovoljeni_kriteriji" :key="idx">{{ kriterij }}</li>
              </ul>
              <span v-else>-</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import axios from 'axios'

const projekti = ref([])
const odabraniProjektId = ref('')
const odabraniProjekt = ref(null)

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

const nezadovoljeniObvezniKriteriji = computed(() =>
  kriteriji.filter(k => k.obvezan && !k.zadovoljen)
)

const sviUcenici = ref([])

const dohvatiProjekte = async () => {
  try {
    const res = await axios.get("http://localhost/my_project/dohvati-projekte.php")
    if (res.data.success) {
      projekti.value = res.data.projekti
    }
  } catch (err) {
    console.error("Greška pri dohvatu projekata:", err)
  }
}

const dohvatiUcenikeSaOcjenama = async () => {
  try {
    const res = await axios.get("http://localhost/my_project/dohvati-ucenike-sa-ocjenama.php")
    if (res.data.success) {
      sviUcenici.value = res.data.ucenici.map(ucenik => ({
        ...ucenik,
        nezadovoljeni_kriteriji:
          ucenik.nezadovoljni_obvezni && ucenik.nezadovoljni_obvezni !== "-"
            ? [ucenik.nezadovoljni_obvezni]
            : [],
        obvezni_zadovoljeni: ucenik.nezadovoljni_obvezni === "-" // ključna promjena
      }))
    }
  } catch (err) {
    console.error("Greška pri dohvatu učenika:", err)
  }
}

const postaviOdabraniProjekt = () => {
  odabraniProjekt.value = projekti.value.find(p => p.id === odabraniProjektId.value)
  kriteriji.forEach(k => (k.zadovoljen = false))
}

const spremiOcjenu = async () => {
  try {
    if (!odabraniProjekt.value) return alert("Odaberi projekt prije spremanja!")

    const podaci = {
      projekt_id: odabraniProjekt.value.id,
      kriteriji: kriteriji.map(k => ({
        naziv: k.naziv,
        obvezan: k.obvezan,
        zadovoljen: k.zadovoljen,
        bod: k.zadovoljen ? k.bod : 0
      })),
      ukupno_bodova: ukupniBodovi.value,
      obvezni_zadovoljeni: sviObvezniZadovoljeni.value,
      nezadovoljeni_kriteriji: nezadovoljeniObvezniKriteriji.value.map(k => k.naziv)
    }

    const res = await axios.post("http://localhost/my_project/spremi-ocjenu.php", podaci)
    if (res.data.success) {
      alert("Ocjena je spremljena!")
      await dohvatiUcenikeSaOcjenama() // osvježi tablicu učenika
    } else {
      alert("Greška prilikom spremanja ocjene!")
    }
  } catch (err) {
    console.error("Greška pri spremanju ocjene:", err)
  }
}

const izracunajOcjenu = (bodovi, obvezniZadovoljeni) => {
  if (!obvezniZadovoljeni) return 1
  if (bodovi >= 65) return 5
  if (bodovi >= 60) return 4
  if (bodovi >= 50) return 3
  if (bodovi >= 40) return 2
  return 1
}

onMounted(() => {
  dohvatiProjekte()
  dohvatiUcenikeSaOcjenama()
})
</script>

<style scoped>
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
  margin-bottom: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
}

.subtitle-ocjenjivanje {
  font-weight: 600;
  font-size: 1.1rem;
  color: #1f2937;
  margin-bottom: 1rem;
}

.rezultat-box p {
  font-weight: 600;
  font-size: 1.15rem;
}

.table {
  margin-top: 1rem;
}

.alert {
  font-size: 0.95rem;
}

.btn-primary {
  background-color: #2a5298;
  border-color: #2a5298;
}

.btn-primary:hover {
  background-color: #1e3c72;
  border-color: #1e3c72;
}
</style>
