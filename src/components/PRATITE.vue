<template>
  <div class="container my-5">
    <h2>Praćenje studentskih projekata</h2>
    <p>Pregled napretka studenata po fazama projekta.</p>

    <!-- Kalendar obaveza -->
    <div class="my-5">
      <h4>Kalendar rokova</h4>
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
    <table class="table table-bordered table-hover mt-4">
      <thead class="table-dark">
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
h2 {
  font-weight: bold;
}

.table th,
.table td {
  text-align: center;
  vertical-align: middle;
}

.vuecal {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
</style>
