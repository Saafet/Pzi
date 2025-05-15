<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">TaskNest</a>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="w-100 d-flex justify-content-between align-items-center">
            <!-- Navigacija u sredini -->
            <ul class="navbar-nav mx-auto">
              <li class="nav-item"><a class="nav-link" href="#prijavi">PRIJAVI PROJEKT</a></li>
              <li class="nav-item"><a class="nav-link" href="#pratite">PRATITE RAD</a></li>
              <li class="nav-item"><a class="nav-link" href="#ocjenjivanje">OCJENJIVANJE</a></li>
              <li class="nav-item"><a class="nav-link" href="#edu">UČI WEB</a></li>
              <li class="nav-item"><a class="nav-link" href="#kontakt">KONTAKT</a></li>
            </ul>

            <!-- Login desno -->
            <ul class="navbar-nav">
              <li class="nav-item">
                <router-link class="nav-link" to="/login">Login</router-link>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero sekcija -->
    <section class="hero d-flex align-items-start justify-content-start">
      <div class="content text-white">
        <h1 class="display-4 fw-bold">
          PLATFORMA ZA PRIJAVU, PRAĆENJE I OCJENJIVANJE PZI ZADATAKA
        </h1>
      </div>
      <div class="images-container">
        <div class="image-right">
          <img src="/fotografije/pocetna2.jpeg" alt="Početna" class="img-fluid" />
        </div>
      </div>
    </section>

    <!-- Uvodni tekst -->
    <section class="container mt-5">
      <p class="lead">
        Razvoj web aplikacija je ključna disciplina u modernom digitalnom svijetu. Naučite osnove i napredne tehnike razvoja kroz ovu platformu.
      </p>
      <img src="/fotografije/tehnologije.png" alt="Tehnologije" class="img-fluid" />
    </section>

    <!-- Edukacija -->
    <section id="edu" class="container mt-5">
      <h2 class="text-center mb-4">Naučite Web Development</h2>

      <div class="row text-center">
        <div v-for="tech in tehnologije" :key="tech.naziv" class="col-6 col-md-3 mb-4">
          <a
            :href="tech.link"
            target="_blank"
            rel="noopener"
            class="tech-card p-3 border rounded d-block text-decoration-none text-dark"
            :title="'Saznaj više o ' + tech.naziv"
          >
            <i :class="['bi', tech.ikona]" style="font-size: 3rem; color: #007bff;"></i>
            <h5 class="mt-2">{{ tech.naziv }}</h5>
            <p class="small">{{ tech.opis }}</p>
          </a>
        </div>
      </div>

      <div class="accordion" id="tutorialAccordion">
        <div class="accordion-item" v-for="(kat, idx) in tutorijali" :key="idx">
          <h2 class="accordion-header" :id="'heading' + idx">
            <button
              class="accordion-button collapsed"
              type="button"
              data-bs-toggle="collapse"
              :data-bs-target="'#collapse' + idx"
              aria-expanded="false"
              :aria-controls="'collapse' + idx"
            >
              {{ kat.naslov }}
            </button>
          </h2>
          <div
            :id="'collapse' + idx"
            class="accordion-collapse collapse"
            :aria-labelledby="'heading' + idx"
            data-bs-parent="#tutorialAccordion"
          >
            <div class="accordion-body">
              <ul>
                <li v-for="link in kat.linkovi" :key="link.url">
                  <a :href="link.url" target="_blank" rel="noopener">{{ link.naziv }}</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-5">
        <h4 class="text-center">Pogledajte: Kako izraditi svoju prvu Vue aplikaciju</h4>
        <div class="ratio ratio-16x9">
          <iframe
            src="https://www.youtube.com/embed/ZWrM-eDxTas"
            title="Vue Uvod"
            allowfullscreen
          ></iframe>
        </div>
      </div>

      <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
        <a class="btn btn-primary" href="https://eucenje.sum.ba" target="_blank">e-Kolegij</a>
        <a class="btn btn-success" href="/resursi/scripta.pdf" target="_blank">PDF Scripta</a>
        <a class="btn btn-dark" href="https://github.com/ime-repoa" target="_blank">GitHub Repo</a>
        <a class="btn btn-secondary" href="/resursi/dokumentacija.pdf" target="_blank">Dokumentacija</a>
      </div>
    </section>

    <!-- Komponente -->
    <section id="prijavi" class="container mt-5">
      <PRIJAVI />
    </section>
    <section id="pratite" class="container mt-5">
      <PRATITE />
    </section>
    <section id="ocjenjivanje" class="container mt-5">
      <OCJENJIVANJE />
    </section>

    <!-- Statistika -->
    <section class="container text-center mt-5">
      <h2>Statistika</h2>
      <div class="row mt-4">
        <div class="col-md-4">
          <h3>37+</h3>
          <p>Prijavljenih projekata</p>
        </div>
        <div class="col-md-4">
          <h3>21</h3>
          <p>Aktivnih korisnika</p>
        </div>
        <div class="col-md-4">
          <h3>9</h3>
          <p>Mentora</p>
        </div>
      </div>
    </section>

    <!-- Kontakt -->
    <section id="kontakt" class="container mt-5 mb-5">
      <h2 class="text-center mb-4">Kontaktirajte profesora</h2>
      <form @submit.prevent="posaljiKontakt" novalidate>
        <div class="mb-3">
          <label for="ime" class="form-label">Ime i prezime</label>
          <input
            id="ime"
            type="text"
            v-model.trim="kontakt.ime"
            class="form-control"
            :class="{ 'is-invalid': validacija && !kontakt.ime }"
            required
          />
          <div class="invalid-feedback">Molimo unesite ime.</div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            id="email"
            type="email"
            v-model.trim="kontakt.email"
            class="form-control"
            :class="{ 'is-invalid': validacija && !validEmail(kontakt.email) }"
            required
          />
          <div class="invalid-feedback">Unesite ispravan email.</div>
        </div>

        <div class="mb-3">
          <label for="poruka" class="form-label">Poruka</label>
          <textarea
            id="poruka"
            rows="4"
            v-model.trim="kontakt.poruka"
            class="form-control"
            :class="{ 'is-invalid': validacija && !kontakt.poruka }"
            required
          ></textarea>
          <div class="invalid-feedback">Poruka ne može biti prazna.</div>
        </div>

        <button type="submit" class="btn btn-primary">Pošalji</button>
      </form>
      <div v-if="feedbackPoruka" class="alert alert-success mt-3" role="alert">
        {{ feedbackPoruka }}
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
      <div class="container">
        <p class="mb-1">TaskNest | PZI projekt postavljen na studentski server</p>
        <small>Izradili: Matea Tufekčić &amp; Safet Srna, 2025.</small>
      </div>
    </footer>

    <!-- Scroll to top button -->
    <button
      @click="scrollToTop"
      class="btn btn-primary position-fixed bottom-0 end-0 m-4"
      aria-label="Scroll to top"
    >
      ⬆️
    </button>
  </div>
