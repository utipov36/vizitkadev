<template>
    <div class="container">
        <!-- КАРТОЧКИ -->
        <div class="list__container">
            <h1>ПРИМЕРЫ РАБОТ</h1>
            <div class="col-items">
                <div 
                    v-for="(item, index) in items" 
                    :key="index"
                    class="row"
                    :style="getCardStyle(index)"
                    @mouseenter="onCardHover(index, true)"
                    @mouseleave="onCardHover(index, false)"
                >
                    {{ index + 1 }}
                    <p>{{ item.name }}</p>
                </div>
            </div>
        </div>
        <!-- СЛАЙДЕР -->
        <div class="slider-wrapper">
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
                    <div v-for="(imgSrc, index) in slideImages" :key="index" class="slide">
                        <img :src="imgSrc" :alt="`Работа ${index + 1}`" class="slider-img" draggable="false">
                    </div>
                </div>
            </div>
            
            <div class="bullets">
                <div 
                    v-for="(bullet, index) in totalSlides" 
                    :key="index"
                    class="bullet" 
                    :class="{ active: currentSlide === index }"
                    @click="goToSlide(index)"
                >
                    <span class="bullet-fill" :style="{ width: getBulletFillWidth(index) }"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import midj2 from '@/assets/images/midj2.png'
import amidj2 from '@/assets/images/midj3.png'

