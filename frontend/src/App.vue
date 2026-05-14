<template>
  <div id="container">
    <!-- Минималистичный круговой индикатор загрузки -->
    <div v-if="isLoading" class="loading-overlay">
      <div class="circular-progress">
        <svg class="progress-svg" viewBox="0 0 100 100">
          <!-- Фоновый круг -->
          <circle 
            class="progress-bg"
            cx="50" 
            cy="50" 
            r="45"
            fill="none"
            stroke="rgba(255,255,255,0.1)"
            stroke-width="2"
          />
          
          <!-- Прогресс круг -->
          <circle 
            class="progress-circle"
            cx="50" 
            cy="50" 
            r="45"
            fill="none"
            stroke="var(--vt-c-orange)"
            stroke-width="2"
            stroke-linecap="round"
            :stroke-dasharray="circumference"
            :stroke-dashoffset="strokeDashOffset"
          />
        </svg>
        
        <!-- Процент в центре -->
        <div class="progress-percent">
          {{ Math.floor(loadingProgress) }}%
        </div>
        
        <!-- Текст загрузки -->
        <div class="progress-text">{{ loadingText }}</div>
      </div>
    </div>
    
    <Header />
     <Toaster />
    <RouterView v-if="!isLoading" />
  </div>
</template>

<style scoped>
#container {
  opacity: 1;
  transition: opacity 0.3s ease;
}

/* Минималистичный оверлей загрузки */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.90);
  backdrop-filter: blur(8px);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  animation: fadeIn 0.3s ease;
}

/* Круговой прогресс */
.circular-progress {
  position: relative;
  width: 120px;
  height: 120px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.progress-svg {
  width: 100%;
  height: 100%;
  transform: rotate(-90deg);
}

.progress-bg {
  stroke: rgba(255, 255, 255, 0.1);
}

.progress-circle {
  transition: stroke-dashoffset 0.2s linear;
  filter: drop-shadow(0 0 4px rgba(255, 107, 53, 0.3));
}

.progress-percent {
  position: absolute;
  font-size: 20px;
  font-weight: 600;
  font-family: "Montserrat", monospace;
  color: var(--vt-c-orange);
  text-align: center;
  letter-spacing: 1px;
}

.progress-text {
  position: absolute;
  bottom: -40px;
  font-size: 12px;
  font-family: "Jeko", sans-serif;
  color: rgba(255, 255, 255, 0.7);
  white-space: nowrap;
  letter-spacing: 1px;
  font-weight: 300;
}

/* Анимация появления контента */
.router-view-enter-active,
.router-view-leave-active {
  transition: opacity 0.3s ease;
}

.router-view-enter-from,
.router-view-leave-to {
  opacity: 0;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Адаптивность */
@media (max-width: 768px) {
  .circular-progress {
    width: 100px;
    height: 100px;
  }
  
  .progress-percent {
    font-size: 18px;
  }
  
  .progress-text {
    font-size: 11px;
    bottom: -35px;
  }
}
</style>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { RouterView, useRouter } from 'vue-router'
import { Toaster, toast } from 'vue-sonner'
import 'vue-sonner/style.css'
import Header from './components/Header.vue'

const router = useRouter()
const isLoading = ref(false)
const loadingText = ref('Загрузка...')
const loadingProgress = ref(0)

// Окружность круга (2 * PI * R) где R = 45
const circumference = 2 * Math.PI * 45 // 282.743

// Вычисляем offset для кругового прогресса
const strokeDashOffset = computed(() => {
  return circumference - (loadingProgress.value / 100) * circumference
})

// Функция для имитации прогресса загрузки
let progressInterval = null
let animationFrame = null

const startProgressSimulation = () => {
  loadingProgress.value = 0
  if (progressInterval) clearInterval(progressInterval)
  if (animationFrame) cancelAnimationFrame(animationFrame)
  
  const startTime = performance.now()
  const duration = 2000 // 2 секунды на достижение 90%
  
  const animate = (currentTime) => {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / duration, 1)
    
    // Easing для плавности
    const easeOutCubic = 1 - Math.pow(1 - progress, 3)
    const targetProgress = Math.min(easeOutCubic * 90, 90)
    
    loadingProgress.value = targetProgress
    
    if (progress < 1) {
      animationFrame = requestAnimationFrame(animate)
    }
  }
  
  animationFrame = requestAnimationFrame(animate)
}

const completeProgress = () => {
  if (progressInterval) clearInterval(progressInterval)
  if (animationFrame) cancelAnimationFrame(animationFrame)
  
  // Плавно добираем до 100%
  const startProgress = loadingProgress.value
  const startTime = performance.now()
  const duration = 300 // 300ms до 100%
  
  const animateToComplete = (currentTime) => {
    const elapsed = currentTime - startTime
    const progress = Math.min(elapsed / duration, 1)
    
    // Easing для плавного завершения
    const easeOutQuad = 1 - (1 - progress) * (1 - progress)
    loadingProgress.value = startProgress + (100 - startProgress) * easeOutQuad
    
    if (progress < 1) {
      animationFrame = requestAnimationFrame(animateToComplete)
    } else {
      loadingProgress.value = 100
      setTimeout(() => {
        loadingProgress.value = 0
      }, 300)
    }
  }
  
  animationFrame = requestAnimationFrame(animateToComplete)
}

// Отслеживаем начало навигации
router.beforeEach((to, from, next) => {
  isLoading.value = true
  const pageTitle = to.meta?.title || 'страницы'
  loadingText.value = `Загрузка ${pageTitle}`
  startProgressSimulation()
  next()
})

// Отслеживаем завершение навигации
router.afterEach(() => {
  setTimeout(() => {
    completeProgress()
    setTimeout(() => {
      isLoading.value = false
    }, 500)
  }, 500)
})

// Обработка ошибок загрузки
router.onError((error) => {
  console.error('Ошибка загрузки:', error)
  loadingText.value = 'Ошибка загрузки'
  loadingProgress.value = 0
  
  setTimeout(() => {
    isLoading.value = false
  }, 2000)
})

// Загрузка при монтировании приложения
onMounted(() => {
  isLoading.value = true
  loadingText.value = 'Инициализация'
  startProgressSimulation()
  
  setTimeout(() => {
    completeProgress()
    setTimeout(() => {
      isLoading.value = false
    }, 500)
  }, 2000)
})

onUnmounted(() => {
  if (progressInterval) clearInterval(progressInterval)
  if (animationFrame) cancelAnimationFrame(animationFrame)
})
</script>