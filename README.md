# 📝 To-Do List App – Full Stack (Laravel + Vue.js)

Projet Full Stack permettant de gérer des tâches avec authentification sécurisée (JWT) et notifications en temps réel via Pusher. Il est conçu dans un but pédagogique pour apprendre à structurer un projet professionnel avec Laravel, Vue.js, Laravel Echo, Axios, Pinia, et le pattern Service/Repository.

🔗 **Repo GitHub** : [https://github.com/Ilyas-47/vue.js](https://github.com/Ilyas-47/vue.js)

---

## 🚀 Fonctionnalités

### 🔐 Authentification (JWT)
- Inscription : full name, email, phone, address, image, mot de passe
- Connexion : génération d’un token JWT
- Middleware protégé (`Authorization: Bearer <token>`)
- Un utilisateur ne peut accéder qu’à ses propres tâches

### 🗂️ Gestion des tâches
- `GET /api/tasks` : liste des tâches
- `GET /api/tasks/:id` : détail
- `POST /api/tasks` : ajouter une tâche
- `PUT /api/tasks/:id` : modifier une tâche
- `DELETE /api/tasks/:id` : supprimer une tâche

### 🔔 Notifications en temps réel
- Laravel Echo + Pusher
- Message envoyé après la création d'une tâche
- Page dédiée pour recevoir les notifications

---

## 📦 Étapes pour utiliser ce projet

### ✅ Prérequis installés

- PHP >= 8.1
- Composer
- Node.js + npm
- MySQL
- Compte [Pusher](https://pusher.com/)
- Git

---

### 🔽 1. Cloner le dépôt

```bash
git clone https://github.com/Ilyas-47/vue.js.git
