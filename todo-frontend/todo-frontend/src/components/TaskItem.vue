<template>
  <li class="task-item">
    <input type="checkbox" v-model="checked" @change="toggleDone" />
    <span :class="{ done: checked }">{{ task.title }}</span>
    <button @click="editTask">Modifier</button>
    <button @click="deleteTask">Supprimer</button>
  </li>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue'

const props = defineProps({ task: Object })
const emit = defineEmits(['updated', 'deleted'])

const checked = ref(props.task?.done || false)

function toggleDone() {
  // TODO: Appel API pour mettre à jour l'état
  emit('updated')
}

function editTask() {
  // TODO: Ouvrir TaskForm en mode édition
}

function deleteTask() {
  // TODO: Appel API pour supprimer la tâche
  emit('deleted')
}
</script>

<style scoped>
.done {
  text-decoration: line-through;
  color: #aaa;
}
</style>
