//нижний слайдер с 3 буллетами

<template>
  <div class="slider-wrapper">
    <!-- Область с фото (с overflow hidden) -->
    <div 
      class="slider-container"
      @mousedown="startDrag"
      @mousemove="onDrag"
      @mouseup="endDrag"
      @mouseleave="endDrag"
      @touchstart="startDrag"
      @touchmove="onDrag"
      @touchend="endDrag"
    >
      <div 
        class="slider-track" 
        :style="{ transform: `translateX(calc(-${currentSlide * 100}% + ${dragOffset}px))` }"
        :class="{ dragging: isDragging }"
      >
        <div class="slide">
          <img :src="SlidePng1" alt="Работа 1" class="slider-img" draggable="false">
        </div>
        <div class="slide">
          <img :src="SlidePng2" alt="Работа 2" class="slider-img" draggable="false">
        </div>
        <div class="slide">
          <img :src="SlidePng3" alt="Работа 3" class="slider-img" draggable="false">
        </div>
      </div>
    </div>
    
    <!-- Bullets ВНЕ контейнера - всегда видны -->
    <div class="bullets">
      <div 
        v-for="(bullet, index) in 3" 
        :key="index"
        class="bullet" 
        :class="{ active: currentSlide === index }"
        @click="goToSlide(index)"
      >
        <span class="bullet-fill" :style="{ width: getBulletFillWidth(index) }"></span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import SlidePng1 from '@/assets/images/midj2.png'
import SlidePng2 from '@/assets/images/midj3.png' 
import SlidePng3 from '@/assets/images/midj4.png'

const currentSlide = ref(0)
const totalSlides = 3

// Drag state
const isDragging = ref(false)
const startX = ref(0)
const dragOffset = ref(0)
const dragStartSlide = ref(0)
const dragThreshold = 50 // минимальное расстояние для переключения слайда

// Timer state
let autoSlideInterval = null

// Bullet fill state
const bulletProgress = ref([0, 0, 0])
let progressInterval = null

// Функция для обновления прогресса буллитов
const updateBulletProgress = () => {
  if (progressInterval) clearInterval(progressInterval)
  
  // Сбрасываем прогресс для всех буллитов
  bulletProgress.value = bulletProgress.value.map(() => 0)
  
  // Запускаем анимацию только для активного буллита
  if (autoSlideInterval) {
    let startTime = null
    const duration = 4000 // 4 секунды
    
    const animateProgress = (timestamp) => {
      if (!startTime) startTime = timestamp
      const elapsed = timestamp - startTime
      const progress = Math.min(elapsed / duration, 1)
      
      bulletProgress.value[currentSlide.value] = progress * 100
      
      if (progress < 1) {
        requestAnimationFrame(animateProgress)
      }
    }
    
    requestAnimationFrame(animateProgress)
  }
}

// Автослайд с обновлением прогресса
const startAutoSlide = () => {
  if (autoSlideInterval) clearInterval(autoSlideInterval)
  
  autoSlideInterval = setInterval(() => {
    if (!isDragging.value) {
      nextSlide()
    }
  }, 4000)
  
  updateBulletProgress()
}

const stopAutoSlide = () => {
  if (autoSlideInterval) {
    clearInterval(autoSlideInterval)
    autoSlideInterval = null
  }
  if (progressInterval) {
    clearInterval(progressInterval)
    progressInterval = null
  }
}

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % totalSlides
  updateBulletProgress()
}

const goToSlide = (index) => {
  if (index === currentSlide.value) {
    // Если кликнули на активный буллит - перезапускаем анимацию
    stopAutoSlide()
    startAutoSlide()
    return
  }
  currentSlide.value = index
  updateBulletProgress()
  // Перезапускаем автослайд
  stopAutoSlide()
  startAutoSlide()
}

// Получаем ширину заполнения буллита
const getBulletFillWidth = (index) => {
  if (index === currentSlide.value) {
    return `${bulletProgress.value[index]}%`
  }
  return '0%'
}

// Drag handlers
const startDrag = (event) => {
  if (isDragging.value) return
  
  stopAutoSlide()
  isDragging.value = true
  dragStartSlide.value = currentSlide.value
  
  const clientX = event.type === 'touchstart' ? event.touches[0].clientX : event.clientX
  startX.value = clientX
  dragOffset.value = 0
  
  document.body.style.userSelect = 'none'
}

const onDrag = (event) => {
  if (!isDragging.value) return
  
  const clientX = event.type === 'touchmove' ? event.touches[0].clientX : event.clientX
  let diff = clientX - startX.value
  
  // Ограничиваем перетаскивание
  if (dragStartSlide.value === 0 && diff > 0) {
    diff = diff * 0.3 // сопротивление на первом слайде
  }
  if (dragStartSlide.value === totalSlides - 1 && diff < 0) {
    diff = diff * 0.3 // сопротивление на последнем слайде
  }
  
  dragOffset.value = diff
}

const endDrag = () => {
  if (!isDragging.value) return
  
  isDragging.value = false
  document.body.style.userSelect = ''
  
  // Определяем нужно ли переключить слайд
  if (Math.abs(dragOffset.value) > dragThreshold) {
    if (dragOffset.value > 0 && dragStartSlide.value > 0) {
      // Свайп вправо - предыдущий слайд
      currentSlide.value = dragStartSlide.value - 1
    } else if (dragOffset.value < 0 && dragStartSlide.value < totalSlides - 1) {
      // Свайп влево - следующий слайд
      currentSlide.value = dragStartSlide.value + 1
    }
    updateBulletProgress()
  }
  
  dragOffset.value = 0
  
  // Запускаем автослайд заново
  startAutoSlide()
}

// Lifecycle
onMounted(() => {
  startAutoSlide()
})

onUnmounted(() => {
  stopAutoSlide()
})
</script>

<style scoped>
.slider-wrapper {
  margin: 0 auto;
  position: relative;
  margin-top: 80px;
}

.slider-container {
  height: 667px;
  overflow: hidden;
  cursor: grab;
  user-select: none;
  touch-action: pan-y pinch-zoom;
}

.slider-container:active {
  cursor: grabbing;
}

.slider-track {
  display: flex;
  height: 100%;
  transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  will-change: transform;
}

.slider-track.dragging {
  transition: none;
}

.slide {
  min-width: 100%;
  height: 100%;
}

.slider-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
  display: block;
  pointer-events: none;
}

.bullets {
  display: flex;
  gap: 12px;
  justify-content: center;
  margin-top: 30px;
}

.bullet {
  width: 60px;
  height: 4px;
  border-radius: 2px;
  background: rgba(0, 0, 0, 0.2);
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition: background 0.3s ease;
}

.bullet-fill {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  background: linear-gradient(90deg, var(--vt-c-orange), #ff6b35);
  border-radius: 2px;
  transition: width 0.05s linear;
  width: 0%;
}

.bullet.active {
  background: rgba(0, 0, 0, 0.1);
}

.bullet:hover {
  background: rgba(0, 0, 0, 0.3);
}

.bullet:hover .bullet-fill {
  width: 100% !important;
  transition: width 0.5s ease;
}

/* Адаптивность */
@media (max-width: 1380px) {
  .slider-wrapper {
    width: 100%;
    padding: 0 20px;
  }
  
  .slider-container {
    height: auto;
    aspect-ratio: 1340 / 667;
  }
}

@media (max-width: 768px) {
  .bullet {
    width: 40px;
  }
  
  .slider-wrapper {
    margin-top: 40px;
  }
 
   .slider-container {
    height: auto;
    aspect-ratio: 340 / 667;
  }
  
}
</style>