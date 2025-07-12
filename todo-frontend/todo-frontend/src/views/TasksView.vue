<template>
  <div class="task-view">
    <!-- En-tête -->
    <div class="header">
      <h1>Gestion des Tâches</h1>
      <div class="header-actions">
        <button @click="showAddModal = true" class="btn btn-primary">
          <i class="icon">+</i>
          Nouvelle Tâche
        </button>
        <div class="filter-controls">
          <select v-model="filterStatus" class="filter-select">
            <option value="">Tous les statuts</option>
            <option value="pending">En attente</option>
            <option value="in-progress">En cours</option>
            <option value="completed">Terminé</option>
          </select>
          <select v-model="filterPriority" class="filter-select">
            <option value="">Toutes les priorités</option>
            <option value="low">Faible</option>
            <option value="medium">Moyenne</option>
            <option value="high">Élevée</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="stats">
      <div class="stat-card">
        <div class="stat-number">{{ totalTasks }}</div>
        <div class="stat-label">Total</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ completedTasks }}</div>
        <div class="stat-label">Terminées</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ pendingTasks }}</div>
        <div class="stat-label">En attente</div>
      </div>
      <div class="stat-card">
        <div class="stat-number">{{ inProgressTasks }}</div>
        <div class="stat-label">En cours</div>
      </div>
    </div>

    <!-- Liste des tâches -->
    <div class="task-list">
      <div 
        v-for="task in filteredTasks" 
        :key="task.id" 
        class="task-card"
        :class="{ 'completed': task.status === 'completed' }"
      >
        <div class="task-header">
          <h3 class="task-title">{{ task.title }}</h3>
          <div class="task-actions">
            <button @click="editTask(task)" class="btn btn-sm btn-secondary">
              Modifier
            </button>
            <button @click="deleteTask(task.id)" class="btn btn-sm btn-danger">
              Supprimer
            </button>
          </div>
        </div>
        
        <p class="task-description">{{ task.description }}</p>
        
        <div class="task-meta">
          <span class="task-status" :class="task.status">
            {{ getStatusLabel(task.status) }}
          </span>
          <span class="task-priority" :class="task.priority">
            {{ getPriorityLabel(task.priority) }}
          </span>
          <span class="task-date">
            Échéance: {{ formatDate(task.dueDate) }}
          </span>
        </div>
        
        <div class="task-progress">
          <div class="progress-bar">
            <div 
              class="progress-fill" 
              :style="{ width: task.progress + '%' }"
            ></div>
          </div>
          <span class="progress-text">{{ task.progress }}%</span>
        </div>
      </div>
    </div>

    <!-- Modal d'ajout/modification -->
    <div v-if="showAddModal || editingTask" class="modal-overlay" @click="closeModal">
      <div class="modal" @click.stop>
        <div class="modal-header">
          <h2>{{ editingTask ? 'Modifier la tâche' : 'Nouvelle tâche' }}</h2>
          <button @click="closeModal" class="btn btn-close">&times;</button>
        </div>
        
        <form @submit.prevent="saveTask" class="modal-body">
          <div class="form-group">
            <label for="title">Titre</label>
            <input 
              v-model="taskForm.title" 
              type="text" 
              id="title" 
              required
              class="form-input"
            >
          </div>
          
          <div class="form-group">
            <label for="description">Description</label>
            <textarea 
              v-model="taskForm.description" 
              id="description"
              rows="3"
              class="form-textarea"
            ></textarea>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="status">Statut</label>
              <select v-model="taskForm.status" id="status" class="form-select">
                <option value="pending">En attente</option>
                <option value="in-progress">En cours</option>
                <option value="completed">Terminé</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="priority">Priorité</label>
              <select v-model="taskForm.priority" id="priority" class="form-select">
                <option value="low">Faible</option>
                <option value="medium">Moyenne</option>
                <option value="high">Élevée</option>
              </select>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="dueDate">Date d'échéance</label>
              <input 
                v-model="taskForm.dueDate" 
                type="date" 
                id="dueDate"
                class="form-input"
              >
            </div>
            
            <div class="form-group">
              <label for="progress">Progression (%)</label>
              <input 
                v-model="taskForm.progress" 
                type="number" 
                id="progress"
                min="0"
                max="100"
                class="form-input"
              >
            </div>
          </div>
          
          <div class="modal-footer">
            <button type="button" @click="closeModal" class="btn btn-secondary">
              Annuler
            </button>
            <button type="submit" class="btn btn-primary">
              {{ editingTask ? 'Modifier' : 'Créer' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import '../assets/style/tasks.css'
export default {
  name: 'TaskView',
  data() {
    return {
      tasks: [
        {
          id: 1,
          title: 'Développer l\'interface utilisateur',
          description: 'Créer les composants Vue.js pour l\'interface principale',
          status: 'in-progress',
          priority: 'high',
          dueDate: '2024-12-31',
          progress: 60,
          createdAt: new Date('2024-01-15')
        },
        {
          id: 2,
          title: 'Tester les fonctionnalités',
          description: 'Effectuer les tests unitaires et d\'intégration',
          status: 'pending',
          priority: 'medium',
          dueDate: '2024-12-20',
          progress: 0,
          createdAt: new Date('2024-01-20')
        },
        {
          id: 3,
          title: 'Déployer en production',
          description: 'Mettre en ligne la version finale',
          status: 'completed',
          priority: 'high',
          dueDate: '2024-11-30',
          progress: 100,
          createdAt: new Date('2024-01-10')
        }
      ],
      showAddModal: false,
      editingTask: null,
      filterStatus: '',
      filterPriority: '',
      taskForm: {
        title: '',
        description: '',
        status: 'pending',
        priority: 'medium',
        dueDate: '',
        progress: 0
      }
    }
  },
  computed: {
    filteredTasks() {
      return this.tasks.filter(task => {
        const statusMatch = !this.filterStatus || task.status === this.filterStatus;
        const priorityMatch = !this.filterPriority || task.priority === this.filterPriority;
        return statusMatch && priorityMatch;
      });
    },
    totalTasks() {
      return this.tasks.length;
    },
    completedTasks() {
      return this.tasks.filter(task => task.status === 'completed').length;
    },
    pendingTasks() {
      return this.tasks.filter(task => task.status === 'pending').length;
    },
    inProgressTasks() {
      return this.tasks.filter(task => task.status === 'in-progress').length;
    }
  },
  methods: {
    saveTask() {
      if (this.editingTask) {
        // Modification
        const index = this.tasks.findIndex(t => t.id === this.editingTask.id);
        this.tasks[index] = { ...this.editingTask, ...this.taskForm };
      } else {
        // Création
        const newTask = {
          id: Date.now(),
          ...this.taskForm,
          createdAt: new Date()
        };
        this.tasks.push(newTask);
      }
      this.closeModal();
    },
    editTask(task) {
      this.editingTask = task;
      this.taskForm = { ...task };
    },
    deleteTask(taskId) {
      if (confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
        this.tasks = this.tasks.filter(task => task.id !== taskId);
      }
    },
    closeModal() {
      this.showAddModal = false;
      this.editingTask = null;
      this.taskForm = {
        title: '',
        description: '',
        status: 'pending',
        priority: 'medium',
        dueDate: '',
        progress: 0
      };
    },
    getStatusLabel(status) {
      const labels = {
        pending: 'En attente',
        'in-progress': 'En cours',
        completed: 'Terminé'
      };
      return labels[status] || status;
    },
    getPriorityLabel(priority) {
      const labels = {
        low: 'Faible',
        medium: 'Moyenne',
        high: 'Élevée'
      };
      return labels[priority] || priority;
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('fr-FR');
    }
  }
}
</script>
