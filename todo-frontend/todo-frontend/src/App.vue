<template>
  <div class="app-container">
    <router-view />
  </div>
</template>

<script setup>
</script>

<style scoped>
.app-container {
  max-width: 500px;
  margin: 50px auto;
  padding: 20px;
  background-color: #f5f5f5;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0,0,0,0.05);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
</style>
