<template>
  <Teleport to="body">
    <Transition name="modal-fade">
      <div v-if="isOpen" class="modal-overlay" @click.self="closeModal">
        <div class="modal-container">
          <button class="modal-close" @click="closeModal">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </button>

          <div class="modal-header">
            <h2>Оставить заявку</h2>
            <p>Заполните форму и мы свяжемся с вами в ближайшее время</p>
          </div>

          <form @submit.prevent="handleSubmit" class="modal-form" novalidate>
            <div class="form-group">
              <label for="name">Имя *</label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Введите ваше имя"
                :class="{ 'error': errors.name }"
                @input="validateField('name')"
                @blur="validateField('name')"
              />
              <span v-if="errors.name" class="error-message">{{ errors.name }}</span>
            </div>

            <div class="form-group">
              <label for="phone">Телефон *</label>
              <input
                id="phone"
                v-model="form.phone"
                type="tel"
                placeholder="+7 (___) ___-__-__"
                :class="{ 'error': errors.phone }"
                @input="applyPhoneMask"
                @blur="validateField('phone')"
                maxlength="18"
              />
              <span v-if="errors.phone" class="error-message">{{ errors.phone }}</span>
            </div>

            <div class="form-group">
              <label for="email">Email *</label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="example@mail.com"
                :class="{ 'error': errors.email }"
                @input="validateField('email')"
                @blur="validateField('email')"
              />
              <span v-if="errors.email" class="error-message">{{ errors.email }}</span>
            </div>

            <div class="form-group">
              <label for="message">Комментарий</label>
              <textarea
                id="message"
                v-model="form.message"
                rows="3"
                placeholder="Расскажите о вашем проекте..."
              ></textarea>
            </div>

            <div class="checkbox-group">
              <label class="checkbox-label">
                <input 
                  type="checkbox" 
                  v-model="form.agreement"
                  :class="{ 'error': errors.agreement }"
                  @change="validateField('agreement')"
                />
                <span class="checkbox-custom"></span>
                <span class="checkbox-text">
                  Я соглашаюсь на обработку персональных данных и принимаю 
                  <a href="#" @click.prevent="showPrivacyPolicy">политику конфиденциальности</a>
                </span>
              </label>
              <span v-if="errors.agreement" class="error-message">{{ errors.agreement }}</span>
            </div>

            <button type="submit" class="submit-btn" :disabled="isSubmitting">
              <span v-if="!isSubmitting">Отправить заявку</span>
              <span v-else class="loader"></span>
            </button>
          </form>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { toast } from 'vue-sonner'
import { ref, reactive, watch, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  modelValue: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'submitted'])

const isOpen = ref(false)
const isSubmitting = ref(false)

const form = reactive({
  name: '',
  phone: '',
  email: '',
  message: '',
  agreement: false
})

const errors = reactive({
  name: '',
  phone: '',
  email: '',
  agreement: ''
})

const api = axios.create({
  baseURL: import.meta.env.VITE_BACKEND_URL
})

const applyPhoneMask = (event) => {
  let value = event.target.value.replace(/\D/g, '')
  if (value.length > 11) value = value.slice(0, 11)

  let formattedValue = ''

  if (value.length > 0) {
    if (value[0] === '7' || value[0] === '8') {
      formattedValue = '+' + value[0]
      value = value.slice(1)
    } else {
      formattedValue = '+7'
      if (value[0] !== '9') value = value.slice(1)
    }
  }

  if (value.length > 0) {
    formattedValue += ' ('
    formattedValue += value.slice(0, 3)
    if (value.length >= 3) {
      formattedValue += ') '
      formattedValue += value.slice(3, 6)
      if (value.length >= 6) {
        formattedValue += '-'
        formattedValue += value.slice(6, 8)
        if (value.length >= 8) {
          formattedValue += '-'
          formattedValue += value.slice(8, 10)
        }
      }
    }
  }

  form.phone = formattedValue.trim()
  validateField('phone')
}

const validatePhoneFormat = (phone) => {
  const digits = phone.replace(/\D/g, '')
  if (digits.length === 11 && (digits[0] === '7' || digits[0] === '8')) return true
  if (digits.length === 10) return true
  return false
}

