<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <div class="card-header">
            <h2>Login</h2>
          </div>

          <div class="card-body">
            <div v-if="errorMessage" class="alert alert-danger" role="alert">
              {{ errorMessage }}
            </div>

            <form @submit.prevent="submit">
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  class="form-control"
                  :class="{ 'is-invalid': errors.email }"
                  id="email"
                  required
                  placeholder="Enter your email"
                >
                <div v-if="errors.email" class="invalid-feedback">
                  {{ errors.email[0] }}
                </div>
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  v-model="form.password"
                  type="password"
                  class="form-control"
                  :class="{ 'is-invalid': errors.password }"
                  id="password"
                  required
                  placeholder="Enter your password"
                >
                <div v-if="errors.password" class="invalid-feedback">
                  {{ errors.password[0] }}
                </div>
              </div>

              <div class="mb-3 form-check">
                <input
                  v-model="form.remember"
                  type="checkbox"
                  class="form-check-input"
                  id="remember"
                >
                <label class="form-check-label" for="remember">
                  Remember me
                </label>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <Link href="/register" class="text-decoration-none">
                  Don't have an account? Register
                </Link>
                <button type="submit" class="btn btn-primary" :disabled="processing">
                  {{ processing ? 'Logging in...' : 'Login' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'

const form = ref({
  email: '',
  password: '',
  remember: false
})

const processing = ref(false)
const errors = ref({})
const errorMessage = ref('')

const submit = async () => {
  processing.value = true
  errors.value = {}
  errorMessage.value = ''

  try {
    const response = await axios.post('/login', {
      email: form.value.email,
      password: form.value.password,
      remember: form.value.remember
    })

    if (response.data.redirect) {
      window.location.href = response.data.redirect
    }
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors || {}
      errorMessage.value = error.response.data.message || 'Validation failed'
    } else {
      errorMessage.value = 'An error occurred. Please try again.'
    }
  } finally {
    processing.value = false
  }
}
</script>
