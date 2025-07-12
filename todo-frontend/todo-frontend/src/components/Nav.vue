<template>
  <nav class="main-nav">
    <router-link to="/tasks" active-class="active-link">Tâches</router-link>
    <router-link to="/notifications" active-class="active-link">Notifications</router-link>
    <router-link to="/profile" active-class="active-link">Profil</router-link>
    <router-link to="/login" class="logout-link" active-class="active-link">Déconnexion</router-link>
  </nav>
</template>

<script setup>
// Rien à ajouter ici pour l'instant
</script>

<style scoped>
.main-nav {
  background: #007bff;
  padding: 1rem;
  display: flex;
  justify-content: center;
  gap: 2rem;
  flex-wrap: wrap;
}

.main-nav a {
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  transition: background 0.2s;
  font-weight: bold;
}

.main-nav a:hover,
.main-nav a.active-link {
  background: #0056b3;
}

.logout-link {
  background: #dc3545;
}

.logout-link:hover {
  background: #b52a37;
}
</style>