const validateEmailFormat = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)

const validateField = (fieldName) => {
  switch (fieldName) {
    case 'name':
      errors.name = !form.name.trim()
        ? 'Введите ваше имя'
        : form.name.length < 2
          ? 'Имя должно содержать минимум 2 символа'
          : ''
      break
    case 'phone':
      errors.phone = !form.phone.replace(/\D/g, '').length
        ? 'Введите номер телефона'
        : !validatePhoneFormat(form.phone)
          ? 'Введите корректный номер телефона'
          : ''
      break
    case 'email':
      errors.email = !form.email.trim()
        ? 'Введите email адрес'
        : !validateEmailFormat(form.email)
          ? 'Введите корректный email адрес'
          : ''
      break
    case 'agreement':
      errors.agreement = !form.agreement
        ? 'Необходимо согласие на обработку персональных данных'
        : ''
      break
  }
}

const validateForm = () => {
  validateField('name')
  validateField('phone')
  validateField('email')
  validateField('agreement')
  return !errors.name && !errors.phone && !errors.email && !errors.agreement
}

const handleSubmit = async () => {
  if (!validateForm()) return

  isSubmitting.value = true

  try {
    const payload = {
      name: form.name,
      phone: form.phone.replace(/\D/g, ''),
      email: form.email,
      message: form.message,
      agreement: form.agreement
    }

    const response = await api.post('/applications', payload)

    emit('submitted', response.data.data)
    toast.success('Заявка успешно отправлена')
    closeModal()
  } catch (error) {
    if (error.response?.status === 422) {
      const serverErrors = error.response.data.errors || {}
      errors.name = serverErrors.name?.[0] || ''
      errors.phone = serverErrors.phone?.[0] || ''
      errors.email = serverErrors.email?.[0] || ''
      errors.agreement = serverErrors.agreement?.[0] || ''
    } else {
      toast.error('Ошибка отправки заявки')
    }
  } finally {
    isSubmitting.value = false
  }
}

const closeModal = () => {
  isOpen.value = false
}

const resetForm = () => {
  form.name = ''
  form.phone = ''
  form.email = ''
  form.message = ''
  form.agreement = false
  errors.name = ''
  errors.phone = ''
  errors.email = ''
  errors.agreement = ''
}

const showPrivacyPolicy = () => {
  toast.info('Ваши данные не будут переданы третьим лицам.')
}

const initPhoneMask = () => {
  if (!form.phone) form.phone = '+7 '
}

watch(() => props.modelValue, (newVal) => {
  isOpen.value = newVal
  if (newVal) resetForm()
})

watch(isOpen, (newVal) => {
  emit('update:modelValue', newVal)
})

let phoneInput = null

onMounted(() => {
  setTimeout(() => {
    phoneInput = document.getElementById('phone')
    if (phoneInput) phoneInput.addEventListener('focus', initPhoneMask)
  }, 100)
})

onUnmounted(() => {
  if (phoneInput) phoneInput.removeEventListener('focus', initPhoneMask)
})
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(4px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 20px;
}

