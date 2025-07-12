import axios from 'axios'
import router from './router'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
})

// Intercepteur pour ajouter le token JWT à chaque requête
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

// Intercepteur pour gérer les erreurs de réponse, notamment 401 (non autorisé)
api.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.status === 401) {
      // Token invalide ou expiré
      localStorage.removeItem('token')
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

export default api
