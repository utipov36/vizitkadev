<template>
  <div class="landing__container">
    <div class="left__block">
      <h1 class="animated-title">Продукт на <br> стероидах: Dev + <br> Admin в одном <br> флаконе</h1>
      
      <!-- Описание с эффектом печатающего текста -->
      <p class="animated-description">
        <span class="typing-text" ref="typingText"></span>
        <span class="cursor" :class="{ 'typing': isTyping }">|</span>
      </p>
      
      <div class="button-group">
        <button class="priceBtn" @click="openLeadForm">Узнать стоимость</button>
        <button class="notifyBtn" @click="openLeadForm">
          <img :src="CallIcon" alt="Call">Оставить заявку
        </button>
      </div>
    </div>
    <div class="right__block">
      <img :src="LandingImg" alt="Image" class="animated-image">
    </div>

    <!-- Подключаем модальное окно -->
    <LeadForm v-model="isModalOpen" @submitted="handleFormSubmitted" />
  </div>
</template>

<script setup>
import { onMounted, ref, onUnmounted } from 'vue'
import gsap from 'gsap'
import LandingImg from '@/assets/images/Mask group.png'
import CallIcon from '@/assets/images/callIcon.svg'
import LeadForm from './modals/LeadForm.vue'
import { toast } from 'vue-sonner'

const typingText = ref(null)
const isTyping = ref(true)
const isModalOpen = ref(false)

// Твой оригинальный текст для печатающего эффекта
const fullText = `Суперкомбо разработчика и админа: пишем чистый код, оптимизируем запросы, настраиваем сети и безопасность (SQL-инъекции, парсинг). Ваш сайт — быстрый, безопасный и всегда доступный`

let currentIndex = 0
let typingTimeout = null

// Функция открытия модального окна
const openLeadForm = () => {
  isModalOpen.value = true
}

// Функция печатающего текста
const typeWriter = () => {
  if (currentIndex < fullText.length) {
    if (typingText.value) {
      typingText.value.textContent += fullText.charAt(currentIndex)
      currentIndex++
      isTyping.value = true
      typingTimeout = setTimeout(typeWriter, 50)
    }
  } else {
    isTyping.value = false
  }
}

onMounted(() => {
  // Запускаем эффект печати текста
  typeWriter()
  
  // GSAP анимации
  gsap.set('.priceBtn, .notifyBtn', {
    opacity: 0,
    scale: 0.8,
    y: 20
  })
  
  gsap.set('.animated-title', {
    opacity: 0,
    y: 50
  })
  
  gsap.set('.animated-description', {
    opacity: 0
  })
  
  gsap.set('.animated-image', {
    opacity: 0,
    scale: 1.1,
    x: 50
  })
  
  // Основная анимационная шкала
  const tl = gsap.timeline()
  
  tl.from('.landing__container', {
    scale: 0.95,
    opacity: 0,
    duration: 1.2,
    ease: 'power3.out'
  })
  
  tl.to('.animated-title', {
    opacity: 1,
    y: 0,
    duration: 0.8,
    ease: 'back.out(0.7)'
  }, '-=0.6')
  
  tl.to('.animated-description', {
    opacity: 1,
    duration: 0.6,
    ease: 'power2.out'
  }, '-=0.4')
  
  tl.to('.priceBtn, .notifyBtn', {
    opacity: 1,
    scale: 1,
    y: 0,
    duration: 0.6,
    stagger: 0.2,
    ease: 'elastic.out(1, 0.5)'
  }, '-=0.3')
  
  tl.to('.animated-image', {
    opacity: 1,
    scale: 1,
    x: 0,
    duration: 1,
    ease: 'power3.out'
  }, '-=0.8')
  
  // Анимация кнопок при наведении
  const priceBtn = document.querySelector('.priceBtn')
  const notifyBtn = document.querySelector('.notifyBtn')
  
  priceBtn?.addEventListener('mouseenter', () => {
    gsap.to(priceBtn, {
      scale: 1.05,
      boxShadow: '0 10px 25px rgba(0,0,0,0.2)',
      duration: 0.3,
      ease: 'power2.out'
    })
  })
  
  priceBtn?.addEventListener('mouseleave', () => {
    gsap.to(priceBtn, {
      scale: 1,
      boxShadow: 'none',
      duration: 0.3,
      ease: 'power2.out'
    })
  })
  
  notifyBtn?.addEventListener('mouseenter', () => {
    gsap.to(notifyBtn, {
      scale: 1.05,
      backgroundColor: 'rgba(255,255,255,0.1)',
      duration: 0.3,
      ease: 'power2.out'
    })
  })
  
  notifyBtn?.addEventListener('mouseleave', () => {
    gsap.to(notifyBtn, {
      scale: 1,
      backgroundColor: 'transparent',
      duration: 0.3,
      ease: 'power2.out'
    })
  })
  
  // Плавающая анимация для изображения
  gsap.to('.animated-image', {
    y: 15,
    duration: 3,
    repeat: -1,
    yoyo: true,
    ease: 'power1.inOut',
    delay: 1
  })
  
  // Параллакс эффект
  const container = document.querySelector('.landing__container')
  const image = document.querySelector('.animated-image')
  const title = document.querySelector('.animated-title')
  
  container?.addEventListener('mousemove', (e) => {
    const rect = container.getBoundingClientRect()
    const x = (e.clientX - rect.left) / rect.width - 0.5
    const y = (e.clientY - rect.top) / rect.height - 0.5
    
    gsap.to(image, {
      x: x * 20,
      y: y * 20,
      rotation: x * 2,
      duration: 1,
      ease: 'power2.out'
    })
    
    gsap.to(title, {
      x: x * 10,
      y: y * 10,
      duration: 1,
      ease: 'power2.out'
    })
  })
  
  container?.addEventListener('mouseleave', () => {
    gsap.to(image, {
      x: 0,
      y: 0,
      rotation: 0,
      duration: 1,
      ease: 'elastic.out(1, 0.3)'
    })
    
    gsap.to(title, {
      x: 0,
      y: 0,
      duration: 1,
      ease: 'elastic.out(1, 0.3)'
    })
  })
  
  createParticles()
})