export default {
    name: 'ExamplesList',
    data() {
        return {
            items: [
                { name: 'Сбер' },
                { name: 'VK' },
                { name: 'Telegram' },
                { name: 'Yandex' },
                { name: 'Альфа' },
                { name: 'Браво' },
                { name: 'Чарли' },
                { name: 'Танго' }
            ],
            animations: [],
            hoverStates: [],
            animationFrame: null,
            startTime: null,
            
            // ✅ ИСПРАВЛЕНО: прямой импорт изображений
            currentSlide: 0,
            totalSlides: 8,
            slideImages: [
                midj2, midj2, midj2, midj2,   // индексы 0-3
                amidj2, midj2, midj2, midj2   // индексы 4-7
            ],
            
            // Состояние слайдера
            isDragging: false,
            startX: 0,
            dragOffset: 0,
            dragStartSlide: 0,
            dragThreshold: 50,
            autoSlideInterval: null,
            bulletProgress: [0, 0, 0, 0, 0, 0, 0, 0],
            progressStartTime: null
        }
    },
    mounted() {
        this.initAnimations()
        this.startAnimation()
        this.startAutoSlide()
    },
    beforeUnmount() {
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame)
        }
        this.stopAutoSlide()
    },
    methods: {
        initAnimations() {
            for (let i = 0; i < this.items.length; i++) {
                this.animations.push({
                    yAmplitude: 5 + Math.random() * 55,
                    ySpeed: 0.5 + Math.random() * 1.5,
                    yPhase: Math.random() * Math.PI * 2,
                    xAmplitude: 3 + Math.random() * 12,
                    xSpeed: 0.3 + Math.random() * 1.8,
                    xPhase: Math.random() * Math.PI * 2,
                    rotateAmplitude: 0.5 + Math.random() * 2,
                    rotateSpeed: 0.4 + Math.random() * 1.6,
                    rotatePhase: Math.random() * Math.PI * 2,
                    scaleAmplitude: 0.01 + Math.random() * 0.03,
                    scaleSpeed: 0.6 + Math.random() * 1.4,
                    scalePhase: Math.random() * Math.PI * 2,
                    delay: Math.random() * Math.PI * 2
                })
                this.hoverStates.push(false)
            }
        },
        
        getCardStyle(index) {
            if (!this.startTime || !this.animations[index]) return {}
            
            const time = (performance.now() - this.startTime) / 1000
            const anim = this.animations[index]
            const isHovered = this.hoverStates[index]
            const t = time + anim.delay
            
            const yOffset = Math.sin(t * anim.ySpeed + anim.yPhase) * (isHovered ? anim.yAmplitude * 0.3 : anim.yAmplitude)
            const xOffset = Math.sin(t * anim.xSpeed + anim.xPhase) * (isHovered ? anim.xAmplitude * 0.3 : anim.xAmplitude)
            const rotate = Math.sin(t * anim.rotateSpeed + anim.rotatePhase) * (isHovered ? anim.rotateAmplitude * 0.5 : anim.rotateAmplitude)
            const scale = 1 + Math.sin(t * anim.scaleSpeed + anim.scalePhase) * (isHovered ? anim.scaleAmplitude * 0.5 : anim.scaleAmplitude)
            
            const transition = isHovered ? 'all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1)' : 'all 0.5s ease-out'
            
            return {
                transform: `translate(${xOffset}px, ${yOffset}px) rotate(${rotate}deg) scale(${scale})`,
                transition,
                willChange: 'transform'
            }
        },
        
        // 🎯 ХОВЕР СИНХРОНИЗАЦИЯ СО СЛАЙДЕРОМ
        onCardHover(index, isHovered) {
            this.hoverStates[index] = isHovered
            
            if (isHovered) {
                this.goToSlide(index)
                const card = document.querySelectorAll('.row')[index]
                if (card) card.style.zIndex = '10'
            } else {
                const card = document.querySelectorAll('.row')[index]
                if (card) card.style.zIndex = '1'
            }
        },
        
        // СЛАЙДЕР МЕТОДЫ
        goToSlide(index) {
            this.stopAutoSlide()
            this.currentSlide = index
            this.updateBulletProgress()
            this.startAutoSlide()
        },
        
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides
            this.updateBulletProgress()
        },
        
        startAutoSlide() {
            if (this.autoSlideInterval) clearInterval(this.autoSlideInterval)
            this.autoSlideInterval = setInterval(() => {
                if (!this.isDragging) this.nextSlide()
            }, 4000)
            this.updateBulletProgress()
        },
        
        stopAutoSlide() {
            if (this.autoSlideInterval) {
                clearInterval(this.autoSlideInterval)
                this.autoSlideInterval = null
            }
        },
        
        updateBulletProgress() {
            this.bulletProgress = this.bulletProgress.map(() => 0)
            this.progressStartTime = performance.now()
            this.animateBulletProgress()
        },
        
        animateBulletProgress() {
            const elapsed = (performance.now() - this.progressStartTime) / 4000
            if (elapsed < 1) {
                this.bulletProgress[this.currentSlide] = elapsed * 100
                requestAnimationFrame(() => this.animateBulletProgress())
            }
        },
        
        getBulletFillWidth(index) {
            return index === this.currentSlide ? `${this.bulletProgress[index]}%` : '0%'
        },
        
        // DRAG & TOUCH
        startDrag(event) {
            if (this.isDragging) return
            this.stopAutoSlide()
            this.isDragging = true
            this.dragStartSlide = this.currentSlide
            
            const clientX = event.type === 'touchstart' ? event.touches[0].clientX : event.clientX
            this.startX = clientX
            this.dragOffset = 0
            document.body.style.userSelect = 'none'
        },
        
        onDrag(event) {
            if (!this.isDragging) return
            const clientX = event.type === 'touchmove' ? event.touches[0].clientX : event.clientX
            let diff = clientX - this.startX
            
            if (this.dragStartSlide === 0 && diff > 0) diff *= 0.3
            if (this.dragStartSlide === this.totalSlides - 1 && diff < 0) diff *= 0.3
            
            this.dragOffset = diff
        },
        
        endDrag() {
            if (!this.isDragging) return
            this.isDragging = false
            document.body.style.userSelect = ''
            
            if (Math.abs(this.dragOffset) > this.dragThreshold) {
                if (this.dragOffset > 0 && this.dragStartSlide > 0) {
                    this.currentSlide = this.dragStartSlide - 1
                } else if (this.dragOffset < 0 && this.dragStartSlide < this.totalSlides - 1) {
                    this.currentSlide = this.dragStartSlide + 1
                }
                this.updateBulletProgress()
            }
            this.dragOffset = 0
            this.startAutoSlide()
        },
        
        startAnimation() {
            this.startTime = performance.now()
            const animate = () => {
                this.$forceUpdate()
                this.animationFrame = requestAnimationFrame(animate)
            }
            this.animationFrame = requestAnimationFrame(animate)
        }
    }
}
</script>

