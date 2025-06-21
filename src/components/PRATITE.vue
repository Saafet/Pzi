<template>
  <div v-if="userRole === 'professor' || userRole === 'admin'" class="container my-5">
    <h2 class="main-title">Praćenje studentskih projekata</h2>
    <p class="subtitle">Ovdje možete vidjeti kada su prijavljeni projekti.</p>

    <div class="calendar-wrapper my-5 p-4 rounded shadow-sm">
      <div class="calendar-header d-flex align-center justify-between">
        <button @click="prevMonth" class="nav-btn">&lt;</button>

        <div class="month-year-selectors">
          <div class="selector" @click="toggleMonthDropdown">
            {{ months[currentMonth] }}
            <span class="arrow">&#9662;</span>
            <ul v-if="showMonthDropdown" class="dropdown">
              <li
                v-for="(m, idx) in months"
                :key="m"
                @click.stop="setMonth(idx)"
                :class="{selected: idx === currentMonth}"
              >
                {{ m }}
              </li>
            </ul>
          </div>

          <div class="selector" @click="toggleYearDropdown">
            {{ currentYear }}
            <span class="arrow">&#9662;</span>
            <ul v-if="showYearDropdown" class="dropdown">
              <li
                v-for="year in yearsRange"
                :key="year"
                @click.stop="setYear(year)"
                :class="{selected: year === currentYear}"
              >
                {{ year }}
              </li>
            </ul>
          </div>
        </div>

        <button @click="nextMonth" class="nav-btn">&gt;</button>
      </div>

      <div class="weekdays">
        <div v-for="day in weekDays" :key="day" class="weekday-cell">
          {{ day }}
        </div>
      </div>

      <div class="calendar-grid">
        <div
          v-for="day in calendarDays"
          :key="day.key"
          class="calendar-cell"
          :class="{
            'has-project': hasProject(day.date),
            'selected': isSelected(day.date),
            'not-current-month': !day.currentMonth
          }"
          @click="hasProject(day.date) && selectDay(day.date)"
        >
          {{ day.date.getDate() }}
          <div v-if="hasProject(day.date)" class="dot"></div>
        </div>
      </div>
    </div>

    <div class="project-details" v-if="selectedDay !== null">
    <h3>Projekti za {{ formatDate(selectedDay) }}</h3>
    <ul v-if="projectsOfTheDay.length > 0" class="list-group">
      <li v-for="p in projectsOfTheDay" :key="p.id" class="list-group-item">
        {{ p.ime }} {{ p.prezime }} - 
        <h4>{{ p.tema_naziv }} <br /></h4>
        <p>Email: {{ p.email }}</p>
      </li>
    </ul>
    <p v-else>Nema prijavljenih projekata za ovaj datum.</p>
  </div>

  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "DynamicCalendar",
  props: {
    userRole: {
      type: String,
      required: true
    }
  },
  data() {
    const today = new Date();
    return {
      allProjects: [],
      selectedDay: null,
      currentYear: today.getFullYear(),
      currentMonth: today.getMonth(),
      weekDays: ["Pon", "Uto", "Sri", "Čet", "Pet", "Sub", "Ned"],
      months: [
        "Siječanj", "Veljača", "Ožujak", "Travanj", "Svibanj", "Lipanj",
        "Srpanj", "Kolovoz", "Rujan", "Listopad", "Studeni", "Prosinac",
      ],
      showMonthDropdown: false,
      showYearDropdown: false,
    };
  },
  computed: {
    yearsRange() {
      const current = new Date().getFullYear();
      const years = [];
      for (let y = current - 10; y <= current + 10; y++) {
        years.push(y);
      }
      return years;
    },
    calendarDays() {
      const firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1);
      const lastDayOfMonth = new Date(this.currentYear, this.currentMonth + 1, 0);
      const daysInMonth = lastDayOfMonth.getDate();

      let startDay = firstDayOfMonth.getDay();
      startDay = startDay === 0 ? 6 : startDay - 1;

      const days = [];

      for (let i = startDay - 1; i >= 0; i--) {
        const d = new Date(this.currentYear, this.currentMonth, -i);
        days.push({ date: d, currentMonth: false, key: d.toISOString() });
      }
      for (let i = 1; i <= daysInMonth; i++) {
        const d = new Date(this.currentYear, this.currentMonth, i);
        days.push({ date: d, currentMonth: true, key: d.toISOString() });
      }
      while (days.length % 7 !== 0) {
        const lastDate = days[days.length - 1].date;
        const d = new Date(lastDate);
        d.setDate(lastDate.getDate() + 1);
        days.push({ date: d, currentMonth: false, key: d.toISOString() });
      }

      return days;
    },
    projectsOfTheDay() {
      if (!this.selectedDay) return [];
      const dateStr = this.formatDate(this.selectedDay);
      return this.allProjects.filter((p) => p.datum === dateStr);
    },
  },
  watch: {
    selectedDay(newDay) {
      if (newDay) {
        this.loadProjects();
      }
    }
  },
  mounted() {
    this.loadProjects();
    document.addEventListener("click", this.closeDropdowns);
  },
  beforeUnmount() {
    document.removeEventListener("click", this.closeDropdowns);
  },
  methods: {
    addNewProjectLocally(noviProjekt) {
      this.allProjects.push(noviProjekt);
      this.loadProjects(); 
    },
    async loadProjects() {
      try {
        const res = await axios.get("http://localhost/my_project/dohvati-projekte.php");
        if (res.data.success) {
          this.allProjects = res.data.projekti;
        }
      } catch (e) {
        console.error("Greška pri dohvatu projekata:", e);
      }
    },
    hasProject(date) {
      const dateStr = this.formatDate(date);
      return this.allProjects.some((p) => p.datum === dateStr);
    },
    selectDay(date) {
      if (this.selectedDay && this.selectedDay.toDateString() === date.toDateString()) {
        this.selectedDay = null;
      } else if (this.isCurrentMonth(date)) {
        this.selectedDay = date;
      }
    },
    isSelected(date) {
      return this.selectedDay && this.selectedDay.toDateString() === date.toDateString();
    },
    formatDate(date) {
      const y = date.getFullYear();
      const m = (date.getMonth() + 1).toString().padStart(2, "0");
      const d = date.getDate().toString().padStart(2, "0");
      return `${y}-${m}-${d}`;
    },
    prevMonth() {
      if (this.currentMonth === 0) {
        this.currentYear--;
        this.currentMonth = 11;
      } else {
        this.currentMonth--;
      }
      this.selectedDay = null;
    },
    nextMonth() {
      if (this.currentMonth === 11) {
        this.currentYear++;
        this.currentMonth = 0;
      } else {
        this.currentMonth++;
      }
      this.selectedDay = null;
    },
    toggleMonthDropdown() {
      this.showMonthDropdown = !this.showMonthDropdown;
      this.showYearDropdown = false;
    },
    toggleYearDropdown() {
      this.showYearDropdown = !this.showYearDropdown;
      this.showMonthDropdown = false;
    },
    setMonth(idx) {
      this.currentMonth = idx;
      this.showMonthDropdown = false;
      this.selectedDay = null;
    },
    setYear(year) {
      this.currentYear = year;
      this.showYearDropdown = false;
      this.selectedDay = null;
    },
    closeDropdowns(event) {
      if (!event.target.closest(".selector")) {
        this.showMonthDropdown = false;
        this.showYearDropdown = false;
      }
    },
    isCurrentMonth(date) {
      return date.getMonth() === this.currentMonth && date.getFullYear() === this.currentYear;
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

.container {
  padding: 0;
  margin: 0;
  width: 100vw;
  max-width: 100%;
}


.main-title {
  font-weight: 600;
  font-size: 3rem;
  background: linear-gradient(90deg, rgb(119, 111, 127), rgb(9, 70, 238));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  margin-bottom: 0.3rem;
  user-select: none;
}

.subtitle {
  font-size: 1.3rem;
  color: rgb(62, 82, 198);
  margin-bottom: 2.5rem;
  font-weight: 500;
  user-select: none;
}

.calendar-wrapper {
  padding: 2rem 4rem;
  max-width: none;
  width: 100%;
  background-color: #e8eaf6;
  border: 2px solid #3949ab;
}


.calendar-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.2rem;
}

.nav-btn {
  font-size: 1.8rem;
  background: #3949ab;
  border: none;
  color: white;
  font-weight: 700;
  padding: 10px 18px;
  border-radius: 0.6rem;
  cursor: pointer;
  user-select: none;
  transition: background 0.3s ease;
}

.nav-btn:hover {
  background: #1a237e;
}

.month-year-selectors {
  display: flex;
  gap: 1.5rem;
  user-select: none;
}

.selector {
  position: relative;
  font-weight: 600;
  font-size: 1.25rem;
  color: #5c6bc0;
  cursor: pointer;
  user-select: none;
  padding: 4px 10px;
  border-radius: 0.5rem;
  border: 1.8px solid transparent;
  transition: border-color 0.3s ease, background-color 0.3s ease;
  display: flex;
  align-items: center;
  gap: 6px;
}

.selector:hover,
.selector.selected {
  border-color: #3949ab;
  background-color: #dbe1f9;
}

.arrow {
  font-size: 0.75rem;
  user-select: none;
}

.dropdown {
  position: absolute;
  top: 110%;
  left: 0;
  background: white;
  border: 2px solid #3949ab;
  border-radius: 0.8rem;
  box-shadow: 0 5px 12px rgb(57 73 171 / 0.25);
  z-index: 100;
  max-height: 220px;
  overflow-y: auto;
  width: 110%;
  padding: 5px 0;
  user-select: none;
}

.dropdown li {
  padding: 8px 15px;
  cursor: pointer;
  white-space: nowrap;
  transition: background-color 0.25s ease;
}

.dropdown li:hover,
.dropdown li.selected {
  background-color: #3949ab;
  color: white;
}

.weekdays {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  margin-bottom: 10px;
}

.weekday-cell {
  text-align: center;
  font-weight: 600;
  color: #3949ab;
  padding: 8px 0;
  font-size: 1.05rem;
  user-select: none;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 12px;
}

.calendar-cell {
  background: #f3f5fc;
  border: 2px solid #3949ab;
  height: 75px;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  cursor: pointer;
  font-weight: 600;
  color: #2c3e50;
  border-radius: 12px;
  transition: background 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
  box-shadow: 0 0 5px transparent;
  font-size: 1.15rem;
}

.calendar-cell.not-current-month {
  color: #9fa8da;
  background: #dbe1f9;
  cursor: default;
  border-color: #a3a6c9;
}

.calendar-cell:hover:not(.not-current-month) {
  background: #3949ab;
  color: white;
  box-shadow: 0 0 14px #1a237e;
}

.calendar-cell.has-project .dot {
  position: absolute;
  bottom: 10px;
  right: 10px;
  width: 16px;
  height: 16px;
  background-color: #0b49f5;
  border-radius: 50%;
  box-shadow: 0 0 8px #0b49f5;
}

.calendar-cell.selected {
  background: #1a237e;
  color: white;
  border-color: #0b49f5;
  box-shadow: 0 0 18px #0b49f5;
}

.project-details {
  background: #f4f5f4;
  padding: 22px 30px;
  border-radius: 1rem;
  border: 2px solid #3949ab;
  margin-top: 2.5rem;
  box-shadow: 0 3px 12px rgb(57 73 171 / 0.25);
  user-select: none;
}

.project-details h3 {
  margin-bottom: 22px;
  font-weight: 600;
  color: #3949ab;
  letter-spacing: 0.02em;
}

.project-details ul.list-group {
  list-style: none;
  padding-left: 0;
  margin: 0;
}

.project-details li.list-group-item {
  margin-bottom: 16px;
  font-size: 1.1rem;
  background: white;
  padding: 14px 20px;
  border-radius: 10px;
  border: 1.5px solid #c1c1c1;
  color: #2c3e50;
  box-shadow: 0 2px 7px rgb(0 0 0 / 0.07);
  transition: box-shadow 0.3s ease;
}

.project-details li.list-group-item:hover {
  box-shadow: 0 4px 12px rgb(9 70 238 / 0.4);
}
</style>
