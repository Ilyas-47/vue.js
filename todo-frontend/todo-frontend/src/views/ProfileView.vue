<template>
  <div class="profile-container">
    <div class="profile-header">
      <h2>Mon profil</h2>
      <div class="profile-actions">
        <button 
          v-if="!isEditing" 
          @click="startEditing"
          class="btn btn-primary"
        >
          <i class="fas fa-edit"></i> Modifier
        </button>
        <div v-else class="edit-actions">
          <button 
            @click="saveProfile"
            :disabled="saving"
            class="btn btn-success"
          >
            <i class="fas fa-save"></i> 
            {{ saving ? 'Sauvegarde...' : 'Sauvegarder' }}
          </button>
          <button 
            @click="cancelEditing"
            :disabled="saving"
            class="btn btn-secondary"
          >
            <i class="fas fa-times"></i> Annuler
          </button>
        </div>
      </div>
    </div>

    <!-- Loading state -->
    <div v-if="loading" class="loading">
      <i class="fas fa-spinner fa-spin"></i>
      Chargement du profil...
    </div>

    <!-- Error state -->
    <div v-else-if="error" class="error-message">
      <i class="fas fa-exclamation-triangle"></i>
      {{ error }}
      <button @click="loadProfile" class="btn btn-link">Réessayer</button>
    </div>

    <!-- Profile content -->
    <div v-else class="profile-content">
      <!-- Avatar section -->
      <div class="avatar-section">
        <div class="avatar-container">
          <img 
            :src="user.avatar || defaultAvatar" 
            :alt="user.name"
            class="avatar"
          >
          <div v-if="isEditing" class="avatar-overlay">
            <input 
              type="file" 
              ref="avatarInput"
              accept="image/*"
              @change="handleAvatarChange"
              class="hidden"
            >
            <button 
              @click="$refs.avatarInput.click()"
              class="btn-avatar-change"
            >
              <i class="fas fa-camera"></i>
            </button>
          </div>
        </div>
        <div class="user-info">
          <h3>{{ user.name }}</h3>
          <p class="user-role">{{ user.role }}</p>
          <p class="user-status">
            <span :class="['status-indicator', user.status]"></span>
            {{ getUserStatus(user.status) }}
          </p>
        </div>
      </div>

      <!-- Profile form -->
      <form @submit.prevent="saveProfile" class="profile-form">
        <div class="form-sections">
          <!-- Informations personnelles -->
          <div class="form-section">
            <h4>Informations personnelles</h4>
            <div class="form-grid">
              <div class="form-group">
                <label for="name">Nom complet *</label>
                <input 
                  id="name"
                  v-model="editedUser.name"
                  :readonly="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                  type="text"
                  required
                >
                <div v-if="errors.name" class="error-text">{{ errors.name }}</div>
              </div>

              <div class="form-group">
                <label for="email">Email *</label>
                <input 
                  id="email"
                  v-model="editedUser.email"
                  :readonly="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                  type="email"
                  required
                >
                <div v-if="errors.email" class="error-text">{{ errors.email }}</div>
              </div>

              <div class="form-group">
                <label for="phone">Téléphone</label>
                <input 
                  id="phone"
                  v-model="editedUser.phone"
                  :readonly="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                  type="tel"
                >
              </div>

              <div class="form-group">
                <label for="job_title">Poste</label>
                <input 
                  id="job_title"
                  v-model="editedUser.job_title"
                  :readonly="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                  type="text"
                >
              </div>

              <div class="form-group">
                <label for="department">Département</label>
                <select 
                  id="department"
                  v-model="editedUser.department"
                  :disabled="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                >
                  <option value="">Sélectionner un département</option>
                  <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                    {{ dept.name }}
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label for="location">Localisation</label>
                <input 
                  id="location"
                  v-model="editedUser.location"
                  :readonly="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                  type="text"
                >
              </div>
            </div>
          </div>

          <!-- Informations additionnelles -->
          <div class="form-section">
            <h4>Informations additionnelles</h4>
            <div class="form-grid">
              <div class="form-group full-width">
                <label for="bio">Bio</label>
                <textarea 
                  id="bio"
                  v-model="editedUser.bio"
                  :readonly="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                  rows="3"
                  maxlength="500"
                  placeholder="Parlez-nous de vous..."
                ></textarea>
                <div class="char-counter">
                  {{ (editedUser.bio || '').length }}/500
                </div>
              </div>

              <div class="form-group">
                <label for="timezone">Fuseau horaire</label>
                <select 
                  id="timezone"
                  v-model="editedUser.timezone"
                  :disabled="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                >
                  <option v-for="tz in timezones" :key="tz.value" :value="tz.value">
                    {{ tz.label }}
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label for="language">Langue</label>
                <select 
                  id="language"
                  v-model="editedUser.language"
                  :disabled="!isEditing"
                  :class="['form-control', { 'readonly': !isEditing }]"
                >
                  <option value="fr">Français</option>
                  <option value="en">English</option>
                  <option value="es">Español</option>
                  <option value="de">Deutsch</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Préférences -->
          <div class="form-section">
            <h4>Préférences</h4>
            <div class="preferences-grid">
              <div class="preference-item">
                <label class="checkbox-label">
                  <input 
                    type="checkbox"
                    v-model="editedUser.preferences.email_notifications"
                    :disabled="!isEditing"
                  >
                  <span class="checkmark"></span>
                  Notifications par email
                </label>
              </div>

              <div class="preference-item">
                <label class="checkbox-label">
                  <input 
                    type="checkbox"
                    v-model="editedUser.preferences.push_notifications"
                    :disabled="!isEditing"
                  >
                  <span class="checkmark"></span>
                  Notifications push
                </label>
              </div>

              <div class="preference-item">
                <label class="checkbox-label">
                  <input 
                    type="checkbox"
                    v-model="editedUser.preferences.weekly_digest"
                    :disabled="!isEditing"
                  >
                  <span class="checkmark"></span>
                  Résumé hebdomadaire
                </label>
              </div>

              <div class="preference-item">
                <label class="checkbox-label">
                  <input 
                    type="checkbox"
                    v-model="editedUser.preferences.task_reminders"
                    :disabled="!isEditing"
                  >
                  <span class="checkmark"></span>
                  Rappels de tâches
                </label>
              </div>
            </div>
          </div>

          <!-- Sécurité -->
          <div class="form-section">
            <h4>Sécurité</h4>
            <div class="security-info">
              <div class="security-item">
                <div class="security-label">
                  <i class="fas fa-shield-alt"></i>
                  Authentification à deux facteurs
                </div>
                <div class="security-value">
                  <span :class="['status-badge', user.two_factor_enabled ? 'enabled' : 'disabled']">
                    {{ user.two_factor_enabled ? 'Activée' : 'Désactivée' }}
                  </span>
                  <button 
                    @click="toggle2FA"
                    class="btn btn-sm btn-outline"
                  >
                    {{ user.two_factor_enabled ? 'Désactiver' : 'Activer' }}
                  </button>
                </div>
              </div>

              <div class="security-item">
                <div class="security-label">
                  <i class="fas fa-key"></i>
                  Mot de passe
                </div>
                <div class="security-value">
                  <span class="last-updated">
                    Modifié le {{ formatDate(user.password_updated_at) }}
                  </span>
                  <button 
                    @click="changePassword"
                    class="btn btn-sm btn-outline"
                  >
                    Changer
                  </button>
                </div>
              </div>

              <div class="security-item">
                <div class="security-label">
                  <i class="fas fa-history"></i>
                  Dernière connexion
                </div>
                <div class="security-value">
                  <span>{{ formatDate(user.last_login_at) }}</span>
                  <span class="ip-address">{{ user.last_login_ip }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Success message -->
    <div v-if="successMessage" class="success-message">
      <i class="fas fa-check-circle"></i>
      {{ successMessage }}
    </div>

    <!-- Password change modal -->
    <div v-if="showPasswordModal" class="modal-overlay" @click="closePasswordModal">
      <div class="modal" @click.stop>
        <div class="modal-header">
          <h3>Changer le mot de passe</h3>
          <button @click="closePasswordModal" class="btn-close">×</button>
        </div>
        <form @submit.prevent="submitPasswordChange" class="modal-body">
          <div class="form-group">
            <label for="current_password">Mot de passe actuel</label>
            <input 
              id="current_password"
              v-model="passwordForm.current_password"
              type="password"
              class="form-control"
              required
            >
          </div>
          <div class="form-group">
            <label for="new_password">Nouveau mot de passe</label>
            <input 
              id="new_password"
              v-model="passwordForm.new_password"
              type="password"
              class="form-control"
              required
              minlength="8"
            >
          </div>
          <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input 
              id="confirm_password"
              v-model="passwordForm.confirm_password"
              type="password"
              class="form-control"
              required
            >
          </div>
          <div class="modal-footer">
            <button type="button" @click="closePasswordModal" class="btn btn-secondary">
              Annuler
            </button>
            <button type="submit" :disabled="passwordChanging" class="btn btn-primary">
              {{ passwordChanging ? 'Changement...' : 'Changer' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'

// États réactifs
const user = ref({})
const editedUser = ref({})
const loading = ref(true)
const saving = ref(false)
const isEditing = ref(false)
const error = ref(null)
const successMessage = ref('')
const departments = ref([])
const showPasswordModal = ref(false)
const passwordChanging = ref(false)

// Formulaire de changement de mot de passe
const passwordForm = reactive({
  current_password: '',
  new_password: '',
  confirm_password: ''
})

// Erreurs de validation
const errors = ref({})

// Avatar par défaut
const defaultAvatar = 'https://via.placeholder.com/150x150?text=User'

// Fuseaux horaires
const timezones = ref([
  { value: 'Europe/Paris', label: 'Europe/Paris (GMT+1)' },
  { value: 'Europe/London', label: 'Europe/London (GMT+0)' },
  { value: 'America/New_York', label: 'America/New_York (GMT-5)' },
  { value: 'America/Los_Angeles', label: 'America/Los_Angeles (GMT-8)' },
  { value: 'Asia/Tokyo', label: 'Asia/Tokyo (GMT+9)' }
])

// Computed
const hasChanges = computed(() => {
  return JSON.stringify(user.value) !== JSON.stringify(editedUser.value)
})

// Méthodes
const loadProfile = async () => {
  try {
    loading.value = true
    error.value = null
    
    const response = await fetch('/api/user/profile', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (!response.ok) {
      throw new Error('Erreur lors du chargement du profil')
    }
    
    const data = await response.json()
    user.value = {
      ...data.user,
      preferences: data.user.preferences || {
        email_notifications: true,
        push_notifications: true,
        weekly_digest: false,
        task_reminders: true
      }
    }
    editedUser.value = JSON.parse(JSON.stringify(user.value))
    
    // Charger les départements
    await loadDepartments()
    
  } catch (err) {
    console.error('Erreur lors du chargement du profil:', err)
    error.value = 'Impossible de charger le profil utilisateur'
    
    // Données d'exemple en cas d'erreur
    user.value = {
      id: 1,
      name: 'Jean Dupont',
      email: 'jean.dupont@example.com',
      phone: '+33 1 23 45 67 89',
      job_title: 'Développeur Senior',
      department: 1,
      location: 'Paris, France',
      bio: 'Développeur passionné avec 5 ans d\'expérience en Vue.js et Laravel.',
      timezone: 'Europe/Paris',
      language: 'fr',
      avatar: null,
      role: 'Développeur',
      status: 'active',
      two_factor_enabled: false,
      password_updated_at: '2024-01-15T10:30:00Z',
      last_login_at: '2024-01-20T14:22:00Z',
      last_login_ip: '192.168.1.100',
      preferences: {
        email_notifications: true,
        push_notifications: true,
        weekly_digest: false,
        task_reminders: true
      }
    }
    editedUser.value = JSON.parse(JSON.stringify(user.value))
  } finally {
    loading.value = false
  }
}

const loadDepartments = async () => {
  try {
    const response = await fetch('/api/departments', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'application/json'
      }
    })
    
    if (response.ok) {
      const data = await response.json()
      departments.value = data.departments
    } else {
      // Départements d'exemple
      departments.value = [
        { id: 1, name: 'Développement' },
        { id: 2, name: 'Marketing' },
        { id: 3, name: 'Ventes' },
        { id: 4, name: 'Support' },
        { id: 5, name: 'Ressources Humaines' }
      ]
    }
  } catch (err) {
    console.error('Erreur lors du chargement des départements:', err)
    departments.value = []
  }
}

const startEditing = () => {
  isEditing.value = true
  errors.value = {}
}

const cancelEditing = () => {
  if (hasChanges.value) {
    if (confirm('Vous avez des modifications non sauvegardées. Voulez-vous vraiment annuler ?')) {
      editedUser.value = JSON.parse(JSON.stringify(user.value))
      isEditing.value = false
      errors.value = {}
    }
  } else {
    isEditing.value = false
    errors.value = {}
  }
}

const validateForm = () => {
  errors.value = {}
  
  if (!editedUser.value.name?.trim()) {
    errors.value.name = 'Le nom est requis'
  }
  
  if (!editedUser.value.email?.trim()) {
    errors.value.email = 'L\'email est requis'
  } else if (!/\S+@\S+\.\S+/.test(editedUser.value.email)) {
    errors.value.email = 'L\'email n\'est pas valide'
  }
  
  return Object.keys(errors.value).length === 0
}

const saveProfile = async () => {
  if (!validateForm()) {
    return
  }
  
  try {
    saving.value = true
    error.value = null
    
    const response = await fetch('/api/user/profile', {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(editedUser.value)
    })
    
    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.message || 'Erreur lors de la sauvegarde')
    }
    
    const data = await response.json()
    user.value = data.user
    editedUser.value = JSON.parse(JSON.stringify(user.value))
    isEditing.value = false
    
    successMessage.value = 'Profil mis à jour avec succès'
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
    
  } catch (err) {
    console.error('Erreur lors de la sauvegarde:', err)
    error.value = err.message || 'Erreur lors de la sauvegarde du profil'
  } finally {
    saving.value = false
  }
}

