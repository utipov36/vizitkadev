<template>
  <header class="header">
    <nav class="navigation">
      <RouterLink to="/" class="logo-link" @click="closeMenu">
        <img :src="Logo" alt="Logo" class="logo" />
      </RouterLink>

      <ul class="desktop-menu">
        <li><RouterLink to="/" active-class="active">Главная</RouterLink></li>
        <li><RouterLink to="/services" active-class="active">Услуги</RouterLink></li>
        <li><RouterLink to="/about" active-class="active">О нас</RouterLink></li>
        <li><RouterLink to="/contacts" active-class="active">Контакты</RouterLink></li>
      </ul>

      <p class="desktop-number"><a href="tel:+74951251040" class="desktop-number-link">+7(495) 125-10-40</a></p>

      <button
        class="burger-button"
        :class="{ active: isMenuOpen }"
        @click="toggleMenu"
        type="button"
        aria-label="Открыть меню"
        :aria-expanded="isMenuOpen.toString()"
      >
        <span class="burger-line"></span>
        <span class="burger-line"></span>
        <span class="burger-line"></span>
      </button>
    </nav>

    <div class="mobile-menu" :class="{ open: isMenuOpen }">
      <div class="mobile-menu-overlay" @click="closeMenu"></div>
      <div class="mobile-menu-content">
        <nav class="mobile-nav">
          <RouterLink to="/" @click="closeMenu" class="mobile-link">Главная</RouterLink>
          <RouterLink to="/services" @click="closeMenu" class="mobile-link">Услуги</RouterLink>
          <RouterLink to="/about" @click="closeMenu" class="mobile-link">О нас</RouterLink>
          <RouterLink to="/contacts" @click="closeMenu" class="mobile-link">Контакты</RouterLink>
          <a href="tel:+74951251040" class="mobile-phone">+7(495) 125-10-40</a>
        </nav>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import Logo from "@/assets/images/Logo.svg";

const isMenuOpen = ref(false);
const router = useRouter();

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
  document.body.style.overflow = isMenuOpen.value ? 'hidden' : '';
};

const closeMenu = () => {
  isMenuOpen.value = false;
  document.body.style.overflow = '';
};

router.afterEach(() => closeMenu());

const handleResize = () => {
  if (window.innerWidth > 768) closeMenu();
};

onMounted(() => window.addEventListener('resize', handleResize));
onUnmounted(() => {
  window.removeEventListener('resize', handleResize);
  document.body.style.overflow = '';
});
</script>

<style scoped>
* {
  box-sizing: border-box;
}

.header {
  width: 100%;
  background: #fff;
  position: relative;
  z-index: 1000;
}

.navigation {
  width: 100%;
  max-width: 1340px;
  margin: 0 auto;
  min-height: 80px;
  padding: 0 40px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
}

.logo-link {
  display: flex;
  align-items: center;
  flex-shrink: 0;
}

.logo {
  width: 200px;
  height: auto;
  display: block;
}

.desktop-menu {
  display: flex;
  gap: 40px;
  list-style: none;
  padding: 0;
  margin: 0;
}

.desktop-menu a {
  text-decoration: none;
  color: #333;
  transition: color 0.3s;
}

.desktop-menu a:hover {
  color: var(--vt-c-orange, #ff6b35);
}

.desktop-menu .active {
  color: var(--vt-c-orange, #ff6b35);
}

.desktop-number {
  margin: 0;
  white-space: nowrap;
  color: #333;
  font-size: 16px;
}

.burger-button {
  display: none;
  width: 30px;
  height: 22px;
  flex-direction: column;
  justify-content: space-between;
  background: transparent;
  border: 0;
  padding: 0;
  cursor: pointer;
  z-index: 1002;
  position: relative;
}

.burger-line {
  width: 100%;
  height: 2px;
  border-radius: 2px;
  background: #000;
  transition: 0.3s ease;
}

.burger-button.active .burger-line:nth-child(1) {
  transform: translateY(10px) rotate(45deg);
}

.burger-button.active .burger-line:nth-child(2) {
  opacity: 0;
}

.burger-button.active .burger-line:nth-child(3) {
  transform: translateY(-10px) rotate(-45deg);
}

/* Мобильное меню - полный экран с центрированием */
.mobile-menu {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1001;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s ease, visibility 0.3s ease;
}

.mobile-menu.open {
  opacity: 1;
  visibility: visible;
}

/* Размытый фон на весь экран */
.mobile-menu-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.85);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}

