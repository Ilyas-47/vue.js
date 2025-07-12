
<template>
  <div class="auth-container">
    <div class="auth-form">
      <h2>Connexion</h2>
      <form @submit.prevent="submit">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            id="email"
            type="email"
            v-model="email"
            placeholder="votre@email.com"
            required
          />
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input
            id="password"
            type="password"
            v-model="password"
            placeholder="Votre mot de passe"
            required
          />
        </div>
        <div class="form-actions">
          <button type="submit" :disabled="isSubmitting">
            <span v-if="!isSubmitting">Se connecter</span>
            <span v-else>Connexion...</span>
          </button>
        </div>
      </form>
      <div class="link-block">
        <p>
          Pas encore de compte ?
          <router-link to="/register">Créer un compte</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../store/auth'
import '../assets/style/auth.css'
import Nav from '../components/Nav.vue'

const auth = useAuthStore()
const email = ref('')
const password = ref('')
const isSubmitting = ref(false)

async function submit() {
  isSubmitting.value = true
  try {
    await auth.login({ email: email.value, password: password.value })
  } catch (err) {
    alert('Identifiants invalides.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