<style scoped>
.container {
    display: flex;
    justify-content: center;
    margin-top: 80px;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

/* СЛАЙДЕР */
.slider-wrapper {
    position: relative;
    margin-bottom: 80px;
    margin-top: 80px;
    width: 100vw;
    max-width: none;
    margin-left: calc(50% - 50vw);
    margin-right: calc(50% - 50vw);
}

.slider-container {
    height: 80vh;
    min-height: 500px;
    overflow: hidden;
    cursor: grab;
    user-select: none;
    touch-action: pan-y pinch-zoom;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
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
    background: linear-gradient(90deg, #ff6b35, #f7931e);
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

/* КАРТОЧКИ */
.list__container {
    width: 1340px;
    height: 841px;
}

.list__container h1 {
    font-size: 142px;
    font-family: "Jeko", sans-serif;
    letter-spacing: -0.02em;
    background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    text-align: center;
}

.col-items {
    margin-top: 80px;
    display: grid;
    grid-template-columns: repeat(4, 276px);
    grid-template-rows: repeat(2, 273px);     
    gap: 10px;  
    justify-content: center;
    position: relative;
}

.row {
    width: 276px;
    height: 273px;
    border-radius: 20px;
    background: linear-gradient(135deg, #F5F8F9 0%, #ffffff 100%);
    color: #ff6b35;
    font-family: "Jeko", sans-serif;
    font-size: 24px;
    font-weight: bold;
    padding: 30px 0 0 40px;
    cursor: pointer;
    position: relative;
    will-change: transform;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.01);
    backdrop-filter: blur(0px);
    transition: all 0.3s ease;
}

.row:hover {
    background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    transform: scale(1.02);
}

.row p {
    color: rgba(0,0,0,0.6);
    font-family: "Jeko", sans-serif;
    margin-top: 12px;
    font-size: 18px;
    font-weight: 500;
    transition: color 0.3s ease;
}

.row:hover p {
    color: white !important;
}

.row::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 20px;
    background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.2) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none;
}

.row:hover::before {
    opacity: 1;
}

/* АНИМАЦИЯ ПОЯВЛЕНИЯ */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.row {
    animation: fadeInUp 0.6s ease-out backwards;
}

.row:nth-child(1) { animation-delay: 0.05s; }
.row:nth-child(2) { animation-delay: 0.1s; }
.row:nth-child(3) { animation-delay: 0.15s; }
.row:nth-child(4) { animation-delay: 0.2s; }
.row:nth-child(5) { animation-delay: 0.25s; }
.row:nth-child(6) { animation-delay: 0.3s; }
.row:nth-child(7) { animation-delay: 0.35s; }
.row:nth-child(8) { animation-delay: 0.4s; }

/* АДАПТИВНОСТЬ */
@media (max-width: 1380px) {
    .list__container, .slider-wrapper {
        width: 100%;
        padding: 0 20px;
    }
    
    .col-items {
        grid-template-columns: repeat(4, minmax(200px, 276px));
        grid-template-rows: repeat(2, minmax(200px, 273px));
        gap: 15px;
    }
    
    .row {
        width: 100%;
        height: auto;
        aspect-ratio: 276 / 273;
        padding: 20px 0 0 20px;
    }
    
    .slider-container {
        height: auto;
        aspect-ratio: 16 / 10;
    }
    
    .list__container h1 {
        font-size: 100px;
    }
}

@media (max-width: 1024px) {
    .col-items {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(4, auto);
        gap: 20px;
    }
    
    .list__container h1 {
        font-size: 80px;
    }
}

@media (max-width: 768px) {
    .list__container h1 {
        font-size: 48px;
    }
    
    .col-items {
        gap: 15px;
    }
    
    .row {
        font-size: 20px;
        padding: 20px 0 0 20px;
    }
    
    .row p {
        font-size: 16px;
    }
    
    .slider-container {
        aspect-ratio: 16 / 9;
    }
    
    .bullet {
        width: 40px;
    }

    .slider-wrapper {
        margin-top: 200px;
    }
}
</style>