<template>
  <div>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">TaskNest</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <div class="w-100 d-flex justify-content-between align-items-center">
            
            <ul class="navbar-nav mx-auto">
              <li class="nav-item"><a class="nav-link" href="#edu">UČI WEB</a></li>

<li class="nav-item" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'">
  <a class="nav-link" href="#prijavi">PRIJAVI PROJEKT</a>
</li>

<li class="nav-item" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'">
  <a class="nav-link" href="#pratite">PRATITE RAD</a>
</li>

<li class="nav-item" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'">
  <a class="nav-link" href="#ocjenjivanje">OCJENJIVANJE</a>
</li>

<li class="nav-item"><a class="nav-link" href="#kontakt">KONTAKT</a></li>


            </ul>

            
            <ul class="navbar-nav">
              <li class="nav-item">
                <button class="nav-link btn btn-link p-0" @click="logout" style="border: none; background: none;">Logout</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <Login v-if="!userRole" />




    <br><br>

    <section class=" uvodni-tekst mt-5 pt-5 py-5 bg-light rounded shadow">
      <div class="row align-items-center">
        
        <div class="col-md-6 px-4 text-dark">
          <h2 class="mb-4 fw-bold text-primary">Dobrodošli na platformu TaskNest!</h2>
          <p class="lead">
            <strong>TaskNest</strong> je vaša centralna točka za <strong>prijavu, praćenje i ocjenjivanje PZI
              zadataka</strong>. Bilo da ste student koji prijavljuje projekt, mentor koji nadzire napredak ili
            nastavnik koji ocjenjuje — ovdje ste na pravom mjestu.
          </p>
          <p>
            Kroz intuitivno sučelje i interaktivne komponente, platforma omogućuje jednostavnu suradnju i transparentno
            praćenje svakog koraka u razvoju vašeg zadatka. Osim toga, nudi edukativne resurse koji će vas voditi kroz
            sve faze web developmenta.
          </p>
          <a href="#prijavi" class="btn btn-primary btn-lg mt-3">🚀 Započni sada</a>
        </div>

        
        <div class="col-md-6 text-center">
          <img src="/fotografije/tehnologije.png" alt="Tehnologije" class="img-fluid rounded shadow-sm"
            style="max-height: 320px;" />
        </div>
      </div>
    </section>

    
    <section id="edu" class="container mt-5">
      <h2 class="text-center mb-4">Tehnologije</h2>

      <div class="row text-center">
        <div v-for="tech in tehnologije" :key="tech.naziv" class="col-6 col-md-3 mb-4">
          <a :href="tech.link" target="_blank" rel="noopener" class="tech-card p-3 border rounded d-block text-decoration-none text-light" :title="'Saznaj više o ' + tech.naziv">
            <i :class="['bi', tech.ikona]" style="font-size: 3rem; color: #007bff;"></i>
            <h5 class="mt-2">{{ tech.naziv }}</h5>
            <p class="small">{{ tech.opis }}</p>
          </a>
        </div>
      </div>

      
      <div class="accordion" id="tutorialAccordion">
        <div class="accordion-item" v-for="(kat, idx) in tutorijali" :key="idx">
          <h2 class="accordion-header" :id="'heading' + idx">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              :data-bs-target="'#collapse' + idx" aria-expanded="false" :aria-controls="'collapse' + idx">
              {{ kat.naslov }}
            </button>
          </h2>
          <div :id="'collapse' + idx" class="accordion-collapse collapse" :aria-labelledby="'heading' + idx"
            data-bs-parent="#tutorialAccordion">
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

      
      <div class="d-flex gap-4 flex-wrap justify-content-center mt-5">
        <div class="video-wrapper">
          <h4 class="video-title text-center">Pogledajte: Kako izraditi svoju prvu Vue aplikaciju</h4>
          <div class="ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/JLt3GrDZDvQ?si=ot0en-nPvNpEZIiQ" title="Vue Uvod" allowfullscreen></iframe>
          </div>
        </div>

        <div class="video-wrapper">
          <h4 class="video-title text-center">Pogledajte: Backend dio aplikacije</h4>
          <div class="ratio ratio-16x9">
            <iframe src="https://www.youtube.com/embed/OeEHJgzqS1k?si=GbUrD_zkbiPFA_mC" title="Back Tutorial" allowfullscreen></iframe>
          </div>
        </div>
      </div>

      
      <div class="accordion mt-5" id="edukacijaAccordion">
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#kolegij"
              aria-expanded="true" aria-controls="kolegij">
              📘 Prijava na e-Kolegij
            </button>
          </h2>
          <div id="kolegij" class="accordion-collapse collapse show" aria-labelledby="headingOne"
            data-bs-parent="#edukacijaAccordion">
            <div class="accordion-body">
              Prijavite se na e-kolegij koristeći svoj eduID račun.<br>
              <strong>🔗 Poveznica:</strong> <a href="https://eucenje.sum.ba/moodle/course/view.php?id=4980" target="_blank">e-kolegij</a><br>
              <strong>🔐 Lozinka za pristup:</strong> <code>Pzi2025#</code><br><br>
              📅 <strong>Orašje - Google Meet:</strong><br>
              <a href="https://meet.google.com/xyz-abc-def" target="_blank" class="btn btn-outline-primary mt-2">Pristupi predavanju</a>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#skripta"
              aria-expanded="false" aria-controls="skripta">
              📄 Upute za izradu projekta
            </button>
          </h2>
          <div id="skripta" class="accordion-collapse collapse" aria-labelledby="headingTwo"
            data-bs-parent="#edukacijaAccordion">
            <div class="accordion-body">
              Ovaj PDF dokument sadrži sve ključne informacije o načinu izrade te ocjenjivanju vašeg projekta.
              <a href="/dokumentacija/Upute_za_izradu_projekta.pdf" class="btn btn-success mt-2" target="_blank">Prikaži dokument</a>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingThree">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
              data-bs-target="#dokumentacija" aria-expanded="false" aria-controls="dokumentacija">
              📚 Dokumentacija projekta
            </button>
          </h2>
          <div id="dokumentacija" class="accordion-collapse collapse" aria-labelledby="headingThree"
            data-bs-parent="#edukacijaAccordion">
            <div class="accordion-body">
              Detaljna prezentacija svih faza razvoja aplikacije.<br>
              <a href="https://docs.google.com/presentation/d/1AO7DxeAvBOM0qfUfVmtM1iw9hiEfsE2emQAjpnF_9FQ/edit?slide=id.p#slide=id.p" class="btn btn-secondary mt-2" target="_blank">Otvori prezentaciju</a>
            </div>
          </div>
        </div>

        <div class="accordion-item">
          <h2 class="accordion-header" id="headingFour">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#github"
              aria-expanded="false" aria-controls="github">
              💻 GitHub projekt
            </button>
          </h2>
          <div id="github" class="accordion-collapse collapse" aria-labelledby="headingFour"
            data-bs-parent="#edukacijaAccordion">
            <div class="accordion-body">
              Pogledaj izvorni kod aplikacije.<br>
              <a href="https://github.com/MateaTufekcic/PZI.git" class="btn btn-dark mt-2" target="_blank">Pogledaj primjer</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <section id="prijavi" class="container mt-5" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'">
  <PRIJAVI :userRole="userRole" />
