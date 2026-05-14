<!-- views/NotFoundView.vue -->
<template>
  <div class="not-found">
    <h1 class="not-found__title">404</h1>
    <p class="not-found__text">Упс, такой страницы не существует</p>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const goHome = () => {
  router.push('/')
}

const goBack = () => {
  router.back()
}
</script>

<style scoped>
.not-found {
  min-height: 50vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
  font-family: "Jeko", sans-serif;
}

.not-found__title {
  margin: 0 0 16px 0;
  font-size: 96px;
  color: #333;
  font-weight: 400;
}

.not-found__text {
  margin: 0;
  font-size: 24px;
  color: #666;
}
</style>