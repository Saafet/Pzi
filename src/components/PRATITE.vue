<template>
  <div class="container my-5">
    <h2 class="main-title">Praćenje studentskih projekata</h2>
    <p class="subtitle">Pregled napretka studenata po fazama projekta.</p>

    <!-- Kalendar obaveza -->
    <div class="calendar-wrapper my-5 p-4 rounded shadow-sm">
      <h4 class="calendar-title">Kalendar rokova</h4>
      <vue-cal
        style="height: 500px"
        :events="events"
        @cell-click="addDeadline"
        :on-event-click="editDeadline"
        time="24"
        default-view="month"
        hide-view-selector
        :disable-views="['years', 'year', 'week', 'day']"
      />
    </div>

    <!-- Tablica napretka -->
    <table class="table table-bordered table-hover mt-4 progress-table">
      <thead class="table-header">
        <tr>
          <th>Ime i prezime</th>
          <th>Vizija</th>
          <th>Figma</th>
          <th>GitHub</th>
          <th>Backend</th>
          <th>Testiranje</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(student, index) in students" :key="index">
          <td>{{ student.name }}</td>
          <td><input type="checkbox" v-model="student.vizija" /></td>
          <td><input type="checkbox" v-model="student.figma" /></td>
          <td><input type="checkbox" v-model="student.github" /></td>
          <td><input type="checkbox" v-model="student.backend" /></td>
          <td><input type="checkbox" v-model="student.testiranje" /></td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

export default {
  name: 'PRATITE',
  components: {
    VueCal
  },
  data() {
    return {
      events: [],
      students: [
        {
          name: 'Ana Kovač',
          vizija: false,
          figma: false,
          github: false,
          backend: false,
          testiranje: false
        },
        {
          name: 'Marko Marić',
          vizija: true,
          figma: true,
          github: false,
          backend: false,
          testiranje: false
        }
      ]
    }
  },
  methods: {
    addDeadline({ date }) {
      const task = prompt('Unesi naziv zadatka (npr. Predati Figma dizajn)')
      if (task) {
        this.events.push({
          start: date,
          end: date,
          title: task
        })
      }
    },
    editDeadline(event) {
      const noviNaziv = prompt('Uredi naziv zadatka:', event.title)
      if (noviNaziv) {
        event.title = noviNaziv
      }
    }
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.container {
  font-family: 'Poppins', sans-serif;
  color: #2c3e50;
}

/* Naslovi */
.main-title {
  font-weight: 600;
  font-size: 2.8rem;
  background: linear-gradient(90deg,rgb(119, 111, 127),rgb(9, 70, 238)); 
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 0.3rem;
  padding-bottom: 0.4rem;
  letter-spacing: 1px;
}

.subtitle {
  font-size: 1.2rem;
  color:rgb(62, 82, 198);
  margin-bottom: 2.5rem;
  font-weight: 500;
}

/* Kalendar */
.calendar-wrapper {
  background-color: #e8eaf6; /* svijetla plava */
  border: 1.5px solid #3949ab;
}

.calendar-title {
  font-size: 1.6rem;
  font-weight: 600;
  color: #5c6bc0;
  margin-bottom: 1rem;
}

/* Tablica */
.progress-table {
  font-size: 1.1rem;
}

.table-header {
  background-color:rgb(14, 23, 75);
  color: white;
  font-weight: 600;
  letter-spacing: 0.8px;
}

.table tbody tr:nth-child(odd) {
  background-color: #f3f6fc;
}

.table tbody tr:hover {
  background-color: #c5cae9;
}

.table th,
.table td {
  text-align: center;
  vertical-align: middle;
  padding: 0.8rem 1rem;
}
</style>
