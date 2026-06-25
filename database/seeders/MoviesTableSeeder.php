<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\Tape;

/**
 * MoviesTableSeeder
 * ─────────────────────────────────────────────────────────────────────
 * Reads ALL movie data from:  database/seeders/data/movies.php
 *
 * HOW TO ADD A NEW MOVIE:
 *   1. Drop the poster image into:  storage/app/public/movies/
 *   2. Add your movie entry to:     database/seeders/data/movies.php
 *   3. Run:  php artisan db:seed --class=MoviesTableSeeder
 *
 * This seeder is IDEMPOTENT — safe to re-run anytime.
 *   • Existing records are UPDATED (not duplicated)
 *   • New records are CREATED
 *   • Image is stored as  movies/<filename>  when a filename is provided
 * ─────────────────────────────────────────────────────────────────────
 */
class MoviesTableSeeder extends Seeder
{
    /** Path prefix used in the DB and served via /storage/movies/<file> */
    const IMAGE_PREFIX = 'movies/';

    /** Absolute path where poster images must be placed */
    const IMAGE_DISK_PATH = 'storage/app/public/movies/';

    public function run(): void
    {
        $catalog = require database_path('seeders/data/movies.php');

        $this->command->info('');
        $this->command->info('📽️  MovieTableSeeder — processing ' . count($catalog) . ' movie(s)…');
        $this->command->info('');

        foreach ($catalog as $entry) {
            // ── 1. Ensure the category exists ──────────────────────────────
            $category = Category::firstOrCreate(
                ['name' => $entry['category']],
                ['description' => $entry['category'] . ' films']
            );

            // ── 2. Resolve image path ───────────────────────────────────────
            $imagePath = null;
            if (!empty($entry['image'])) {
                $diskFile = base_path(self::IMAGE_DISK_PATH . $entry['image']);
                if (file_exists($diskFile)) {
                    $imagePath = self::IMAGE_PREFIX . $entry['image'];
                } else {
                    $this->command->warn(
                        "  ⚠  Image not found for [{$entry['title']}]: " . $entry['image'] .
                        "\n     → Place it in: storage/app/public/movies/"
                    );
                }
            }

            // ── 3. Upsert movie (update if already exists) ─────────────────
            $movie = Movie::updateOrCreate(
                ['movie_id' => $entry['movie_id']],
                [
                    'title'       => $entry['title'],
                    'category_id' => $category->id,
                    'director'    => $entry['director'],
                    'year'        => $entry['year'],
                    'description' => $entry['description'] ?? null,
                    'image'       => $imagePath,
                ]
            );

            $status = $movie->wasRecentlyCreated ? '✅ CREATED' : '🔄 UPDATED';
            $imgTag = $imagePath ? "🖼  {$entry['image']}" : '📭 no image';
            $this->command->line("  {$status}  [{$entry['movie_id']}] {$entry['title']}  — {$imgTag}");

            // ── 4. Ensure actors exist & link them ─────────────────────────
            $actorIds = [];
            foreach ($entry['actors'] as $actorData) {
                $actor = Actor::firstOrCreate(
                    ['name' => $actorData['name']],
                    ['bio'  => $actorData['bio'] ?? null]
                );
                $actorIds[] = $actor->id;
            }
            $movie->actors()->syncWithoutDetaching($actorIds);

            // ── 5. Ensure tapes exist (never duplicate by tape_number) ──────
            foreach ($entry['tapes'] as $tape) {
                Tape::firstOrCreate(
                    ['tape_number' => $tape['tape_number']],
                    [
                        'movie_id'      => $movie->id,
                        'format'        => $tape['format'],
                        'shelf_location'=> $tape['shelf'] ?? null,
                        'condition'     => $tape['condition'] ?? 'Good',
                        'status'        => $tape['status']  ?? 'available',
                    ]
                );
            }
        }

        $this->command->info('');
        $this->command->info('─────────────────────────────────────────────────');
        $this->command->info('✅  All done! ' . Movie::count() . ' total movies in database.');
        $this->command->info('');
        $this->command->info('📁  Poster images folder:');
        $this->command->info('      ' . base_path(self::IMAGE_DISK_PATH));
        $this->command->info('');
        $this->command->info('📋  To add more movies:');
        $this->command->info('      1. Drop image into storage/app/public/movies/');
        $this->command->info('      2. Add entry in database/seeders/data/movies.php');
        $this->command->info('      3. php artisan db:seed --class=MoviesTableSeeder');
        $this->command->info('─────────────────────────────────────────────────');
        $this->command->info('');
    }
}
