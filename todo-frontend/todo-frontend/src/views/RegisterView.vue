<template>
  <div class="auth-container">
    <div class="auth-form">
      <h2>Créer un compte</h2>
      <form @submit.prevent="submit">
        <div class="form-group">
          <label for="name">Nom complet</label>
          <input 
            id="name"
            type="text" 
            v-model="form.name" 
            placeholder="Entrez votre nom complet"
            required 
          />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input 
            id="email"
            type="email" 
            v-model="form.email" 
            placeholder="votre@email.com"
            required 
          />
        <div class="form-group">
          <label for="phone">Téléphone</label>
          <input 
            id="phone"
            type="tel" 
            v-model="form.phone" 
            placeholder="+212 6 12 34 56 78"
            required
          />
          </div>
        <div class="form-group">
          <label for="address">Adresse</label>
          <input 
            id="address"
            type="text" 
            v-model="form.address" 
            placeholder="Votre adresse complète"
            required
          />
        </div>
        <div class="form-group">
          <label for="image">Image de profil</label>
          <input 
            id="image"
            type="file"
            @change="handleFileUpload"
            accept="image/*"
            required
          />
        </div>
        </div>
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input 
            id="password"
            type="password" 
            v-model="form.password" 
            placeholder="Minimum 8 caractères"
            required 
          />
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirmer le mot de passe</label>
          <input 
            id="password_confirmation"
            type="password" 
            v-model="form.password_confirmation" 
            placeholder="Confirmer votre mot de passe"
            required 
          />
        </div>
        <div class="form-actions">
          <button type="submit" :disabled="isSubmitting">
            <span v-if="!isSubmitting">S'inscrire</span>
            <span v-else>Inscription en cours...</span>
          </button>
        </div>
      </form>
      <div class="link-block">
        <p>
          Déjà inscrit ?
          <router-link to="/login">Se connecter</router-link>
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useAuthStore } from '../store/auth'
import '../assets/style/auth.css'

const auth = useAuthStore()

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const isSubmitting = ref(false)

async function submit() {
  if (form.password !== form.password_confirmation) {
    alert('Les mots de passe ne correspondent pas')
    return
  }

  if (form.password.length < 8) {
    alert('Le mot de passe doit contenir au moins 8 caractères')
    return
  }

  isSubmitting.value = true
  
  try {
    await auth.register(form)
  } catch (err) {
    alert('Erreur lors de l\'inscription. Veuillez réessayer.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

