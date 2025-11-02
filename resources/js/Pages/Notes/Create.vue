<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <Link href="/inertia/notes" class="btn btn-sm btn-outline-secondary mb-3">
          ← Back to Notes
        </Link>

        <div class="card">
          <div class="card-header">
            <h2>Create New Note</h2>
          </div>

          <div class="card-body">
            <form @submit.prevent="submit">
              <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input
                  v-model="form.title"
                  type="text"
                  class="form-control"
                  id="title"
                  required
                  placeholder="Enter note title"
                >
              </div>

              <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea
                  v-model="form.content"
                  class="form-control"
                  id="content"
                  rows="6"
                  required
                  placeholder="Write your note content here..."
                ></textarea>
              </div>

              <div class="mb-3">
                <label for="tags" class="form-label">Tags (comma separated)</label>
                <input
                  v-model="form.tags"
                  type="text"
                  class="form-control"
                  id="tags"
                  placeholder="e.g., work, personal, ideas"
                >
                <small class="text-muted">
                  Available tags: {{ availableTags.map(t => t.name).join(', ') }}
                </small>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <Link href="/inertia/notes" class="btn btn-secondary">
                  Cancel
                </Link>
                <button type="submit" class="btn btn-primary" :disabled="processing">
                  {{ processing ? 'Creating...' : 'Create Note' }}
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
import { Link, router } from '@inertiajs/vue3'

const props = defineProps({
  tags: {
    type: Array,
    default: () => []
  }
})

const form = ref({
  title: '',
  content: '',
  tags: ''
})

const processing = ref(false)
const availableTags = ref(props.tags)

const submit = () => {
  processing.value = true

  // Note: This is a demo - in real app you'd use router.post()
  // For now, we just show an alert since we don't have POST handler in Inertia
  alert('Note creation via Inertia would be implemented here!\n\nData:\n' + JSON.stringify(form.value, null, 2))
  processing.value = false

  // In real implementation:
  // router.post('/inertia/notes', {
  //   title: form.value.title,
  //   content: form.value.content,
  //   tags: form.value.tags.split(',').map(t => t.trim()).filter(Boolean)
  // })
}
</script>
