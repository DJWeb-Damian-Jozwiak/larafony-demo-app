# 🚀 Quick Start - Inertia.js Demo

## 1️⃣ Install Dependencies
```bash
cd /var/www/projekty/book/demo-app
npm install
```

## 2️⃣ Start Development Servers

**Terminal 1 - Vite (Frontend):**
```bash
npm run dev
```
You should see:
```
  VITE v5.x.x  ready in XXX ms

  ➜  Local:   http://localhost:5173/
  ➜  Network: use --host to expose
```

**Terminal 2 - PHP (Backend):**
```bash
php -S localhost:8000 -t public
```

## 3️⃣ Open Browser

Visit: **http://localhost:8000/inertia/notes**

## 🎯 What You'll See

### Notes List (`/inertia/notes`)
- Card grid showing all notes
- Each note displays:
  - Title & truncated content
  - Author name
  - Tags (if any)
  - "View Details" button

### Note Details (`/inertia/notes/{id}`)
- Full note content
- Author information
- All tags
- Comments with authors
- Back navigation

### Create Note (`/inertia/notes/create`)
- Form with title, content, tags
- Demo form (alert on submit)

## 🔍 Testing SPA Navigation

1. Click any note → Navigate **without page reload** ✨
2. Click "Create New Note" → Navigate **without page reload** ✨
3. Open browser DevTools → Network tab
4. Navigate between pages → See **XHR JSON requests** (not full page loads!)

## ✅ Everything Working?

You should see:
- ✅ Bootstrap styling
- ✅ Vue components rendering
- ✅ Smooth SPA navigation (no flash)
- ✅ Data from database (notes, users, tags)

## 🐛 Troubleshooting

**Blank page?**
- Check if Vite is running on `localhost:5173`
- Check browser console for errors

**404 on routes?**
- Routes are auto-discovered via attributes
- Check `InertiaNotesController.php` has `#[Route(...)]` attributes

**No notes showing?**
- Run database seeders first
- Check `/notes` endpoint (traditional Blade) to verify DB has data

## 📚 Next Steps

Read `INERTIA_DEMO.md` for:
- Architecture details
- Code examples
- Advanced features (lazy props, partial reloads)
