<?php

declare(strict_types=1);

/**
 * Cache Warmers Configuration
 *
 * This file is automatically loaded by the cache:warm command.
 * Register cache warmers here to preload frequently accessed data.
 *
 * Example:
 * $warmer->register(
 *     key: 'users.active_count',
 *     callback: fn() => User::where('active', true)->count(),
 *     ttl: 3600,
 *     tags: ['users', 'statistics']
 * );
 */

use App\Models\Note;
use App\Models\Tag;
use Larafony\Framework\Cache\Cache;

$cache = Cache::instance();
$warmer = $cache->warmer();

// Warm frequently accessed data
$warmer
    // Total notes count (frequently displayed on dashboard)
    ->register(
        key: 'statistics.notes.total',
        callback: fn() => Note::count(),
        ttl: 3600, // 1 hour
        tags: ['statistics', 'notes']
    )
    // All tags with their note counts
    ->register(
        key: 'tags.with_counts',
        callback: function () {
            $tags = Tag::all();
            return $tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'notes_count' => $tag->notes()->count(),
                ];
            })->toArray();
        },
        ttl: 1800, // 30 minutes
        tags: ['tags', 'statistics']
    )
    // Recent notes (frequently accessed on homepage)
    ->register(
        key: 'notes.recent',
        callback: fn() => Note::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray(),
        ttl: 600, // 10 minutes
        tags: ['notes']
    )
    // Active tags (tags that have at least one note)
    ->register(
        key: 'tags.active',
        callback: function () {
            return Tag::whereHas('notes')
                ->orderBy('name')
                ->get()
                ->toArray();
        },
        ttl: 1800, // 30 minutes
        tags: ['tags']
    );
