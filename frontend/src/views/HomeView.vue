<script setup>
import { onMounted, onUnmounted } from 'vue'
import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'
import Lists from '@/components/Lists.vue';
import Landing from '@/components/Landing.vue';
import Slider from '@/components/Slider.vue';
import Service from '@/components/Service.vue';
import About from '@/components/About.vue';
import Footer from '@/components/Footer.vue';
import AboutUs from '@/components/AboutUs.vue';

onMounted(() => {
  gsap.registerPlugin(ScrollTrigger)
  
  // Анимация для Landing (центральный блок с эффектом)
  const landingTl = gsap.timeline({
    scrollTrigger: {
      trigger: '.landing__container',
      start: 'top 80%',
      end: 'top 30%',
      scrub: 1,
      toggleActions: 'play none none reverse'
    }
  })
  
  landingTl.fromTo('.landing__container',
    {
      opacity: 0,
      scale: 0.8,
      rotationY: 30,
      transformOrigin: 'center center'
    },
    {
      opacity: 1,
      scale: 1,
      rotationY: 0,
      duration: 1.5,
      ease: 'power3.out'
    }
  )
  
  // Анимация для Lists - элементы выпадают слева
  const listsTl = gsap.timeline({
    scrollTrigger: {
      trigger: '.lists-container',
      start: 'top 80%',
      end: 'top 40%',
      scrub: 1,
      toggleActions: 'play none none reverse'
    }
  })
  
  listsTl.fromTo('.lists-container',
    {
      opacity: 0,
      x: -200,
      rotation: -10,
      skewX: -15
    },
    {
      opacity: 1,
      x: 0,
      rotation: 0,
      skewX: 0,
      duration: 1.2,
      ease: 'back.out(0.8)'
    }
  )
  
  // Анимация для Slider - выпадает справа с вращением
  const sliderTl = gsap.timeline({
  scrollTrigger: {
    trigger: '.slider-container',
    start: 'top 80%',
    end: 'top 35%',
    scrub: 1.2,
    toggleActions: 'play none none reverse'
  }
})

sliderTl.fromTo('.slider-container',
  {
    opacity: 0,
    y: 140,
    scale: 0.94,
    rotateX: 8,
    rotateY: -6,
    filter: 'blur(18px)',
    transformOrigin: 'center center',
  },
  {
    opacity: 1,
    y: 0,
    scale: 1,
    rotateX: 0,
    rotateY: 0,
    filter: 'blur(0px)',
    duration: 1.8,
    ease: 'power4.out'
  }
)
  
  // Анимация для Service - эффект аккордеона
  const serviceTl = gsap.timeline({
    scrollTrigger: {
      trigger: '.service-container',
      start: 'top 80%',
      end: 'top 30%',
      scrub: 1,
      toggleActions: 'play none none reverse'
    }
  })
  
  serviceTl.fromTo('.service-container',
    {
      opacity: 0,
      y: 150,
      rotationX: 45,
      transformOrigin: 'top center'
    },
    {
      opacity: 1,
      y: 0,
      rotationX: 0,
      duration: 1.3,
      ease: 'power3.out'
    }
  )
  
  // Анимация для AboutUs - выпадает слева снизу
  const aboutUsTl = gsap.timeline({
    scrollTrigger: {
      trigger: '.about-us-container',
      start: 'top 80%',
      end: 'top 40%',
      scrub: 1,
      toggleActions: 'play none none reverse'
    }
  })
  
  aboutUsTl.fromTo('.about-us-container',
    {
      opacity: 0,
      x: -150,
      y: 100,
      rotation: -5,
      scale: 0.95
    },
    {
      opacity: 1,
      x: 0,
      y: 0,
      rotation: 0,
      scale: 1,
      duration: 1.1,
      ease: 'back.out(1.2)'
    }
  )
  
  // Анимация для About - выпадает справа сверху
  const aboutTl = gsap.timeline({
    scrollTrigger: {
      trigger: '.about-container',
      start: 'top 80%',
      end: 'top 40%',
      scrub: 1,
      toggleActions: 'play none none reverse'
    }
  })
  
  aboutTl.fromTo('.about-container',
    {
      opacity: 0,
      x: 150,
      y: -100,
      rotation: 8,
      scale: 0.9
    },
    {
      opacity: 1,
      x: 0,
      y: 0,
      rotation: 0,
      scale: 1,
      duration: 1.1,
      ease: 'elastic.out(1, 0.5)'
    }
  )
  
  // Анимация для Footer - плавное появление с эффектом свечения
  const footerTl = gsap.timeline({
    scrollTrigger: {
      trigger: '.footer',
      start: 'top 90%',
      end: 'top 60%',
      scrub: 1,
      toggleActions: 'play none none reverse'
    }
  })
  
  footerTl.fromTo('.footer',
    {
      opacity: 0,
      y: 80,
      filter: 'blur(10px)'
    },
    {
      opacity: 1,
      y: 0,
      filter: 'blur(0px)',
      duration: 0.8,
      ease: 'power2.out'
    }
  )
  
  // Анимация для всех дочерних элементов с каскадным эффектом
  // Lists дочерние элементы
  gsap.utils.toArray('.lists-container .list-item, .lists-container li, .lists-container .card').forEach((item, i) => {
    gsap.fromTo(item,
      {
        opacity: 0,
        x: -100,
        rotationY: -30
      },
      {
        opacity: 1,
        x: 0,
        rotationY: 0,
        duration: 0.8,
        delay: i * 0.1,
        ease: 'back.out(0.9)',
        scrollTrigger: {
          trigger: '.lists-container',
          start: 'top 85%',
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // Slider дочерние элементы
  gsap.utils.toArray('.slider-container .slide, .slider-container .slider-item, .slider-container .card').forEach((item, i) => {
    gsap.fromTo(item,
      {
        opacity: 0,
        x: 100,
        rotationY: 30,
        scale: 0.8
      },
      {
        opacity: 1,
        x: 0,
        rotationY: 0,
        scale: 1,
        duration: 0.9,
        delay: i * 0.15,
        ease: 'elastic.out(1, 0.7)',
        scrollTrigger: {
          trigger: '.slider-container',
          start: 'top 85%',
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // Service карточки - эффект 3D флип
  gsap.utils.toArray('.service-container .service-card, .service-container .service-item').forEach((card, i) => {
    gsap.fromTo(card,
      {
        opacity: 0,
        y: 80,
        rotationX: 45,
        transformOrigin: 'bottom center'
      },
      {
        opacity: 1,
        y: 0,
        rotationX: 0,
        duration: 1,
        delay: i * 0.12,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '.service-container',
          start: 'top 85%',
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // AboutUs дочерние элементы - вылетают справа налево
  gsap.utils.toArray('.about-us-container .about-item, .about-us-container .text-block, .about-us-container .content').forEach((item, i) => {
    gsap.fromTo(item,
      {
        opacity: 0,
        x: 120,
        skewX: 10
      },
      {
        opacity: 1,
        x: 0,
        skewX: 0,
        duration: 0.9,
        delay: i * 0.12,
        ease: 'back.out(1.1)',
        scrollTrigger: {
          trigger: '.about-us-container',
          start: 'top 85%',
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // About дочерние элементы - вылетают слева направо
  gsap.utils.toArray('.about-container .about-item, .about-container .text-block, .about-container .content').forEach((item, i) => {
    gsap.fromTo(item,
      {
        opacity: 0,
        x: -120,
        rotation: -5
      },
      {
        opacity: 1,
        x: 0,
        rotation: 0,
        duration: 0.9,
        delay: i * 0.12,
        ease: 'elastic.out(1, 0.6)',
        scrollTrigger: {
          trigger: '.about-container',
          start: 'top 85%',
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // Параллакс эффект при скролле для всех секций
  const sections = ['.landing__container', '.lists-container', '.slider-container', '.service-container', '.about-us-container', '.about-container']
  
  sections.forEach((section) => {
    // Эффект наклона при скролле
    gsap.to(section, {
      y: (i, el) => -el.offsetHeight * 0.1,
      ease: 'none',
      scrollTrigger: {
        trigger: section,
        start: 'top bottom',
        end: 'bottom top',
        scrub: 1.5
      }
    })
    
    // Эффект изменения прозрачности
    gsap.fromTo(section,
      {
        opacity: 0.6,
        filter: 'blur(5px)'
      },
      {
        opacity: 1,
        filter: 'blur(0px)',
        duration: 1.5,
        scrollTrigger: {
          trigger: section,
          start: 'top 85%',
          end: 'top 50%',
          scrub: 1,
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // Эффект "волны" для заголовков
  gsap.utils.toArray('h1, h2, h3').forEach((title, i) => {
    gsap.fromTo(title,
      {
        opacity: 0,
        letterSpacing: '10px',
        scale: 0.9
      },
      {
        opacity: 1,
        letterSpacing: 'normal',
        scale: 1,
        duration: 1,
        delay: i * 0.1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: title,
          start: 'top 85%',
          toggleActions: 'play none none reverse'
        }
      }
    )
  })
  
  // Интерактивный эффект при наведении на карточки
  const cards = document.querySelectorAll('.service-card, .list-item, .slide, .about-item')
  
  cards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      gsap.to(card, {
        y: -10,
        scale: 1.05,
        boxShadow: '0 20px 40px rgba(0,0,0,0.2)',
        duration: 0.3,
        ease: 'power2.out'
      })
    })
    
    card.addEventListener('mouseleave', () => {
      gsap.to(card, {
        y: 0,
        scale: 1,
        boxShadow: 'none',
        duration: 0.3,
        ease: 'power2.out'
      })
    })
  })
  
  // Очистка
  return () => {
    ScrollTrigger.getAll().forEach(trigger => trigger.kill())
  }
})
</script>

<template>
  <main>
    <Landing />
    <Lists />
    <Service />
    <Slider />
    <AboutUs />
    <About />
    <Footer />
  </main>
</template>

<style scoped>
/* Крутые эффекты для анимаций */
.landing__container,
.lists-container,
.slider-container,
.service-container,
.about-us-container,
.about-container,
.footer {
  will-change: transform, opacity, filter;
  transition: transform 0.3s ease, opacity 0.3s ease;
  backface-visibility: hidden;
  perspective: 1000px;
}

/* Эффект свечения при скролле */
@keyframes glowPulse {
  0%, 100% {
    box-shadow: 0 0 0px rgba(255,107,53,0);
  }
  50% {
    box-shadow: 0 0 30px rgba(255,107,53,0.3);
  }
}

/* 3D эффект для карточек */
.service-card,
.list-item,
.slide,
.about-item {
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

.service-card:hover,
.list-item:hover,
.slide:hover,
.about-item:hover {
  transform: translateY(-10px) scale(1.03) rotateX(2deg);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Плавное появление контента */
main > * {
  animation: fadeInScale 0.6s ease-out forwards;
  opacity: 0;
}

@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Эффект стекла для карточек */
.service-card, .list-item {
  backdrop-filter: blur(10px);
  transition: backdrop-filter 0.3s ease;
}

.service-card:hover, .list-item:hover {
  backdrop-filter: blur(0px);
}

/* Стили для скролла */
::-webkit-scrollbar {
  width: 10px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: var(--vt-c-orange);
  border-radius: 5px;
}

::-webkit-scrollbar-thumb:hover {
  background: #ff5722;
}

/* Эффект параллакса для фона */
body {
  overflow-x: hidden;
}

/* Анимация загрузки секций */
main > * {
  animation: slideUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}

@keyframes slideUp {
  from {
    transform: translateY(100px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

/* Задержки для каждой секции */
main > :nth-child(1) { animation-delay: 0s; }
main > :nth-child(2) { animation-delay: 0.15s; }
main > :nth-child(3) { animation-delay: 0.3s; }
main > :nth-child(4) { animation-delay: 0.45s; }
main > :nth-child(5) { animation-delay: 0.6s; }
main > :nth-child(6) { animation-delay: 0.75s; }
main > :nth-child(7) { animation-delay: 0.9s; }
</style>