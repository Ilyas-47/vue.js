<template>
  <form class="task-form" @submit.prevent="submit">
    <input v-model="title" placeholder="Titre de la tâche" required />
    <button type="submit">{{ isEdit ? 'Modifier' : 'Ajouter' }}</button>
  </form>
</template>

<script setup>
import { ref, watch, defineProps, defineEmits } from 'vue'

const props = defineProps({
  task: Object,
  isEdit: Boolean
})
const emit = defineEmits(['created', 'updated'])

const title = ref(props.task ? props.task.title : '')

watch(() => props.task, (newTask) => {
  title.value = newTask ? newTask.title : ''
})

async function submit() {
  // TODO: Appel API pour créer ou modifier la tâche
  if (props.isEdit) {
    emit('updated')
  } else {
    emit('created')
  }
  title.value = ''
}
</script>
