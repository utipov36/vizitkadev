<template>
  <div class="about-container">
    <div class="flex-about">
      <div class="about-text-flex">
        <h1>О НАС</h1>

        <p>
          <span>Команда full-stack: <br /></span>
          разрабатываем responsive<br />
          сайты и визитки, <br />
          администрируем сети<br />
          /VLAN, базы данных и<br />
          сервера. Используем JWT<br />
          /CSRF токены,<br />
          автоматизируем DevOps.<br />
          Результат — быстрый<br />
          запуск и бесперебойная<br />
          работа, даже под<br />
          нагрузкой.
        </p>
      </div>

      <div
        class="image-mask-wrap"
        ref="wrapRef"
        @pointerenter="onEnter"
        @pointerleave="onLeave"
        @pointerdown="startErase"
        @pointermove="handleMove"
        @pointerup="stopErase"
      >
        <img :src="LandingImg" alt="Image" class="main-image" />

        <canvas ref="canvasRef" class="erase-canvas"></canvas>

        <div
          class="brush"
          v-show="showBrush"
          :style="{ left: brushX + 'px', top: brushY + 'px' }"
        ></div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from "vue"
import LandingImg from "@/assets/images/midj1.png"

const wrapRef = ref(null)
const canvasRef = ref(null)

const showBrush = ref(false)
const brushX = ref(0)
const brushY = ref(0)

let ctx = null
let isErasing = false
let resizeObserver = null

const brushSize = 140

/* -----------------------------
   INIT CANVAS (safe resize)
------------------------------ */
const initCanvas = async () => {
  await nextTick()

  const wrap = wrapRef.value
  const canvas = canvasRef.value
  if (!wrap || !canvas) return

  const rect = wrap.getBoundingClientRect()

  let snapshot = null

  if (canvas.width && canvas.height) {
    snapshot = document.createElement("canvas")
    snapshot.width = canvas.width
    snapshot.height = canvas.height

    const sctx = snapshot.getContext("2d")
    sctx.drawImage(canvas, 0, 0)
  }

  canvas.width = rect.width
  canvas.height = rect.height

  ctx = canvas.getContext("2d")

  if (snapshot) {
    ctx.drawImage(
      snapshot,
      0,
      0,
      snapshot.width,
      snapshot.height,
      0,
      0,
      canvas.width,
      canvas.height
    )
  } else {
    ctx.fillStyle = "#ffffff"
    ctx.fillRect(0, 0, canvas.width, canvas.height)
  }
}

/* -----------------------------
   ERASE LOGIC
------------------------------ */
const erase = (x, y) => {
  if (!ctx) return

  ctx.save()
  ctx.globalCompositeOperation = "destination-out"

  const gradient = ctx.createRadialGradient(
    x,
    y,
    0,
    x,
    y,
    brushSize / 2
  )

  gradient.addColorStop(0, "rgba(0,0,0,1)")
  gradient.addColorStop(1, "rgba(0,0,0,0)")

  ctx.fillStyle = gradient
  ctx.beginPath()
  ctx.arc(x, y, brushSize / 2, 0, Math.PI * 2)
  ctx.fill()

  ctx.restore()
}

/* -----------------------------
   POINTER EVENTS
------------------------------ */
const getPos = (e) => {
  const rect = wrapRef.value.getBoundingClientRect()
  return {
    x: e.clientX - rect.left,
    y: e.clientY - rect.top
  }
}

/* hover enter */
const onEnter = () => {
  showBrush.value = true
}

/* hover leave */
const onLeave = () => {
  showBrush.value = false
  isErasing = false
}

/* start drawing */
const startErase = (e) => {
  isErasing = true
  showBrush.value = true
}

/* stop drawing */
const stopErase = () => {
  isErasing = false
}

/* move */
const handleMove = (e) => {
  const { x, y } = getPos(e)

  brushX.value = x
  brushY.value = y

  showBrush.value = true

  if (isErasing) {
    erase(x, y)
  }
}

/* -----------------------------
   LIFE CYCLE
------------------------------ */
onMounted(async () => {
  await initCanvas()

  const img = wrapRef.value?.querySelector("img")
  if (img) img.onload = initCanvas

  resizeObserver = new ResizeObserver(() => {
    initCanvas()
  })

  if (wrapRef.value) {
    resizeObserver.observe(wrapRef.value)
  }

  window.addEventListener("resize", initCanvas)
})

