<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1>Notes</h1>
          <Link href="/inertia/notes/create" class="btn btn-primary">
            Create New Note
          </Link>
        </div>

        <div v-if="notes.length === 0" class="alert alert-info">
          No notes found. Create your first note!
        </div>

        <div v-else class="row">
          <div v-for="note in notes" :key="note.id" class="col-md-6 mb-3">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">{{ note.title }}</h5>
                <p class="card-text">{{ truncate(note.content, 100) }}</p>

                <div class="mb-2">
                  <small class="text-muted">
                    By {{ note.user.name }}
                  </small>
                </div>

                <div v-if="note.tags.length > 0" class="mb-2">
                  <span
                    v-for="tag in note.tags"
                    :key="tag.id"
                    class="badge bg-secondary me-1"
                  >
                    {{ tag.name }}
                  </span>
                </div>

                <Link
                  :href="`/inertia/notes/${note.id}`"
                  class="btn btn-sm btn-outline-primary"
                >
                  View Details
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  notes: Array,
})

const truncate = (text, length) => {
  if (text.length <= length) return text
  return text.substring(0, length) + '...'
}
</script>
