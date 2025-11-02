<template>
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <Link href="/inertia/notes" class="btn btn-sm btn-outline-secondary mb-3">
          ← Back to Notes
        </Link>

        <div class="card">
          <div class="card-header">
            <h2>{{ note.title }}</h2>
            <small class="text-muted">
              By {{ note.user.name }} • {{ formatDate(note.created_at) }}
            </small>
          </div>

          <div class="card-body">
            <div class="mb-4">
              <p class="card-text" style="white-space: pre-wrap">{{ note.content }}</p>
            </div>

            <div v-if="note.tags.length > 0" class="mb-3">
              <strong>Tags:</strong>
              <span
                v-for="tag in note.tags"
                :key="tag.id"
                class="badge bg-secondary ms-2"
              >
                {{ tag.name }}
              </span>
            </div>

            <hr>

            <div class="mt-4">
              <h4>Comments ({{ note.comments.length }})</h4>

              <div v-if="note.comments.length === 0" class="alert alert-light">
                No comments yet.
              </div>

              <div v-else class="mt-3">
                <div
                  v-for="comment in note.comments"
                  :key="comment.id"
                  class="card mb-2"
                >
                  <div class="card-body">
                    <p class="mb-1">{{ comment.content }}</p>
                    <small class="text-muted">
                      - {{ comment.user.name }} • {{ formatDate(comment.created_at) }}
                    </small>
                  </div>
                </div>
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
  note: Object,
})

const formatDate = (dateString) => {
  if (!dateString) return 'Unknown date'
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>