const handleAvatarChange = async (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  if (file.size > 5 * 1024 * 1024) { // 5MB
    alert('La taille du fichier ne peut pas dépasser 5MB')
    return
  }
  
  const formData = new FormData()
  formData.append('avatar', file)
  
  try {
    const response = await fetch('/api/user/avatar', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
      },
      body: formData
    })
    
    if (!response.ok) {
      throw new Error('Erreur lors de l\'upload de l\'avatar')
    }
    
    const data = await response.json()
    editedUser.value.avatar = data.avatar_url
    
  } catch (err) {
    console.error('Erreur lors de l\'upload de l\'avatar:', err)
    alert('Erreur lors de l\'upload de l\'avatar')
  }
}

const changePassword = () => {
  showPasswordModal.value = true
  passwordForm.current_password = ''
  passwordForm.new_password = ''
  passwordForm.confirm_password = ''
}

const closePasswordModal = () => {
  showPasswordModal.value = false
}

const submitPasswordChange = async () => {
  if (passwordForm.new_password !== passwordForm.confirm_password) {
    alert('Les mots de passe ne correspondent pas')
    return
  }
  
  try {
    passwordChanging.value = true
    
    const response = await fetch('/api/user/password', {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        current_password: passwordForm.current_password,
        new_password: passwordForm.new_password
      })
    })
    
    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.message || 'Erreur lors du changement de mot de passe')
    }
    
    closePasswordModal()
    successMessage.value = 'Mot de passe changé avec succès'
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
    
  } catch (err) {
    console.error('Erreur lors du changement de mot de passe:', err)
    alert(err.message || 'Erreur lors du changement de mot de passe')
  } finally {
    passwordChanging.value = false
  }
}

