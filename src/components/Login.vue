<template>
  <div>
    <!-- Gumb za otvaranje modala -->
    <button class="btn btn-primary" @click="showModal = true">Prijava</button>

    <!-- Modal prozor -->
    <div
      class="modal fade show"
      tabindex="-1"
      style="display: block;"
      v-if="showModal"
      role="dialog"
    >
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Prijava korisnika</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>

          <div class="modal-body">
            <form @submit.prevent="submitForm" novalidate>
              <div class="mb-3">
                <label for="ime" class="form-label">Ime</label>
                <input type="text" class="form-control" id="ime" v-model="ime" required />
                <div class="text-danger" v-if="errors.ime">{{ errors.ime }}</div>
              </div>

              <div class="mb-3">
                <label for="prezime" class="form-label">Prezime</label>
                <input type="text" class="form-control" id="prezime" v-model="prezime" required />
                <div class="text-danger" v-if="errors.prezime">{{ errors.prezime }}</div>
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" v-model="email" required />
                <div class="text-danger" v-if="errors.email">{{ errors.email }}</div>
              </div>

              <div class="mb-3">
                <label for="lozinka" class="form-label">Lozinka</label>
                <input type="password" class="form-control" id="lozinka" v-model="lozinka" required />
                <div class="text-danger" v-if="errors.lozinka">{{ errors.lozinka }}</div>
              </div>
            </form>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Odustani</button>
            <button type="button" class="btn btn-primary" @click="submitForm">Prijavi se</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Pozadina modala -->
    <div class="modal-backdrop fade show" v-if="showModal"></div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      ime: '',
      prezime: '',
      email: '',
      lozinka: '',
      errors: {},
    };
  },
  methods: {
    validateForm() {
      this.errors = {};
      if (!this.ime) this.errors.ime = 'Polje je obavezno.';
      if (!this.prezime) this.errors.prezime = 'Polje je obavezno.';
      if (!this.email) {
        this.errors.email = 'Polje je obavezno.';
      } else if (!/.+@.+\..+/.test(this.email)) {
        this.errors.email = 'Unesite ispravan email.';
      }
      if (!this.lozinka) {
        this.errors.lozinka = 'Polje je obavezno.';
      } else if (this.lozinka.length < 6) {
        this.errors.lozinka = 'Lozinka mora imati najmanje 6 znakova.';
      }

      return Object.keys(this.errors).length === 0;
    },
    submitForm() {
      if (this.validateForm()) {
        console.log('Ime:', this.ime);
        console.log('Prezime:', this.prezime);
        console.log('Email:', this.email);
        console.log('Lozinka:', this.lozinka);
        this.closeModal();
        this.resetForm();
      }
    },
    closeModal() {
      this.showModal = false;
    },
    resetForm() {
      this.ime = '';
      this.prezime = '';
      this.email = '';
      this.lozinka = '';
      this.errors = {};
    },
  },
};
</script>

<style scoped>
/* Bootstrap modal workaround za prikaz bez JavaScript metode show() */
.modal {
  display: block;
}
</style>