onUnmounted(() => {
  if (typingTimeout) {
    clearTimeout(typingTimeout)
  }
})

// Партиклы для фона
const createParticles = () => {
  const container = document.querySelector('.landing__container')
  if (!container) return
  
  for (let i = 0; i < 30; i++) {
    const particle = document.createElement('div')
    particle.classList.add('particle')
    particle.style.width = Math.random() * 6 + 2 + 'px'
    particle.style.height = particle.style.width
    particle.style.left = Math.random() * 100 + '%'
    particle.style.top = Math.random() * 100 + '%'
    particle.style.animationDelay = Math.random() * 5 + 's'
    particle.style.animationDuration = Math.random() * 10 + 5 + 's'
    container.appendChild(particle)
    
    gsap.to(particle, {
      y: -100,
      x: (Math.random() - 0.5) * 200,
      opacity: 0,
      duration: Math.random() * 8 + 4,
      repeat: -1,
      delay: Math.random() * 5,
      ease: 'power1.inOut'
    })
  }
}
</script>

<style scoped>
/* Ваши существующие стили остаются без изменений */
.landing__container {
    height: 748px;
    background: linear-gradient(135deg, #9F4626 0%, #C96935 50%, #CC6F38 100%);
    border-radius: 20px;
    color: var(--vt-c-white);
    margin-top: 40px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

/* Партиклы */
.particle {
    position: absolute;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    pointer-events: none;
    z-index: 1;
}

.left__block {
    width: 660px;
    height: 507px;
    padding-top: 100px;
    padding-left: 40px;
    position: relative;
    z-index: 10;
}

.left__block p {
    width: 660px;
}

.left__block h1, .left__block p {
    font-family: "Montserrat";
    position: relative;
    z-index: 10;
}

.left__block h1 {
    font-size: 58px;
    line-height: 1.2;
    background: linear-gradient(135deg, #fff 0%, #ffe0b3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.left__block p {
    padding-top: 30px;
    padding-bottom: 30px;
    font-size: 24px;
    line-height: 1.4;
    opacity: 0.95;
}

/* Стили для печатающего текста */
.animated-description {
    padding-top: 30px;
    padding-bottom: 30px;
    font-size: 24px;
    line-height: 1.4;
    opacity: 0.95;
    min-height: 180px;
}

.typing-text {
    display: inline;
}

.cursor {
    display: inline-block;
    width: 2px;
    height: 32px;
    background-color: white;
    margin-left: 2px;
    vertical-align: middle;
    animation: blink 1s step-end infinite;
}

.cursor.typing {
    animation: blink 0.8s step-end infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}

.button-group {
    display: flex;
    gap: 20px;
    align-items: center;
}

.right__block {
    position: absolute;
    top: 0px;
    left: 220px;
    z-index: 2;
    pointer-events: none;
}

.right__block img {
    border-radius: 20px;
    position: relative;
    filter: drop-shadow(0 10px 20px rgba(0,0,0,0.2));
    transition: filter 0.3s ease;
}

.priceBtn {
    font-family: "Jeko";
    font-size: 20px;
    color: var(--vt-c-orange);
    padding: 11px 26px;
    border-radius: 6px;
    background-color: var(--vt-c-white);
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 600;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: scale(0.8) translateY(20px);
}

.priceBtn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,107,53,0.2);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.priceBtn:hover::before {
    width: 300px;
    height: 300px;
}

.notifyBtn {
    font-family: "Jeko";
    font-size: 20px;
    color: var(--vt-c-white);
    padding: 11px 26px;
    border-radius: 6px;
    border: 1px solid #fff;
    background: none;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: scale(0.8) translateY(20px);
}

.notifyBtn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.notifyBtn:hover::before {
    left: 100%;
}

.notifyBtn img {
    margin-right: 10px;
    align-items: center;
    filter: brightness(0) invert(1);
}

.animated-title {
    display: inline-block;
    opacity: 0;
    transform: translateY(50px);
}

.animated-image {
    opacity: 0;
    transform: scale(1.1) translateX(50px);
}

@media (max-width: 1440px) {
    .landing__container {
        width: 100%;
        padding: 0 20px;
    }
    
    .right__block {
        left: 200px;
    }
}

@media (max-width: 1024px) {
    .landing__container {
        height: auto;
        min-height: 800px;
    }
    
    .left__block {
        width: 100%;
        padding: 60px 20px;
    }
    
    .left__block h1 {
        font-size: 40px;
    }
    
    .left__block p {
        font-size: 18px;
        width: 100%;
        min-height: auto;
    }
    
    .right__block {
        display: none;
    }
    
    .button-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .priceBtn, .notifyBtn {
        text-align: center;
        justify-content: center;
    }
    
    .cursor {
        height: 24px;
    }
}
</style>