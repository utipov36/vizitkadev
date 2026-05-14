<template>
  <div class="service-container">
    <h1 class="zagolovok_serv">УСЛУГИ</h1>

    <!-- Услуга 1: Системное администрирование -->
    <div class="service-group">
      <div class="flex-cont">
        <h1 class="number-service">1.</h1>
        <h2 class="descr-service">Системное<br>администрирование</h2>
      </div>

      <div class="cards-grid">
        <div class="card">
          <h3>Сервера и хостинг</h3>
          <p>
            Настройка Linux/Windows‑серверов,<br> RDP, резервное копирование и<br> мониторинг (zabbix). 
            Гарантируем<br> 99.9% аптайм, автоматизированные<br> обновления и легкий деплой сайта.
          </p>
        </div>

        <div class="card">
          <h3>Сети и безопасность</h3>
          <p>
            Администрирование VLAN,<br> коммутаторов, маршрутизаторов,<br> брандмауэров; защита от<br> SQL‑инъекций, 
            DDoS и уязвимостей.<br> Сканирование, аудит и hardening<br> инфраструктуры.
          </p>
        </div>

        <div class="card">
          <h3>Базы данных и DevOps</h3>
          <p>
            Миграции MariaDB/PostgreSQL,<br> MySQL, оптимизация запросов,<br> автоматизированные скрипты (Bash/Python).<br>
            Docker‑контейнеры для CI<br>/CD и быстрого локального теста сайта.
          </p>
        </div>

        <div class="card">
          <h3>Мониторинг IT‑оборудования</h3>
          <p>
            Установка и настройка систем<br> мониторинга (Zabbix, Prometheus,<br> Grafana и т.п.) для серверов, сетевых<br> устройств, 
            камер, терминалов и<br> другого оборудования. Подключаем<br> оповещения, используя Telegram API,<br>
            SMS при перегреве, сбое дисков,<br> перегрузке CPU/сети или<br> отключении питания.
          </p>
        </div>
      </div>
    </div>

    <!-- Услуга 2: Веб-разработка -->
    <div class="service-group">
      <div class="flex-cont">
        <h1 class="number-service">2.</h1>
        <h2 class="descr-service">Веб‑<br>разработка</h2>
      </div>

      <div class="cards-grid">
        <div class="card">
          <h3>Сайты‑визитки</h3>
          <p>
            Быстрый запуск одностраничников<br> или маленьких сайтов на React<br>/Vue.js + Laravel/Node.js. 
            Адаптивный<br> дизайн, SEO‑дружелюбная структура<br> и модальные формы для заявок.
          </p>
        </div>

        <div class="card">
          <h3>Веб‑приложения</h3>
          <p>
            Интерактивные дашборды, панели<br> управления (например, для<br> мониторинга сети) с real‑time<br> обновлениями. 
            Поддержка<br> PostgreSQL/MySQL и<br> масштабируемая архитектура.
          </p>
        </div>

        <div class="card">
          <h3>Корпоративные сайты</h3>
          <p>
            Полноценные сайты для компаний с<br> новостными блоками, каталогами и<br> CRM‑интеграциями. 
            Оптимизация<br> загрузки, JWT/CSRF‑защита и<br> кастомный UI/UX.
          </p>
        </div>

        <div class="card">
          <h3>Сайты под ключ</h3>
          <p>
            Полноценные сайты с несколькими<br> разделами: контакты, новости,<br> услуги, галерея, онлайн‑запись.<br> 
            Разрабатываем и адаптивный<br> фронтенд, и безопасный бэкенд<br> (Laravel, Node.js, PostgreSQL/MariaDB),<br> 
            с защитой от CSRF, инъекций и<br> удобной админкой для<br> редактирования контента.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// пустой, если не нужен логика
</script>

<style>
/* Общий контейнер */
.service-container {
  margin-top: 80px;
  width: 1340px;
  max-width: 100%;
  padding: 0 20px;
  box-sizing: border-box;
}

/* Заголовок */
.zagolovok_serv {
  width: 100%;
  margin-left: 20px;
  font-size: 158px;
  font-family: "Jeko";
  margin-bottom: 40px;
}

/* Номер и заголовок услуги */
.flex-cont {
  display: flex;
  gap: 29px;
  align-items: center;
  margin-bottom: 24px;
}

.number-service {
  font-size: 112px;
  color: var(--vt-c-orange);
  font-family: "Jeko";
  margin: 0;
}

.descr-service {
  font-family: "Jeko";
  font-size: 48px;
  margin: 0;
  line-height: 1.2;
}

/* Группа одной услуги (заголовок + карточки) */
.service-group {
  margin-bottom: 60px;
  margin-top: 80px;
}

/* Сетка 2×2 карточки */
.cards-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
}

/* Одна карточка */
.card {
  padding: 28px;
  border-radius: 14px;
}


.card h3 {
  font-family: "Jeko";
  font-size: 48px;
  margin: 0 0 30px 0;
  line-height: 1.2;
  color: #111;
}

.card p {
  font-size: 24px;
  line-height: 1.5;
  color: #000;
  opacity: 50%;
  margin: 0;
  font-family: "Jeko";
}

/* Адаптивность */
@media (max-width: 1400px) {
  .cards-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 768px) {
  .zagolovok_serv {
    font-size: 4rem;
  }
}

@media (max-width: 480px) {
  
}

</style>