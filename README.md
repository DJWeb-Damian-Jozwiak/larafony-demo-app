# Larafony Skeleton - Demo Application

Full-featured demo application for the **Larafony Framework** showcasing real-world usage patterns and best practices.

## What's Included

This application demonstrates:

### 🗄️ **Database & ORM**
- **Active Record ORM** with relationship management
- **belongsTo** - Note → User relationship
- **hasMany** - User → Comments, Note → Comments
- **belongsToMany** - Note ↔ Tags (pivot table)
- **Query Builder** with fluent API
- **Migrations** using Schema Builder
- **Seeders** for demo data

### 🎨 **Views & Templates**
- **Blade Template Engine** with custom directives
- **Components** (Layout, Alert, InfoCard, StatusBadge)
- **Slots** for component composition
- **@if, @foreach, @component** directives
- Nested components with proper compilation

### 🔄 **Data Handling**
- **Form Requests (DTOs)** with automatic validation
- **Type-safe data transfer** using PHP 8.5 property hooks
- **Union type support** (string|array|null)
- **Automatic hydration** from HTTP requests

### 🛣️ **Routing & Controllers**
- **Attribute-based routing** - `#[Route('/path', 'METHOD')]`
- **Controller base class** with helpers (view, json, redirect)
- **Automatic DTO injection** via FormRequestAwareHandler
- **RESTful API endpoints** with JSON responses

### 💻 **Console Commands**
- **Interactive setup command** - `build:notes`
- **Database connection testing**
- **Automatic migration runner**
- **Data seeding**

### ⚙️ **PHP 8.5 Features**
- **Property hooks** for computed properties
- **Asymmetric visibility** - `public protected(set)`
- **Attributes** for routing and relationships
- **Union types** and null-safe operators

## Installation

```bash
composer create-project larafony/skeleton my-app
cd my-app
```

## Setup

Run the interactive installation command:

```bash
php8.5 bin/larafony build:notes
```

The installer will:
- Check your database connection
- Prompt for database credentials if needed (interactive setup)
- Create the database if it doesn't exist
- Run all migrations
- Seed demo data (user, tags, notes)

**Note**: The command will automatically configure your `.env` file based on your input.

## Development Server

Start the built-in PHP server:

```bash
php -S localhost:8000 -t public
```

Visit: http://localhost:8000/notes

## Features Showcase

### 📝 **Web Interface** - `/notes`

The notes application demonstrates a complete CRUD workflow:

**List Notes**
- Displays all notes with user, tags, and comment count
- Shows relationship loading (N+1 prevention)
- Blade components for consistent UI

**Create Note**
- HTML form with validation
- Tag creation and attachment (many-to-many)
- DTO-based request handling
- Automatic tag parsing (comma-separated)

**View Note Details**
- Author information (belongsTo)
- All tags (belongsToMany)
- All comments (hasMany)

### 🔌 **API Endpoints**

**`GET /api/notes`** - List all notes with relations
```json
{
  "notes": [
    {
      "id": 1,
      "title": "Welcome to Larafony",
      "content": "...",
      "user": { "id": 1, "name": "Admin" },
      "tags": [ { "id": 1, "name": "framework" } ],
      "comments": [ { "id": 1, "content": "Great!" } ]
    }
  ]
}
```

**`POST /api/notes`** - Create new note
```json
{
  "title": "My Note",
  "content": "Note content",
  "tags": ["php", "framework"]
}
```

**`POST /api/comments`** - Add comment
```json
{
  "note_id": 1,
  "content": "This is a comment"
}
```

### 💡 **Code Examples**

**ORM Relationships with Attributes**
```php
class Note extends Model
{
    #[BelongsTo(related: User::class, foreign_key: 'user_id')]
    public ?User $user {
        get => $this->relations->getRelation('user');
    }

    #[BelongsToMany(
        related: Tag::class,
        pivot_table: 'note_tag',
        foreign_pivot_key: 'note_id',
        related_pivot_key: 'tag_id'
    )]
    public array $tags {
        get => $this->relations->getRelation('tags');
    }
}
```

**DTO with Property Hooks**
```php
class CreateNoteDto
{
    #[IsValidated]
    public protected(set) string|array|null $tags {
        get {
            if (!isset($this->tags)) return null;
            if (is_array($this->tags)) return $this->tags;
            return array_map('trim', explode(',', $this->tags));
        }
        set => $this->tags = $value;
    }
}
```

**Controller with Automatic DTO Injection**
```php
#[Route('/notes', 'POST')]
public function store(CreateNoteDto $dto): ResponseInterface
{
    // $dto is automatically created and validated from request
    $note = new Note()->fill([
        'title' => $dto->title,
        'content' => $dto->content,
    ]);
    $note->save();

    if ($dto->tags) {
        $note->attachTags($tagIds);
    }

    return $this->redirect('/notes');
}
```

## Requirements

- PHP 8.5 or higher
- MySQL/PostgreSQL/SQLite
- Composer

## License

MIT
