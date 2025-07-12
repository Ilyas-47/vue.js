import { defineStore } from 'pinia'
import api from '../axios'

export const useTaskStore = defineStore('tasks', {
  state: () => ({
    tasks: [],
  }),
  actions: {
    async fetchTasks() {
      try {
        const response = await api.get('/tasks')
        this.tasks = response.data
      } catch (error) {
        console.error('Erreur chargement tâches:', error)
      }
    },

    async fetchTask(id) {
      try {
        const response = await api.get(`/tasks/${id}`)
        return response.data
      } catch (error) {
        console.error(`Erreur chargement tâche ${id}:`, error)
        throw error
      }
    },

    async createTask(taskData) {
      try {
        const response = await api.post('/tasks', taskData)
        this.tasks.push(response.data)
      } catch (error) {
        console.error('Erreur création tâche:', error)
        throw error
      }
    },

    async updateTask(id, taskData) {
      try {
        const response = await api.put(`/tasks/${id}`, taskData)
        const index = this.tasks.findIndex(task => task.id === id)
        if (index !== -1) this.tasks[index] = response.data
      } catch (error) {
        console.error(`Erreur mise à jour tâche ${id}:`, error)
        throw error
      }
    },

    async deleteTask(id) {
      try {
        await api.delete(`/tasks/${id}`)
        this.tasks = this.tasks.filter(task => task.id !== id)
      } catch (error) {
        console.error(`Erreur suppression tâche ${id}:`, error)
        throw error
      }
    },
  }
})

