<template>
  <div class="rokovi-container">
    <h2>Rokovi za obranu projekata</h2>

    <div class="dodaj-rok">
      <h3>Dodaj novi rok</h3>
      <form @submit.prevent="dodajRok">
        <label>
          Datum:
          <input type="date" v-model="noviRok.datum" required />
        </label>
        <label>
          Vrijeme:
          <input type="time" v-model="noviRok.vrijeme" required />
        </label>
        <label>
          Napomena:
          <input type="text" v-model="noviRok.napomena" />
        </label>
        <button type="submit">Dodaj rok</button>
      </form>
    </div>

    <h3>Popis rokova</h3>
    <ul class="rok-lista">
  <li v-for="rok in rokovi" :key="rok.id" class="rok-item">
    <div class="rok-info">
      <div class="datum-vrijeme">
        <span>{{ rok.datum }} {{ rok.vrijeme }}</span>
      </div>
      <div class="napomena" v-if="rok.napomena">
        - {{ rok.napomena }}
      </div>
    </div>

        <div class="akcije">
          <button v-if="!prijavljeniRokoviIds.includes(rok.id)" @click="prijaviNaRok(rok.id)">
            Prijavi se
          </button>
          <button v-else @click="odjaviSaRoka(rok.id)">
            Odjavi se
          </button>

          <!-- Uređivanje -->
          <button @click="pripremiZaUredjivanje(rok)">Uredi</button>
          <button @click="obrisiRok(rok.id)">Obriši</button>
        </div>
      </li>
    </ul>

    <!-- Forma za uređivanje -->
    <div v-if="urediRok.id" class="dodaj-rok">
      <h3>Uredi rok</h3>
      <form @submit.prevent="urediPostojeciRok">
        <label>
          Datum:
          <input type="date" v-model="urediRok.datum" required />
        </label>
        <label>
          Vrijeme:
          <input type="time" v-model="urediRok.vrijeme" required />
        </label>
        <label>
          Napomena:
          <input type="text" v-model="urediRok.napomena" />
        </label>
        <button type="submit">Spremi promjene</button>
        <button type="button" @click="urediRok = {}">Otkaži</button>
      </form>
    </div>

    <p v-if="poruka" :class="{'error-msg': porukaTip === 'error', 'success-msg': porukaTip === 'success'}">
      {{ poruka }}
    </p>
  </div>
</template>

