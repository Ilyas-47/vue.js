import { defineStore } from 'pinia'
import api from '../axios'
import router from '../router'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async register(formData) {
  try {
    const response = await api.post('/auth/register', formData);
    this.token = response.data.token
    localStorage.setItem('token', this.token)
    router.push('/tasks')
  } catch (error) {
    console.error('Erreur inscription:', error.response?.data || error.message)
    alert('Erreur lors de l\'inscription. Veuillez vérifier vos données.')
    throw error
  }
},

    async login(credentials) {
      try {
        const response = await api.post('/auth/login', credentials)
        this.token = response.data.token
        localStorage.setItem('token', this.token)
        router.push('/tasks')
      } catch (error) {
        console.error('Erreur connexion:', error)
        throw error
      }
    },

    logout() {
      this.token = null
      this.user = null
      localStorage.removeItem('token')
      router.push('/login')
    }
  }
})
