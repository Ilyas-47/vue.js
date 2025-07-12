<template>
  <div class="notification-container">
    <h2>Notifications</h2>
    <div v-if="notifications.length === 0" class="notification">
      Aucune notification pour le moment.
    </div>
    <div v-for="notif in notifications" :key="notif.id" class="notification">
      {{ notif.message }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
// import Echo from 'laravel-echo'
// import Pusher from 'pusher-js'

const notifications = ref([])

// Exemple d'intégration Echo/Pusher (à adapter selon votre config backend)
/*
window.Pusher = Pusher
const echo = new Echo({
  broadcaster: 'pusher',
  key: 'VOTRE_PUSHER_KEY',
  cluster: 'VOTRE_PUSHER_CLUSTER',
  forceTLS: true
})

onMounted(() => {
  echo.channel('events')
    .listen('TaskCreated', (e) => {
      notifications.value.unshift({
        id: Date.now(),
        message: e.message || 'Nouvelle tâche créée !'
      })
    })
})
*/

// Pour test sans backend temps réel :
onMounted(() => {
  // notifications.value.push({ id: 1, message: 'Nouvelle tâche créée !' })
})
</script>
