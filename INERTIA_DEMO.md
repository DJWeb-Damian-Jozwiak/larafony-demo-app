# Inertia.js Demo - Notes Application

## 🚀 Setup

### 1. Install Node Dependencies
```bash
cd /var/www/projekty/book/demo-app
npm install
```

### 2. Build Frontend Assets

**Development (with HMR):**
```bash
npm run dev
```

**Production:**
```bash
npm run build
```

### 3. Start PHP Server
```bash
php -S localhost:8000 -t public
```

## 📍 Available Routes

### Inertia.js Routes (SPA)
- `GET /inertia/notes` - List all notes (Notes/Index.vue)
- `GET /inertia/notes/{id}` - Show note details (Notes/Show.vue)
- `GET /inertia/notes/create` - Create note form (Notes/Create.vue)

### Traditional Routes (Blade)
- `GET /notes` - Traditional Blade rendering
- `POST /notes` - Create note (traditional form)

## 🧪 Testing the Demo

1. **Start Vite dev server** (in one terminal):
   ```bash
   npm run dev
   ```

2. **Start PHP server** (in another terminal):
   ```bash
   php -S localhost:8000 -t public
   ```

3. **Visit in browser**:
   - Inertia SPA: http://localhost:8000/inertia/notes
   - Traditional: http://localhost:8000/notes

## 🎯 Key Features Demonstrated

### Backend (Larafony Framework)
- ✅ **Instance-based Inertia** with DI
- ✅ **Controller helper**: `$this->inertia('Component', $props)`
- ✅ **PSR-7 compliant** - no `$_SERVER` usage
- ✅ **Partial reloads** support
- ✅ **Lazy props** with closures
- ✅ **Middleware** for shared data
- ✅ **Vite integration** with HMR

### Frontend (Vue 3 + Inertia.js)
- ✅ Vue 3 Composition API (`<script setup>`)
- ✅ Inertia Link component for SPA navigation
- ✅ Bootstrap 5 styling
- ✅ Relationships rendering (user, tags, comments)
- ✅ Data transformation in controller

## 📝 Example Controller Usage

```php
// InertiaNotesController.php
class InertiaNotesController extends Controller
{
    #[Route('/inertia/notes', 'GET')]
    public function index(): ResponseInterface
    {
        $notes = Note::query()->get();

        return $this->inertia('Notes/Index', [
            'notes' => $notes,
        ]);
    }
}
```

## 🔧 Architecture

```
Request → InertiaMiddleware
            ↓
        Controller::inertia()
            ↓
        Container->get(Inertia)
            ↓
    Inertia->render('Notes/Index', $props)
            ↓
        ResponseFactory
            ↓
    First visit: HTML + data-page
    Subsequent: JSON response
```

## 📦 Files Created

### Backend
- `src/Controllers/InertiaNotesController.php` - Inertia endpoints
- `config/middleware.php` - Middleware registration

### Frontend
- `resources/js/Pages/Notes/Index.vue` - Notes list
- `resources/js/Pages/Notes/Show.vue` - Note details
- `resources/js/Pages/Notes/Create.vue` - Create form
- `resources/views/inertia.blade.php` - Root template

### Framework
- `framework/src/Larafony/View/Inertia/Inertia.php` - Core class
- `framework/src/Larafony/View/Inertia/ResponseFactory.php` - Response handling
- `framework/src/Larafony/View/Inertia/Vite.php` - Asset management
- `framework/src/Larafony/Http/Middleware/InertiaMiddleware.php` - Middleware
- `framework/src/Larafony/View/Directives/ViteDirective.php` - @vite directive
- `framework/src/Larafony/Web/Controller.php` - Added inertia() helper

## 🎨 Frontend Stack

- **Vue 3** - Progressive JavaScript framework
- **Inertia.js** - Modern monolith approach
- **Vite** - Fast build tool with HMR
- **Bootstrap 5** - CSS framework

## 💡 Notes

- Create form is demo-only (POST handler not implemented in this example)
- Data is transformed in controller (ORM models → arrays)
- Lazy loading could be used: `'user' => fn() => Auth::user()`
- Shared props can be added via middleware override