.modal-container {
  background: white;
  border-radius: 20px;
  width: 100%;
  max-width: 520px;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

.modal-close {
  position: absolute;
  top: 20px;
  right: 20px;
  background: none;
  border: none;
  cursor: pointer;
  color: #666;
  padding: 4px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 8px;
  transition: all 0.2s ease;
  z-index: 10;
}

.modal-close:hover {
  background: #f0f0f0;
  color: #333;
}

.modal-header {
  padding: 32px 32px 0 32px;
  text-align: center;
}

.modal-header h2 {
  font-size: 28px;
  font-weight: 600;
  color: #1a1a1a;
  margin-bottom: 8px;
  font-family: "Montserrat", sans-serif;
}

.modal-header p {
  font-size: 14px;
  color: #666;
  margin: 0;
  font-family: "Jeko", sans-serif;
}

.modal-form {
  padding: 24px 32px 32px 32px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 14px;
  font-weight: 500;
  color: #333;
  margin-bottom: 8px;
  font-family: "Jeko", sans-serif;
}

.form-group label[for="email"]::after {
  content: " *";
  color: #dc2626;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1.5px solid #e0e0e0;
  border-radius: 10px;
  font-size: 14px;
  font-family: "Jeko", sans-serif;
  transition: all 0.2s ease;
  background: white;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #C96935;
  box-shadow: 0 0 0 3px rgba(201, 105, 53, 0.1);
}

/* Стили для ошибок */
.form-group input.error,
.form-group textarea.error {
  border-color: #dc2626;
  background-color: #fff5f5;
}

.form-group input.error:focus,
.form-group textarea.error:focus {
  border-color: #dc2626;
  box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.error-message {
  display: block;
  font-size: 13px;
  color: #dc2626;
  margin-top: 6px;
  font-family: "Jeko", sans-serif;
  animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.checkbox-group {
  margin: 24px 0;
}

.checkbox-label {
  display: flex;
  align-items: flex-start;
  cursor: pointer;
  position: relative;
  padding-left: 28px;
  user-select: none;
}

.checkbox-label input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.checkbox-custom {
  position: absolute;
  top: 2px;
  left: 0;
  height: 18px;
  width: 18px;
  background: white;
  border: 2px solid #d0d0d0;
  border-radius: 4px;
  transition: all 0.2s ease;
}

.checkbox-label input.error ~ .checkbox-custom {
  border-color: #dc2626;
}

.checkbox-label input:checked ~ .checkbox-custom {
  background: #C96935;
  border-color: #C96935;
}

.checkbox-label input:checked ~ .checkbox-custom::after {
  content: '';
  position: absolute;
  left: 5px;
  top: 2px;
  width: 4px;
  height: 8px;
  border: solid white;
  border-width: 0 2px 2px 0;
  transform: rotate(45deg);
}

.checkbox-label input:focus ~ .checkbox-custom {
  box-shadow: 0 0 0 3px rgba(201, 105, 53, 0.2);
}

.checkbox-text {
  font-size: 13px;
  color: #555;
  line-height: 1.4;
  font-family: "Jeko", sans-serif;
}

.checkbox-text a {
  color: #C96935;
  text-decoration: none;
  font-weight: 500;
}

.checkbox-text a:hover {
  text-decoration: underline;
}

.submit-btn {
  width: 100%;
  padding: 14px;
  background: linear-gradient(135deg, #C96935 0%, #9F4626 100%);
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  font-weight: 600;
  font-family: "Jeko", sans-serif;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 10px 20px rgba(201, 105, 53, 0.3);
}

.submit-btn:active:not(:disabled) {
  transform: translateY(0);
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.loader {
  display: inline-block;
  width: 20px;
  height: 20px;
  border: 2px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 0.6s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* Анимации */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-fade-enter-active .modal-container,
.modal-fade-leave-active .modal-container {
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.modal-fade-enter-from .modal-container,
.modal-fade-leave-to .modal-container {
  transform: scale(0.95);
  opacity: 0;
}

.modal-container::-webkit-scrollbar {
  width: 6px;
}

.modal-container::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.modal-container::-webkit-scrollbar-thumb {
  background: #C96935;
  border-radius: 3px;
}

@media (max-width: 640px) {
  .modal-header {
    padding: 24px 24px 0 24px;
  }
  
  .modal-header h2 {
    font-size: 24px;
  }
  
  .modal-form {
    padding: 20px 24px 24px 24px;
  }
  
  .form-group input,
  .form-group textarea {
    padding: 10px 14px;
  }
}
@media (max-width: 500px) {
  .modal-container {
    position: absolute;
    left: 0;
    bottom: 0;
  }
   .modal-container {
    width: 100vw;
    max-width: 100%;
    left: 0;
    right: 0;
    margin: 0; /* убираем центрирование */
    box-shadow: none; /* опционально: убрать тень по бокам */
  }

  .modal-form {
    padding: 20px 16px 24px 16px; /* уменьшаем внутренние отступы */
  }
}


</style>