const toggle2FA = async () => {
  try {
    const response = await fetch('/api/user/two-factor', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('auth_token')}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        enabled: !user.value.two_factor_enabled
      })
    })
    
    if (!response.ok) {
      throw new Error('Erreur lors de la modification de l\'authentification 2FA')
    }
    
    user.value.two_factor_enabled = !user.value.two_factor_enabled
    successMessage.value = `Authentification 2FA ${user.value.two_factor_enabled ? 'activée' : 'désactivée'}`
    setTimeout(() => {
      successMessage.value = ''
    }, 3000)
    
  } catch (err) {
    console.error('Erreur lors de la modification 2FA:', err)
    alert('Erreur lors de la modification de l\'authentification 2FA')
  }
}

const getUserStatus = (status) => {
  const statuses = {
    'active': 'Actif',
    'inactive': 'Inactif',
    'pending': 'En attente',
    'suspended': 'Suspendu'
  }
  return statuses[status] || status
}

const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  
  const date = new Date(dateString)
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Lifecycle hooks
onMounted(() => {
  loadProfile()
})
</script>

<style scoped>
.profile-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.profile-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 2px solid #e0e0e0;
}

.profile-header h2 {
  margin: 0;
  color: #333;
}

.profile-actions {
  display: flex;
  gap: 10px;
}