</section>

    <section id="pratite" class="container mt-5" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'">
      <PRATITE :userRole="userRole" />
    </section>
    <section id="ocjenjivanje" class="container mt-5" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'">
  <OCJENJIVANJE :userRole="userRole" />
</section>


    <section id="ocjenjivanje" class="container mt-5" v-if="userRole === 'student' || userRole === 'professor' || userRole === 'admin'" >
      <RokoviPrijave :userRole="userRole" />
    </section>


    
    <section class="container text-center mt-5">
      <h2 class="mb-4 gradient-text">Statistika</h2>
      <div class="row mt-4">
        <div class="col-md-4">
          <div class="stat-box">
            <h3 class="counter gradient-text">37+</h3>
            <p class="gradient-text">Prijavljenih projekata</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-box">
            <h3 class="counter gradient-text">21</h3>
            <p class="gradient-text">Aktivnih korisnika</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stat-box">
            <h3 class="counter gradient-text">9</h3>
            <p class="gradient-text">Mentora</p>
          </div>
        </div>
      </div>
    </section>

    
    <section id="kontakt" class="container mt-5 mb-5">
      <h2 class="text-center mb-4">Kontaktirajte profesora</h2>
      <form @submit.prevent="posaljiKontakt" novalidate>
        <div class="mb-3">
          <label for="ime" class="form-label">Ime i prezime</label>
          <input id="ime" type="text" v-model.trim="kontakt.ime" class="form-control"
            :class="{ 'is-invalid': validacija && !kontakt.ime }" required />
          <div class="invalid-feedback">Molimo unesite ime.</div>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" v-model.trim="kontakt.email" class="form-control"
            :class="{ 'is-invalid': validacija && !validEmail(kontakt.email) }" required />
          <div class="invalid-feedback">Unesite ispravan email.</div>
        </div>

        <div class="mb-3">
          <label for="poruka" class="form-label">Poruka</label>
          <textarea id="poruka" rows="4" v-model.trim="kontakt.poruka" class="form-control"
            :class="{ 'is-invalid': validacija && !kontakt.poruka }" required></textarea>
          <div class="invalid-feedback">Poruka ne može biti prazna.</div>
        </div>

        <button type="submit" class="btn btn-primary">Pošalji</button>
      </form>
      <div v-if="feedbackPoruka" class="alert alert-success mt-3" role="alert">
        {{ feedbackPoruka }}
      </div>
    </section>

    
    <footer class="custom-footer bg-dark text-white text-center py-4 mt-5">
      <div class="container">
        <p class="mb-2 fs-5 footer-title">TaskNest | PZI projekt postavljen na studentski server</p>
        <small class="d-block mb-3">Izradili: Matea Tufekčić &amp; Safet Srna, 2025.</small>

        
        <div class="footer-icons d-flex justify-content-center gap-3">
          <a href="https://www.youtube.com/@universityofmostartv" target="_blank" class="footer-icon" title="YouTube">
            <i class="fab fa-youtube"></i>
          </a>
          <a href="https://www.instagram.com/fpmoz.mostar/" target="_blank" class="footer-icon" title="Instagram">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://mail.google.com/mail/u/3/#inbox?compose=CllgCJlGVGPWXsjDqRDKRKWCGcqfgkNlWFBxVHfXwXPxXxGRQpVgqMLKvDTkjWrsTPtmTZclWJV" class="footer-icon" title="Pošalji mail">
            <i class="fas fa-envelope"></i>
          </a>
          <a href="https://fpmoz.sum.ba/" target="_blank" class="footer-icon" title="Fakultet">
            <i class="fas fa-university"></i>
          </a>
        </div>
      </div>
    </footer>


    
    <button @click="scrollToTop" class="btn btn-primary position-fixed bottom-0 end-0 m-4" aria-label="Scroll to top">
      ⬆️
    </button>
  </div>
