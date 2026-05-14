<template>
    <div class="contacts-container">
        <h1>КОНТАКТЫ</h1>

        <div class="flex-contacts">
            <p>Свяжитесь с нами любым <br><span>удобным способом</span> — <br>обсудим задачу, оценим <br> объём работ и предложим <br>
                решение под ваш проект.</p>

            <div class="list-items-contacts">
                <li>Телефон: +7 (495) 125-10-40</li>
                <li>Telegram: @hurmanoskill</li>
                <li>Email: utipov36@gmail.com</li>
                <li>Город: Астрахань</li>
                <li>Время связи: Пн–Пт, 08:00–21:00</li>
            </div>
        </div>

        <div class="map-wrapper">
            <iframe 
                src="https://yandex.ru/map-widget/v1/?um=constructor%3Abcda05d4c24c7b4064646a7cc93c9adab1813a20ac2f47c6f1f325bc9db007c6&amp;source=constructor" 
                width="100%" 
                height="100%" 
                frameborder="0"
                class="map-iframe"
            ></iframe>
        </div>
    </div>
</template>

<style scoped>
.contacts-container {
    max-width: 1340px;
    margin: 0 auto;
    padding: 0 40px;
    width: 100%;
}

.contacts-container h1 {
    font-size: 158px;
    font-family: "Jeko", sans-serif;
    margin: 0 0 20px 0;
    line-height: 1.1;
}

.flex-contacts {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    gap: 190px;
    margin-top: 80px;
    margin-bottom: 80px;
}

.flex-contacts p {
    font-size: 40px;
    font-family: "Jeko", sans-serif;
    margin: 0;
    line-height: 1.2;
    flex: 1;
}

.flex-contacts span {
    color: var(--vt-c-orange, #ff6b35);
}

.list-items-contacts {
    flex: 1;
    margin: 0;
    padding: 0;
}

.list-items-contacts li {
    font-family: "Jeko", sans-serif;
    font-size: 32px;
    list-style: none;
    margin-bottom: 20px;
    padding-left: 30px;
    position: relative;
}

/* Кастомный маркер для списка */
.list-items-contacts li::before {
    content: "•";
    color: var(--vt-c-orange, #ff6b35);
    font-size: 32px;
    position: absolute;
    left: 0;
    top: 0;
}

/* Карта */
.map-wrapper {
    width: 100%;
    height: 720px;
    margin: 80px 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.map-iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

/* ===== АДАПТИВ ===== */

/* Планшеты (до 1024px) */
@media (max-width: 1024px) {
    .contacts-container {
        padding: 0 30px;
    }

    .contacts-container h1 {
        font-size: 100px;
    }

    .flex-contacts {
        gap: 80px;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .flex-contacts p {
        font-size: 32px;
    }

    .list-items-contacts li {
        font-size: 26px;
        margin-bottom: 15px;
        padding-left: 25px;
    }

    .list-items-contacts li::before {
        font-size: 26px;
    }

    .map-wrapper {
        height: 500px;
        margin: 50px 0;
    }
}

/* Мобильные устройства (до 768px) */
@media (max-width: 768px) {
    .contacts-container {
        padding: 0 20px;
    }

    .contacts-container h1 {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 30px;
    }

    .flex-contacts {
        flex-direction: column;
        gap: 40px;
        margin-top: 30px;
        margin-bottom: 40px;
    }

    .flex-contacts p {
        font-size: 1rem;
        text-align: center;
        br {
            display: none;
        }
    }

    /* Добавляем переносы на мобилке */
    .flex-contacts p br {
        display: none;
    }

    .list-items-contacts li {
        font-size: 18px;
        margin-bottom: 12px;
        padding-left: 20px;
        text-align: left;
    }

    .list-items-contacts li::before {
        font-size: 18px;
    }

    .map-wrapper {
        height: 350px;
        margin: 30px 0;
        border-radius: 8px;
    }
}

/* Маленькие телефоны (до 480px) */
@media (max-width: 480px) {
    .contacts-container {
        padding: 0 15px;
    }

    .contacts-container h1 {
        font-size: 36px;
    }

    .flex-contacts {
        gap: 30px;
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .flex-contacts p {
        font-size: 20px;
        text-align: left;
    }

    .list-items-contacts li {
        font-size: 16px;
        margin-bottom: 10px;
        padding-left: 18px;
        word-break: break-word;
    }

    .list-items-contacts li::before {
        font-size: 16px;
    }

    .map-wrapper {
        height: 280px;
        margin: 20px 0;
    }
}

/* Очень маленькие экраны (до 360px) */
@media (max-width: 360px) {
    .contacts-container h1 {
        font-size: 28px;
    }

    .flex-contacts p {
        font-size: 18px;
    }

    .list-items-contacts li {
        font-size: 14px;
        margin-bottom: 8px;
    }
}
</style>