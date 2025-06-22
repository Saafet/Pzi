<template>
  <div class="container my-5">

  
    <div v-if="userRole === 'professor' || userRole === 'admin'" class="mb-5 border rounded p-3 bg-light">
      <h3>Dodaj novu temu</h3>
      <form @submit.prevent="dodajTemu" class="row g-2 align-items-center">
        <div class="col-auto flex-grow-1">
          <input
            v-model="novaTema"
            type="text"
            class="form-control"
            placeholder="Unesi naziv nove teme"
            required
          />
        </div>
        <div class="col-auto">
          <button type="submit" class="btn btn-success">Dodaj temu</button>
        </div>
      </form>
    </div>

    
    <div v-if="userRole === 'student' || userRole === 'admin'" class="mb-5">
      <h2>Prijava projekta</h2>
      <form @submit.prevent="dodajProjekt">
        <div class="mb-3">
          <label class="form-label">Ime</label>
          <input v-model="forma.ime" type="text" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Prezime</label>
          <input v-model="forma.prezime" type="text" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input v-model="forma.email" type="email" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Datum</label>
          <input v-model="forma.datum" type="date" class="form-control" required />
        </div>

        <div class="mb-3">
          <label class="form-label">Odaberite temu</label>
          <select v-model="forma.tema_id" class="form-select" required>
            <option value="">Odaberite temu</option>
            <option
              v-for="tema in sveTeme"
              :key="tema.id"
              :value="tema.id"
              :disabled="temaZauzeta(tema.id)"
            >
              {{ tema.naziv }}
              <span v-if="temaZauzeta(tema.id)">(Tema je zauzeta)</span>
            </option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Prijavi projekt</button>
      </form>
    </div>

    
    <div class="mb-5">
      <h3>Teme dostupne za odabir</h3>
      <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col" v-for="tema in sveTeme" :key="tema.id">
          <div
            class="card h-100 shadow"
            :class="{ 'bg-secondary text-white': temaZauzeta(tema.id) }"
          >
            <div class="card-body">
              <h5 class="card-title">{{ tema.naziv }}</h5>
              <p v-if="temaZauzeta(tema.id)" class="text-warning">
                <i class="bi bi-exclamation-triangle"></i> Tema zauzeta
              </p>
              <p v-else class="text-success">
                <i class="bi bi-check-circle"></i> Tema dostupna
              </p>

              
              <div class="mt-3">
                <button v-if="userRole === 'professor' || userRole === 'admin'" class="btn btn-sm btn-warning me-2" @click="pokreniUredi(tema)">Uredi</button>
                <button v-if="userRole === 'professor' || userRole === 'admin'" class="btn btn-sm btn-danger" @click="obrisiTemu(tema.id)">Obriši</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    
    <div v-if="userRole === 'student' || userRole === 'admin'" class="mb-5">
      <h3 >Vaši prijavljeni projekti</h3>
      <div v-if="projekti.length === 0">
        <p>Nema prijavljenih projekata.</p>
      </div>
      <div v-else>
        <div
          class="border p-3 rounded mb-3"
          v-for="projekat in projekti"
          :key="projekat.id"
        >
          <p><b>Ime:</b> {{ projekat.ime }} {{ projekat.prezime }}</p>
          <p><b>Email:</b> {{ projekat.email }}</p>
          <p><b>Datum:</b> {{ projekat.datum }}</p>
          <p><b>Tema:</b> {{ projekat.tema_naziv }}</p>
          <button class="btn btn-sm btn-danger" @click="odjaviProjekt(projekat.id)">Odjavi temu</button>
        </div>
      </div>
    </div>

    
    <div v-if="urediTemu.id" class="modal-backdrop">
      <div class="modal d-block" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Uredi temu</h5>
              <button type="button" class="btn-close" @click="urediTemu = {}"></button>
            </div>
            <div class="modal-body">
              <input
                v-model="urediTemu.naziv"
                type="text"
                class="form-control"
                placeholder="Novi naziv"
              />
            </div>
            <div class="modal-footer">
              <button class="btn btn-secondary" @click="urediTemu = {}">Zatvori</button>
              <button class="btn btn-primary" @click="spremiUredi">Spremi</button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "PRIJAVI",
  props: {
    userRole: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      apiUrl: "http://studenti.sumit.ba/pzi/backend/",
      forma: { ime: "", prezime: "", email: "", datum: "", tema_id: "" },
      novaTema: "",
      sveTeme: [],
      projekti: [],
      urediTemu: {},
    };
  },
  methods: {
    async dodajTemu() {
      if (!this.novaTema.trim()) {
        alert("Unesite naziv teme.");
        return;
      }
      try {
        const res = await fetch(`${this.apiUrl}/dodaj-temu.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ naziv: this.novaTema.trim() }),
        });
        const json = await res.json();
        if (json.success) {
          alert(json.message);
          this.novaTema = "";
          this.ucitajTeme();
        } else {
          alert(json.message || "Greška pri dodavanju teme.");
        }
      } catch (e) {
        alert("Greška kod dodavanja teme.");
        console.error(e);
      }
    },
    async ucitajTeme() {
      try {
        const res = await fetch(`${this.apiUrl}/dohvati-teme.php`);
        const json = await res.json();
        if (json.success) {
          this.sveTeme = json.teme;
        } else {
          alert(json.message || "Greška kod dohvaćanja tema.");
        }
      } catch (e) {
        console.error("Greška kod dohvaćanja tema:", e);
      }
    },
    async dodajProjekt() {
      if (
        !this.forma.ime.trim() ||
        !this.forma.prezime.trim() ||
        !this.forma.email.trim() ||
        !this.forma.datum ||
        !this.forma.tema_id
      ) {
        alert("Sva polja su obavezna.");
        return;
      }
      try {
        const res = await fetch(`${this.apiUrl}/dodaj-projekt.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.forma),
        });
        const json = await res.json();
        if (json.success) {
          alert(json.message);
          this.forma = { ime: "", prezime: "", email: "", datum: "", tema_id: "" };
          this.ucitajProjekte();
          this.ucitajTeme();
        } else {
          alert(json.message || "Greška pri prijavi projekta.");
        }
      } catch (e) {
        alert("Greška pri slanju prijave.");
        console.error(e);
      }
    },
    async ucitajProjekte() {
      try {
        const res = await fetch(`${this.apiUrl}/dohvati-projekte.php`);
        const json = await res.json();
        if (json.success) {
          this.projekti = json.projekti;
        } else {
          alert(json.message || "Greška kod dohvaćanja projekata.");
        }
      } catch (e) {
        console.error("Greška kod dohvaćanja projekata:", e);
      }
    },
    temaZauzeta(temaId) {
  const brojPrijava = this.projekti.filter(p => p.tema_id === temaId).length;
  return brojPrijava >= 2;
}
,
    pokreniUredi(tema) {
      this.urediTemu = { ...tema };
    },
    async spremiUredi() {
  try {
    const res = await fetch(`${this.apiUrl}/uredi-temu.php`, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(this.urediTemu),
    });
    const json = await res.json();
    if (json.success) {
      alert("Tema uspješno uređena.");
      await this.ucitajTeme();      
      await this.ucitajProjekte();  
      this.urediTemu = {};
    } else {
      alert(json.message || "Greška pri uređivanju.");
    }
  } catch (e) {
    console.error("Greška:", e);
  }
},
    async obrisiTemu(id) {
      try {
        let res = await fetch(`${this.apiUrl}/obrisi-temu.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id }),
        });
        let json = await res.json();

        if (json.success) {
          alert(json.message);
          this.ucitajTeme();
        } else if (json.occupied) {
          if (confirm(json.message)) {
            res = await fetch(`${this.apiUrl}/obrisi-temu.php`, {
              method: "POST",
              headers: { "Content-Type": "application/json" },
              body: JSON.stringify({ id, force: true }),
            });
            json = await res.json();

            if (json.success) {
              alert(json.message);
              this.ucitajTeme();
              this.ucitajProjekte();
            } else {
              alert(json.message || "Greška pri brisanju teme.");
            }
          }
        } else {
          alert(json.message || "Greška pri brisanju teme.");
        }
      } catch (e) {
        console.error("Greška:", e);
        alert("Greška pri brisanju teme.");
      }
    },
    
    async odjaviProjekt(id) {
      if (!confirm("Jeste li sigurni da želite odjaviti ovaj projekt?")) return;
      try {
        const res = await fetch(`${this.apiUrl}/obrisi-projekt.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id }),
        });
        const json = await res.json();
        if (json.success) {
          alert(json.message);
          this.ucitajProjekte();
          this.ucitajTeme();
        } else {
          alert(json.message || "Greška pri odjavi projekta.");
        }
      } catch (e) {
        console.error(e);
        alert("Greška pri odjavi projekta.");
      }
    },
  },
  mounted() {
    this.ucitajTeme();
    this.ucitajProjekte();
  },
};
</script>

<style>
.modal-backdrop {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0,0,0,0.5);
  z-index: 1050;
}
</style>
