import { createRouter, createWebHistory } from 'vue-router'
import RegisterView from '../views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'
import TasksView from '../views/TasksView.vue'
import NotificationsView from '../views/NotificationsView.vue'
import ProfileView from '../views/ProfileView.vue'
import NotFound from '../views/NotFound.vue'


const routes = [
  { path: '/register', component: RegisterView },
  { path: '/login', component: LoginView },
  { path: '/tasks', component: TasksView },
  { path: '/notifications', component: NotificationsView },
  { path: '/profile', component: ProfileView },
  { path: '/', redirect: '/login' },
  { path: '/:pathMatch(.*)*', name: 'NotFound', component: NotFound }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