/* Контейнер контента - строго по центру */
.mobile-menu-content {
  position: relative;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1002;
  padding: 20px;
}

/* Навигация строго по центру */
.mobile-nav {
  width: 100%;
  max-width: 280px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 30px;
  text-align: center;
  margin: 0 auto;
  position: absolute;
  top: 0;
  left: 0;
  margin: 100px 140px;
}

/* Стили ссылок */
.mobile-link {
  color: #ffffff;
  text-decoration: none;
  font-size: 24px;
  font-weight: 500;
  transition: all 0.3s ease;
  padding: 8px 0;
  width: 100%;
  text-align: center;
  letter-spacing: 0.5px;
}

.mobile-link:hover,
.mobile-link:active {
  color: var(--vt-c-orange, #ff6b35);
  transform: scale(1.05);
}

/* Телефон */
.mobile-phone {
  color: var(--vt-c-orange, #ff6b35);
  text-decoration: none;
  font-size: 20px;
  font-weight: 700;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  padding-top: 25px;
  margin-top: 10px;
  width: 100%;
  text-align: center;
  transition: all 0.3s ease;
}

.mobile-phone:hover,
.mobile-phone:active {
  transform: scale(1.05);
}

/* Анимация появления ссылок */
.mobile-menu.open .mobile-link,
.mobile-menu.open .mobile-phone {
  animation: fadeSlideUp 0.4s ease forwards;
  opacity: 0;
}

.mobile-menu.open .mobile-link:nth-child(1) { animation-delay: 0.05s; }
.mobile-menu.open .mobile-link:nth-child(2) { animation-delay: 0.1s; }
.mobile-menu.open .mobile-link:nth-child(3) { animation-delay: 0.15s; }
.mobile-menu.open .mobile-link:nth-child(4) { animation-delay: 0.2s; }
.mobile-menu.open .mobile-phone { animation-delay: 0.25s; }

@keyframes fadeSlideUp {
  from {
    opacity: 0;
    transform: translateY(25px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===== АДАПТИВ ===== */

/* Планшеты и мобилки */
@media (max-width: 768px) {
  .navigation {
    min-height: 70px;
    padding: 0 20px;
  }

  .desktop-menu,
  .desktop-number {
    display: none;
  }

  .burger-button {
    display: flex;
  }

  .logo {
    width: 140px;
  }

  .mobile-link {
    font-size: 22px;
  }

  .mobile-phone {
    font-size: 18px;
  }
}

/* Маленькие телефоны */
@media (max-width: 480px) {
  .navigation {
    min-height: 60px;
    padding: 0 16px;
  }

  .logo {
    width: 120px;
  }

  .mobile-nav {
    max-width: 250px;
    gap: 25px;
  }

  .mobile-link {
    font-size: 20px;
    padding: 6px 0;
  }

  .mobile-phone {
    font-size: 17px;
    padding-top: 20px;
  }
}

/* Очень маленькие экраны */
@media (max-width: 380px) {
  .mobile-nav {
    max-width: 220px;
    gap: 22px;
  }

  .mobile-link {
    font-size: 18px;
  }

  .mobile-phone {
    font-size: 16px;
  }
}

.burger-button.active .burger-line {
  background: #ffffff;
}

/* Или если хочешь чтобы плавно менялся цвет */
.burger-button.active .burger-line {
  background-color: #ffffff;
}

@media (max-width: 500px) {
  .mobile-nav {
        margin: 100px 100px;
  }
}

@media (max-width: 400px) {
  .mobile-nav {
        margin: 100px 60px;
  }
}

@media (max-width: 500px) {
  .mobile-nav {
        margin: 100px 100px;
  }
}

@media (min-width: 400px) and (max-width: 500px) {
  .mobile-nav {
    margin: 100px 5rem;
  }
}

.desktop-number-link {
  text-decoration: none;
  color: var(--vt-c-black);
}

.desktop-number-link:hover {
  color: var(--vt-c-orange);
}
</style>