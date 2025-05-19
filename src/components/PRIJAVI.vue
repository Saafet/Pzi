<template>
  <div class="container my-5">

    <!-- 📋 Google tablica -->
    <div class="mb-5">
      <h2 class="mb-3">📋 Tablica prijava i napretka</h2>
      <p>U ovu tablicu studenti su dužni upisati imena parova pored unaprijed određenih tema. 
        <br>
        Svakoga tjedna dužni ste označiti vaš dolazak na predavanje sa "+" (dolasci na predavanja su OBAVEZNI)
        <br>
        Dužni ste svakoga tjedna uraditi zadaću te u tablicu staviti link na istu.
      </p>
      <div class="ratio ratio-16x9">
        <iframe
          src="https://docs.google.com/spreadsheets/d/1G0h6horQ5anOpgiLRdZrHxnfmoUOaJs9jIWuO5xyfrw/edit?gid=0#gid=0"
          width="100%" height="500" frameborder="0" allowfullscreen
        ></iframe>
      </div>
    </div>

    <div class="row">
      <!-- Lijeva polovica: Forma za prijavu -->
      <div class="col-md-6">
        <h2>Prijava projekta</h2>
        <form @submit.prevent="dodajProjekt">
          <div class="mb-3">
            <label for="name" class="form-label">Ime</label>
            <input v-model="forma.ime" type="text" class="form-control" id="name" required />
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Prezime</label>
            <input v-model="forma.prezime" type="text" class="form-control" id="surname" required />
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input v-model="forma.email" type="email" class="form-control" id="email" required />
          </div>

          <div class="mb-3">
            <label for="date" class="form-label">Datum</label>
            <input v-model="forma.datum" type="date" class="form-control" id="date" required />
          </div>

          <div class="mb-3">
            <label for="topic" class="form-label">Odaberite temu</label>
            <select v-model="forma.tema" class="form-select" id="topic" required>
              <option value="">Odaberite temu</option>
              <option v-for="tema in sveTeme" :key="tema" :value="tema">{{ tema }}</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Prijavi projekt</button>
        </form>
      </div>

      <!-- Desna polovica: Teme u karticama -->
      <div class="col-md-6">
        <h2 class="mb-3">Teme dostupne za odabir</h2>
        <div class="row row-cols-1 row-cols-md-2 g-4">
          <div class="col" v-for="(tema, index) in sveTeme" :key="index">
            <div
              class="card h-100 shadow animate__animated animate__fadeInUp"
              :class="{ 'bg-secondary text-white': temaZauzeta(tema) }"
            >
              <div class="card-body">
                <h5 class="card-title">{{ tema }}</h5>
                <p v-if="temaZauzeta(tema)" class="text-warning">
                  <i class="bi bi-exclamation-triangle"></i> Tema zauzeta
                </p>
                <p v-else class="text-success">
                  <i class="bi bi-check-circle"></i> Tema dostupna
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'PRIJAVI',
  data() {
    return {
      forma: {
        ime: '',
        prezime: '',
        email: '',
        datum: '',
        tema: ''
      },
      projekti: [],
      sveTeme: [
        'Evidencija dolazaka QR kodom preko eduID',
        'Društvena mreža za PUB kvizove',
        'AI pomocnik za ucenje',
        'Platforma za studentske STARTUP projekte',
        'Aplikacija za digitalni potpis dokumenata',
        'AI planer putovanja',
        'QR kod foto memories',
        'Aplikacija za personalizirane treninge',
        'Aplikacija za slanje poruka na WhatsApp (chatbot)',
        'Platforma za prijavu, praćenje i ocjenjivanje PZI projekata',
        'Aplikacija za praćenje budžeta i AI savjetnik',
        'Aplikacija za turističku ponudu Hercegovine - Chatbot'
      ]
    }
  },
  methods: {
    dodajProjekt() {
      this.projekti.push({ ...this.forma })
      this.snimiLocalStorage()
      this.forma = { ime: '', prezime: '', email: '', datum: '', tema: '' }
    },
    temaZauzeta(tema) {
      const broj = this.projekti.filter(p => p.tema === tema).length
      return broj >= 2 // kolega će backend povezati kasnije
    },
    snimiLocalStorage() {
      localStorage.setItem('projekti', JSON.stringify(this.projekti))
    },
    ucitajLocalStorage() {
      const podaci = localStorage.getItem('projekti')
      if (podaci) {
        this.projekti = JSON.parse(podaci)
      }
    }
  },
  mounted() {
    this.ucitajLocalStorage()
  }
}
</script>

<style scoped>
form .form-control,
form .form-select {
  font-size: 1.3rem;
  height: 45px;
  margin-bottom: 2rem;
}

button.btn {
  font-size: 1.2rem;
  padding: 10px 25px;
}

.container {
  max-width: 1200px;
}
form .form-label {
  font-weight: bold;
}
.card {
  border-radius: 10px;
  transition: transform 0.3s ease;
}
.card:hover {
  transform: scale(1.02);
}
</style>