</template>

<script>
import PRIJAVI from './components/PRIJAVI.vue'
import PRATITE from './components/PRATITE.vue'
import OCJENJIVANJE from './components/OCJENJIVANJE.vue'

export default {
  name: 'App',
  components: { PRIJAVI, PRATITE, OCJENJIVANJE },

  data() {
  return {
    tehnologije: [
      {
        naziv: 'HTML',
        ikona: 'bi-filetype-html',
        opis: 'Osnova svakog web sadržaja',
        link: 'https://developer.mozilla.org/en-US/docs/Web/HTML',
      },
      {
        naziv: 'CSS',
        ikona: 'bi-filetype-css',
        opis: 'Stilizacija i izgled',
        link: 'https://developer.mozilla.org/en-US/docs/Web/CSS',
      },
      {
        naziv: 'JavaScript',
        ikona: 'bi-filetype-js',
        opis: 'Interaktivnost web stranica',
        link: 'https://developer.mozilla.org/en-US/docs/Web/JavaScript',
      },
      {
        naziv: 'Vue.js',
        ikona: 'bi-vue',
        opis: 'Napredni JavaScript framework',
        link: 'https://vuejs.org/',
      },
      {
        naziv: 'Laravel',
        ikona: 'bi-layers-fill',
        opis: 'PHP framework za backend razvoj',
        link: 'https://laravel.com/',
      },
      {
        naziv: 'Bootstrap',
        ikona: 'bi-bootstrap',
        opis: 'CSS framework za responzivni dizajn',
        link: 'https://getbootstrap.com/',
      },
      {
        naziv: 'TypeScript',
        ikona: 'bi-filetype-tsx',
        opis: 'Typed superset za JavaScript',
        link: 'https://www.typescriptlang.org/',
      },
      {
        naziv: 'Node.js',
        ikona: 'bi-server',
        opis: 'JavaScript runtime za server-side aplikacije',
        link: 'https://nodejs.org/',
      },
      {
        naziv: 'Express.js',
        ikona: 'bi-box-seam',
        opis: 'Minimalistički backend framework za Node.js',
        link: 'https://expressjs.com/',
      },
      {
        naziv: 'MySQL',
        ikona: 'bi-database-fill',
        opis: 'Relacijska baza podataka',
        link: 'https://www.mysql.com/',
      },
      {
        naziv: 'MongoDB',
        ikona: 'bi-hdd-network-fill',
        opis: 'NoSQL baza podataka',
        link: 'https://www.mongodb.com/',
      },
      {
        naziv: 'Git',
        ikona: 'bi-git',
        opis: 'Sustav kontrole verzija',
        link: 'https://git-scm.com/',
      },
      {
        naziv: 'Docker',
        ikona: 'bi-box-arrow-in-right',
        opis: 'Platforma za kontejnerizaciju',
        link: 'https://www.docker.com/',
      },
      {
        naziv: 'Webpack',
        ikona: 'bi-filetype-js',
        opis: 'Modularni bundler za JavaScript aplikacije',
        link: 'https://webpack.js.org/',
      },
      {
        naziv: 'Sass',
        ikona: 'bi-brush',
        opis: 'CSS preprocesor za napredne stilove',
        link: 'https://sass-lang.com/',
      },
      {
        naziv: 'REST API',
        ikona: 'bi-cloud-arrow-up-fill',
        opis: 'Arhitektura za web servise',
        link: 'https://restfulapi.net/',
      },
    ],
    tutorijali: [
      {
        naslov: 'Početnici',
        linkovi: [
          { naziv: 'Uvod u HTML', url: 'https://www.w3schools.com/html/' },
          { naziv: 'Uvod u CSS', url: 'https://www.w3schools.com/css/' },
          { naziv: 'JavaScript Osnove', url: 'https://www.w3schools.com/js/' },
        ],
      },
      {
        naslov: 'Napredni',
        linkovi: [
          { naziv: 'Vue.js Tutorijal', url: 'https://vuejs.org/guide/introduction.html' },
          { naziv: 'Bootstrap 5', url: 'https://getbootstrap.com/docs/5.0/getting-started/introduction/' },
        ],
      },
    ],
    kontakt: {
      ime: '',
      email: '',
      poruka: '',
    },
    validacija: false,
    feedbackPoruka: '',
  }
},


  methods: {
    validEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return re.test(email)
    },

    posaljiKontakt() {
      this.validacija = true

      if (
        this.kontakt.ime &&
        this.validEmail(this.kontakt.email) &&
        this.kontakt.poruka
      ) {
        // Ovdje ide slanje podataka na backend ako postoji

        this.feedbackPoruka = 'Vaša poruka je uspješno poslana. Hvala!'
        // Reset forme
        this.kontakt.ime = ''
        this.kontakt.email = ''
        this.kontakt.poruka = ''
        this.validacija = false
      } else {
        this.feedbackPoruka = ''
      }
    },

    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' })
    },
  },
}
</script>

<style scoped>
.hero {
  height: 300px;
  background-color: #0d6efd;
  padding: 1rem;
  position: relative;
}

.hero .content {
  max-width: 600px;
}

.images-container {
position: absolute;
top: 0;
right: 0;
width: 300px;
height: 300px;
overflow: hidden;
}

.image-right img {
width: 100%;
height: 100%;
object-fit: cover;
}

.tech-card:hover {
background-color: #e9ecef;
transition: background-color 0.3s ease;
}

button[aria-label='Scroll to top'] {
font-size: 1.5rem;
z-index: 9999;
}
</style>
 