<script>
export default {
  name: "RokPrijava",
  data() {
    return {
      rokovi: [],
      prijavljeniRokoviIds: [],
      noviRok: { datum: "", vrijeme: "", napomena: "" },
      urediRok: {},
      userId: 1,
      poruka: "",
      porukaTip: ""
    };
  },
  mounted() {
    this.dohvatiRokove();
    this.dohvatiPrijave();
  },
  methods: {
    async dohvatiRokove() {
      try {
        const res = await fetch("http://localhost/my_project/rokovi.php");
        const data = await res.json();
        if (data.success) this.rokovi = data.data;
        else this.prikaziPoruku(data.message || "Greška pri dohvatu rokova", "error");
      } catch {
        this.prikaziPoruku("Greška pri dohvatu rokova", "error");
      }
    },
    async dohvatiPrijave() {
      try {
        const res = await fetch(`http://localhost/my_project/prijave.php?user_id=${this.userId}`);
        const data = await res.json();
        if (data.success) {
          this.prijavljeniRokoviIds = data.data.map(p => p.rok_id);
        } else {
          this.prikaziPoruku(data.message || "Greška pri dohvatu prijava", "error");
        }
      } catch {
        this.prikaziPoruku("Greška pri dohvatu prijava", "error");
      }
    },
    async dodajRok() {
      try {
        const res = await fetch("http://localhost/my_project/rokovi.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.noviRok)
        });
        const data = await res.json();
        if (data.success) {
          this.prikaziPoruku("Rok uspješno dodan", "success");
          this.noviRok = { datum: "", vrijeme: "", napomena: "" };
          this.dohvatiRokove();
        } else {
          this.prikaziPoruku(data.message || "Greška pri dodavanju roka", "error");
        }
      } catch {
        this.prikaziPoruku("Greška pri dodavanju roka", "error");
      }
    },
    async prijaviNaRok(rokId) {
  try {
    const res = await fetch("http://localhost/my_project/prijave.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ user_id: this.userId, rok_id: rokId }),
    });
    const data = await res.json();
    if (data.success) {
      this.prikaziPoruku("Prijava uspješna", "success");

      // 🔧 Ručno dodaj u listu prijavljenih
      if (!this.prijavljeniRokoviIds.includes(rokId)) {
        this.prijavljeniRokoviIds.push(rokId);
      }

      // Ako želiš opet dohvatiti sve iz backenda:
      // this.dohvatiPrijave(); // nije nužno odmah
    } else {
      this.prikaziPoruku(data.message || "Greška pri prijavi", "error");
      if (data.error) console.error("Detalji greške:", data.error);
    }
  } catch {
    this.prikaziPoruku("Greška pri prijavi", "error");
  }
},
    async odjaviSaRoka(rokId) {
      try {
        const res = await fetch("http://localhost/my_project/prijave.php", {
          method: "DELETE",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ user_id: this.userId, rok_id: rokId })
        });
        const data = await res.json();
        if (data.success) {
          this.prikaziPoruku("Odjavljeno sa roka", "success");
          this.dohvatiPrijave();
        } else {
          this.prikaziPoruku(data.message || "Greška pri odjavi", "error");
        }
      } catch {
        this.prikaziPoruku("Greška pri odjavi", "error");
      }
    },
    async obrisiRok(rokId) {
      if (!confirm("Jesi siguran da želiš obrisati ovaj rok?")) return;
      try {
        const res = await fetch(`http://localhost/my_project/rokovi.php?id=${rokId}`, {
          method: "DELETE"
        });
        const data = await res.json();
        if (data.success) {
          this.prikaziPoruku("Rok obrisan", "success");
          this.dohvatiRokove();
        } else {
          this.prikaziPoruku(data.message || "Greška pri brisanju", "error");
        }
      } catch {
        this.prikaziPoruku("Greška pri brisanju", "error");
      }
    },
    pripremiZaUredjivanje(rok) {
      this.urediRok = { ...rok };
    },
    async urediPostojeciRok() {
      try {
        const res = await fetch(`http://localhost/my_project/rokovi.php?id=${this.urediRok.id}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.urediRok)
        });
        const data = await res.json();
        if (data.success) {
          this.prikaziPoruku("Rok ažuriran", "success");
          this.urediRok = {};
          this.dohvatiRokove();
        } else {
          this.prikaziPoruku(data.message || "Greška pri ažuriranju", "error");
        }
      } catch {
        this.prikaziPoruku("Greška pri ažuriranju", "error");
      }
    },
    prikaziPoruku(msg, tip) {
      this.poruka = msg;
      this.porukaTip = tip;
      setTimeout(() => {
        this.poruka = "";
        this.porukaTip = "";
      }, 4000);
    }
  }
};
</script>


<style scoped>
.rokovi-container {
  max-width: 600px;
  margin: auto;
  font-family: Arial, sans-serif;
  padding: 2rem;
  background: #2a5298;
  border-radius: 12px;
  color: white;
}
.dodaj-rok {
  background-color: #1f3b7a;
  padding: 1rem;
  border-radius: 8px;
  margin-bottom: 2rem;
}
label {
  display: block;
  margin-bottom: 0.5rem;
}
input {
  display: block;
  margin-bottom: 1rem;
  width: 100%;
  padding: 0.4rem;
  border-radius: 6px;
  border: none;
}
button {
  background-color: #1411bb;
  color: white;
  border: none;
  padding: 0.6rem 1rem;
  border-radius: 6px;
  cursor: pointer;
}
button:hover {
  background-color: #1d0cbd;
}
.rok-lista {
  list-style: none;
  padding: 0;
}
.rok-item {
  padding: 0.8rem;
  background: #2a5298;
  border-radius: 6px;
  margin-bottom: 0.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.akcije {
  margin-left: 1rem;
}
.error-msg {
  color: #ff6666;
  margin-top: 1rem;
}
.success-msg {
  color: #a6ff8f;
  margin-top: 1rem;
}
</style>