</template>

<script>
import PRIJAVI from './PRIJAVI.vue'
import PRATITE from './PRATITE.vue'
import OCJENJIVANJE from './OCJENJIVANJE.vue'
import Login from './Login.vue'
import RokoviPrijave from './RokoviPrijave.vue'

export default {
  name: 'App',
  components: { PRIJAVI, PRATITE, OCJENJIVANJE, Login, RokoviPrijave },

  data() {
    return {
      prikaziLogin: false,
      userRole: null,

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
          naziv: 'Vuetify',
          ikona: 'bi-ui-checks-grid',
          opis: 'UI framework za Vue.js',
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

      kontakt: {
        ime: '',
        email: '',
        poruka: '',
      },
      validacija: false,
      feedbackPoruka: '',
    }
  },

  created() {
  const user = localStorage.getItem("user");
  console.log('user iz localStorage:', user);
  if (!user) {
    this.$router.push('/');
  } else {
    const parsedUser = JSON.parse(user);
    console.log('Parsed user:', parsedUser);
    this.userRole = parsedUser.user.role;
    console.log('userRole:', this.userRole);
  }
},



  methods: {
    validEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },

    otvoriLogin() {
      this.prikaziLogin = true;
    },

    posaljiKontakt() {
  this.validacija = true;

  if (
    !this.kontakt.ime ||
    !this.validEmail(this.kontakt.email) ||
    !this.kontakt.poruka
  ) {
    this.feedbackPoruka = "Poruka je uspješno poslata!";

setTimeout(() => {
  this.feedbackPoruka = "";
}, 5000);
  }

  fetch("http://localhost/my_project/kontakt.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(this.kontakt)
  })
    .then(response => response.json())
    .then(data => {
      this.feedbackPoruka = data.poruka;
      this.kontakt = { ime: '', email: '', poruka: '' };
      this.validacija = false;
    })
    .catch(error => {
      console.error("Greška:", error);
      this.feedbackPoruka = "Došlo je do greške prilikom slanja.";
    });
},

    scrollToTop() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    logout() {
      localStorage.removeItem("user");
      this.$router.push('/');
    },
  }
}
</script>

