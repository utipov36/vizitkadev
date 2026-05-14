<template>
  <footer class="footer">
    <!-- Левая колонка: услуги -->
    <div class="footer-block">
      <ul class="footer-services">
        <li>разработка</li>
        <li>администрирование</li>
        <li>дизайн</li>
        <li>цифровые продукты</li>
        <li>мониторинг</li>
      </ul>
    </div>

    <!-- Правая колонка: контакты -->
    <div class="footer-info">
      <p class="footer-item">+7 (495) 125-10-40</p>
      <p class="footer-item-orange"><a href="mailto:utipov36@gmail.com" class="footer-item-orange">utipov36@gmail.com</a></p>
      <p class="footer-item-time">пн–пт 8:00–21:00</p>
    </div>

    <!-- Блок снизу на всю ширину -->
    <div class="footer-bottom">
      <p class="footer-year">Интеллектуальная собственность, © 2026</p>
      <p class="footer-privacy">обработка персональных данных</p>
    </div>
  </footer>
</template>


<style scoped>
.footer {
  height: 686px;
  background-color: #E4EAED;
  border-radius: 20px;
  padding: 80px 100px 60px 100px;
  box-sizing: border-box;

  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  font-family: "Jeko", sans-serif;
  margin-top: 80px;
  position: relative;
}

.footer-block {
  flex: 1;
  max-width: 400px;
}

.footer-services {
  margin: 0;
  padding: 0;
  list-style: none;
}

.footer-services li {
  font-size: 32px;
  line-height: 1.4;
  color: #111;
  margin-bottom: 16px;
}

.footer-info {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.footer-item {
  margin: 0;
  font-size: 32px;
  color: #000;
  font-family: "Jeko";
}

.footer-item-orange {
  font-size: 32px;
  color: var(--vt-c-orange);
  font-family: "Jeko";
  text-decoration: underline;
}

.footer-item-time {
  font-size: 32px;
  color: #AAAAAC;
  font-family: "Jeko";
}

/* Блок снизу на всю ширину */
.footer-bottom {
  position: absolute;
  bottom: 20px;
  left: 100px;
  right: 100px;
  display: flex;
  justify-content: space-between;
  align-items: center;

  font-family: "Jeko", sans-serif;
}

.footer-year {
  margin: 0;
  font-size: 16px;
  color: #AAAAAC;
}

.footer-privacy {
  margin: 0;
  font-size: 16px;
  color: #AAAAAC;
  cursor: pointer;
  font-family: "Jeko";
}

.footer-privacy:hover {
  color: var(--vt-c-orange);
}

/* Адаптив для маленького экрана */
@media (max-width: 1500px) {
  .footer {
    width: 1200px;
  }
}

@media (max-width: 1200px) {
  .footer {
    width: 100%;
    padding: 60px 40px 50px 40px;
  }
}

@media (max-width: 768px) {
  .footer {
    flex-direction: column;
    gap: 32px;
    height: auto;
    padding: 60px 20px 120px 20px;
  }

  .footer-bottom {
    left: 20px;
    right: 20px;
    flex-direction: column;
    gap: 8px;
    align-items: center;
  }

  .footer-year {
    font-size: 18px;
  }

  .footer-privacy {
    font-size: 16px;
  }
}

.footer-services li:hover {
    color: var(--vt-c-orange);
    cursor: pointer;
}
</style>