onBeforeUnmount(() => {
  window.removeEventListener("resize", initCanvas)
  resizeObserver?.disconnect()
})
</script>

<style>
.about-container {
  margin-top: 80px;
  margin-bottom: 80px;
  padding: 0 40px;
}

.about-text-flex {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.about-container h1 {
  font-size: 7rem;
  font-family: "Jeko";
  margin: 0;
}

.flex-about p {
  font-size: 2.5rem;
  font-family: "Jeko";
  margin: 0;
  line-height: 1.15;
}

.flex-about span {
  color: var(--vt-c-orange);
}

.flex-about {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 190px;
  margin-top: 80px;
  margin-bottom: 80px;
}

/* IMAGE BLOCK */
.image-mask-wrap {
  position: relative;
  width: 42.375rem;
  max-width: 100%;
  cursor: none;
  user-select: none;
}

.main-image {
  width: 100%;
  display: block;
  border-radius: 18px;
}

.erase-canvas {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border-radius: 18px;
}

.brush {
  position: absolute;
  width: 140px;
  height: 140px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  z-index: 20;

  background: radial-gradient(
    circle,
    rgba(255, 255, 255, 0.35) 0%,
    rgba(255, 255, 255, 0.15) 35%,
    rgba(255, 255, 255, 0.05) 60%,
    transparent 100%
  );

  border: 4px solid rgba(214, 214, 214, 0.9);
  backdrop-filter: blur(2px);
  transition: opacity 0.15s ease;
}

/* MOBILE */
@media (max-width: 768px) {
  .about-container {
    padding: 0 16px;
  }

  .flex-about {
    flex-direction: column;
    gap: 24px;
  }

  .about-container h1 {
    font-size: 4rem;
    text-align: center;
  }

  .flex-about p {
    font-size: 2rem;
    text-align: center;
  }

  .image-mask-wrap {
    width: 100%;
    max-width: 28rem;
  }
}

@media (max-width: 480px) {
  .about-container h1 {
    font-size: 3rem;
  }

  .image-mask-wrap {
    max-width: 20rem;
  }
}

/* IMAGE BLOCK */
.image-mask-wrap {
  position: relative;
  width: 42.375rem;
  max-width: 100%;
  cursor: none;
  user-select: none;
}

/* dashed рамка поверх фото */
.image-mask-wrap::after {
  content: "";
  position: absolute;
  inset: 0;
  border-radius: 18px;
  border: 2px dashed rgba(120, 120, 120, 0.95);
  z-index: 12;
  opacity: 20%;
  pointer-events: none;
}

.main-image {
  width: 100%;
  display: block;
  border-radius: 18px;
}

.erase-canvas {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border-radius: 18px;
}

.brush {
  position: absolute;
  width: 140px;
  height: 140px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  z-index: 20;

  background: radial-gradient(
    circle,
    rgba(255, 255, 255, 0.35) 0%,
    rgba(255, 255, 255, 0.15) 35%,
    rgba(255, 255, 255, 0.05) 60%,
    transparent 100%
  );
  border: 4px solid rgba(214, 214, 214, 0.9);
  backdrop-filter: blur(2px);
  transition: opacity 0.15s ease;
}


.image-mask-wrap {
  position: relative;
}

.main-image {
  position: relative;
  z-index: 1;
}

.erase-canvas {
  position: absolute;
  inset: 0;
  z-index: 2;
}

.brush {
  z-index: 3;
}

.image-mask-wrap {
  position: relative;
  cursor: none;
  user-select: none;
  touch-action: none;
}

.erase-canvas {
  position: absolute;
  inset: 0;
  z-index: 2;
}

.main-image {
  position: relative;
  z-index: 1;
}

.brush {
  position: absolute;
  width: 140px;
  height: 140px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  pointer-events: none;
  z-index: 20;

  background: radial-gradient(
    circle,
    rgba(255, 255, 255, 0.35) 0%,
    rgba(255, 255, 255, 0.15) 35%,
    rgba(255, 255, 255, 0.05) 60%,
    transparent 100%
  );

  border: 4px solid rgba(214, 214, 214, 0.9);
  backdrop-filter: blur(2px);
  transition: opacity 0.15s ease;
}
</style>