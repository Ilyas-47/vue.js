<template>
  <div class="notfound-container">
    <div class="notfound-content">
      <div class="error-code">
        <h1>404</h1>
      </div>
      
      <div class="error-message">
        <h2>Page non trouvée</h2>
        <p>Désolé, la page que vous recherchez n'existe pas ou a été déplacée.</p>
      </div>



      <div class="error-actions">
        <router-link to="/" class="btn-primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
            <polyline points="9,22 9,12 15,12 15,22"></polyline>
          </svg>
          Retour à l'accueil
        </router-link>
        
        <button @click="goBack" class="btn-secondary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5"></path>
            <polyline points="12,19 5,12 12,5"></polyline>
          </svg>
          Retour en arrière
        </button>
      </div>


    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import '../assets/style/notfound.css'

const router = useRouter()

function goBack() {
  // Vérifier s'il y a un historique
  if (window.history.length > 1) {
    router.go(-1)
  } else {
    // Rediriger vers l'accueil si pas d'historique
    router.push('/')
  }
}
</script>

