<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-6 mx-auto">
        <div class="card">
          <div class="card-header">
            <h2>Register</h2>
          </div>

          <div class="card-body">
            <div v-if="errorMessage" class="alert alert-danger" role="alert">
              {{ errorMessage }}
            </div>

            <form @submit.prevent="submit">
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input
                  v-model="form.username"
                  type="text"
                  class="form-control"
                  :class="{ 'is-invalid': errors.username }"
                  id="username"
                  required
                  placeholder="Choose a username"
                >
                <div v-if="errors.username" class="invalid-feedback">
                  {{ errors.username[0] }}
                </div>
                <small class="text-muted">Minimum 3 characters</small>
              </div>

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
                  placeholder="Choose a strong password"
                >
                <div v-if="errors.password" class="invalid-feedback">
                  {{ errors.password[0] }}
                </div>
                <small class="text-muted">Minimum 8 characters. Password will be checked against data breaches.</small>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <Link href="/login" class="text-decoration-none">
                  Already have an account? Login
                </Link>
                <button type="submit" class="btn btn-primary" :disabled="processing">
                  {{ processing ? 'Registering...' : 'Register' }}
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
  username: '',
  email: '',
  password: ''
})

const processing = ref(false)
const errors = ref({})
const errorMessage = ref('')

const submit = async () => {
  processing.value = true
  errors.value = {}
  errorMessage.value = ''

  try {
    const response = await axios.post('/register', {
      username: form.value.username,
      email: form.value.email,
      password: form.value.password
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