<style scoped>
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

.navbar-brand {
  font-family: 'Racing Sans One', sans-serif;
  font-size: 1.8rem;
  color: #00aaff; 
  text-shadow: 1px 1px 2px rgba(0,0,0,0.6);
  transition: color 0.3s ease;
}

.navbar-brand:hover {
  color: #005577; 
}


.navbar {
  background: linear-gradient(90deg, #000000 0%, #222222 100%);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.7);
}


.nav-link {
  color: #bbbbbb;
  font-weight: 600;
  padding: 0.5rem 1rem;
  transition: color 0.3s ease, background-color 0.3s ease;
  border-radius: 4px;
}

.nav-link:hover, .nav-link:focus {
  color: #00aaff;
  background-color: rgba(0, 170, 255, 0.1);
  text-shadow: 0 0 5px #00aaff;
}

.nav-link.active {
  color: #00aaff;
  font-weight: 700;
  border-bottom: 2px solid #00aaff;
}


.navbar-toggler {
  border-color: #00aaff;
}

.navbar-toggler-icon {
  filter: invert(60%) sepia(90%) saturate(500%) hue-rotate(180deg) brightness(90%) contrast(85%);
}

.navbar .container-fluid {
  padding: 0.5rem 2rem;
}

@media (max-width: 991px) {
  .navbar-brand {
    font-size: 1.4rem;
  }
}

.uvodni-tekst {
  font-family: 'Poppins', sans-serif;
  line-height: 1.6;
  color: #333333; 
}

.uvodni-tekst h2 {
  font-weight: 600;
  color: #007bff; 
}

.uvodni-tekst p.lead {
  font-weight: 500;
  font-size: 1.125rem;
  color: #555555;
}

.uvodni-tekst p {
  font-size: 1rem;
  color: #666666;
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

.tech-card {
  width: 300px; 
  height: 200px;
    
  
}

.tech-card:hover {
background-color:rgb(122, 123, 124);
transition: background-color 0.3s ease;

}

button[aria-label='Scroll to top'] {
font-size: 1.5rem;
z-index: 9999;
}
.video-title {
  font-family: 'Racing Sans One', sans-serif;
  font-size: 2rem;
  color: #1e3c72;
  font-weight: bold;
  margin-bottom: 1rem;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
  letter-spacing: 1px;
}

.video-wrapper {
  background-color: #f4f6fa;
  border: 2px solid #2a5298;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 8px 18px rgba(42, 82, 152, 0.2);
  width: 45%;
}

.stat-box {
    background-color:rgb(199, 237, 241); 
    padding: 30px;
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  }

  .stat-box:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
  }

  .counter {
    font-size: 2.5rem;
    font-weight: bold;
    animation: countUp 1.5s ease-out;
  }

  @keyframes countUp {
    0% { transform: scale(0); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
  }

  .gradient-text {
    background: linear-gradient(45deg,rgb(78, 78, 175),rgb(11, 40, 70)); 
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    color: transparent;
  }
  .custom-footer {
  
  background-color: #000000; 
  font-family: 'Poppins', sans-serif;
  border-top: 2px solid #444;

}

.footer-title {
  font-weight: 600;
  letter-spacing: 0.5px;
  color: #cfd8dc;
}

.footer-icons .footer-icon {
  color: #b0bec5;
  font-size: 1.5rem;
  transition: all 0.3s ease;
}

.footer-icons .footer-icon:hover {
  color: #ffffff;
  transform: scale(1.2);
  text-shadow: 0 0 8px #3a4ca8;
}
</style>
 