.edit-actions {
  display: flex;
  gap: 10px;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background-color: #0056b3;
}

.btn-success {
  background-color: #28a745;
  color: white;
}

.btn-success:hover:not(:disabled) {
  background-color: #1e7e34;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-secondary:hover:not(:disabled) {
  background-color: #545b62;
}

.btn-outline {
  background-color: transparent;
  color: #007bff;
  border: 1px solid #007bff;
}

.btn-outline:hover {
  background-color: #007bff;
  color: white;
}

.btn-sm {
  padding: 6px 12px;
  font-size: 12px;
}

.btn-link {
  background: none;
  color: #007bff;
  text-decoration: underline;
}

.loading {
  text-align: center;
  padding: 60px 20px;
  color: #666;
  font-size: 16px;
}

.error-message {
  background-color: #f8d7da;
  color: #721c24;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.success-message {
  background-color: #d4edda;
  color: #155724;
  padding: 15px;
  border-radius: 6px;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.avatar-section {
  display: flex;
  align-items: center;
  gap: 30px;
  margin-bottom: 40px;
  padding: 30px;
  background-color: #f8f9fa;
  border-radius: 12px;
}

.avatar-container {
  position: relative;
}

.avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.avatar-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.avatar-container:hover .avatar-overlay {
  opacity: 1;
}

.btn-avatar-change {
  background: none;
  border: none;
  color: white;
  font-size: 24px;
  cursor: pointer;
}

.hidden {
  display: none;
}

.user-info h3 {
  margin: 0 0 10px 0;
  color: #333;
  font-size: 28px;
}

.user-role {
  color: #666;
  font-size: 16px;
  margin: 0 0 10px 0;
}

.user-status {
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0;
}

.status-indicator {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.status-indicator.active {
  background-color: #28a745;
}

.status-indicator.inactive {
  background-color: #dc3545;
}

.status-indicator.pending {
  background-color: #ffc107;
}

.profile-form {
  background-color: white;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
}

.form-sections {
  display: flex;
  flex-direction: column;
  gap: 40px;
}

.form-section {
  margin-bottom: 30px;
}

.form-section h4 {
  margin: 0 0 20px 0;
  color: #333;
  font-size: 22px;
  position: relative;
}

.form-section h4:after {
  content: '';
  position: absolute;
  width: 100%;
  height: 2px;
  bottom: -5px;
  left: 0;
  background: linear-gradient(to right, #007bff, #28a745);
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-weight: 500;
  color: #333;
}

.form-control {
  padding: 10px;
  border: 1px solid #ced4da;
  border-radius: 6px;
  font-size: 14px;
  color: #495057;
  transition: border-color 0.3s ease;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
}

.readonly {
  background-color: #f1f1f1;
  color: #666;
}

.error-text {
  color: #dc3545;
  font-size: 12px;
  margin-top: -5px;
}

.char-counter {
  font-size: 12px;
  color: #666;
  text-align: right;
}

.preferences-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 15px;
}

.preference-item {
  display: flex;
  align-items: center;
  gap: 10px;
}

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
}

.checkmark {
  width: 16px;
  height: 16px;
  border: 2px solid #007bff;
  border-radius: 4px;
  position: relative;
  flex-shrink: 0;
}

.checkmark:after {
  content: '';
  position: absolute;
  width: 8px;
  height: 8px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0);
  background-color: #007bff;
  border-radius: 2px;
  transition: transform 0.3s ease;
}

input[type="checkbox"]:checked + .checkmark:after {
  transform: translate(-50%, -50%) scale(1);
}

.security-info {
  background-color: #f8f9fa;
  border-radius: 12px;
  padding: 20px;
  display: grid;
  grid-template-columns: 1fr;
  gap: 15px;
}

.security-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
  border-radius: 6px;
  transition: background-color 0.3s ease;
}

.security-item:hover {
  background-color: #e9ecef;
}

.security-label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-weight: 500;
  color: #333;
}

.security-value {
  display: flex;
  align-items: center;
  gap: 10px;
}

.last-updated {
  font-size: 12px;
  color: #666;
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal {
  background-color: white;
  border-radius: 12px;
  padding: 20px;
  max-width: 500px;
  width: 100%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
  color: #333;
}

.btn-close {
  background: none;
  border: none;
  color: #333;
  font-size: 18px;
  cursor: pointer;
}

.modal-body {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}
</style>