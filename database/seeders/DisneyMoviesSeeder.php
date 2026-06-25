<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\Tape;

class DisneyMoviesSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ensure Animation category exists ─────────────────────────────
        $animation = Category::firstOrCreate(
            ['name' => 'Animation'],
            ['description' => 'Animated films for all ages']
        );

        // ── Disney Actors ─────────────────────────────────────────────────
        $actorData = [
            ['name' => 'Matthew Broderick',  'bio' => 'American actor, voice of adult Simba in The Lion King (1994).'],
            ['name' => 'Jeremy Irons',        'bio' => 'British actor, voice of Scar in The Lion King (1994).'],
            ['name' => 'James Earl Jones',    'bio' => 'Legendary actor, voice of Mufasa in The Lion King (1994).'],
            ['name' => 'Jodi Benson',         'bio' => 'American actress, voice of Ariel in The Little Mermaid (1989).'],
            ['name' => 'Pat Carroll',         'bio' => 'American actress, voice of Ursula in The Little Mermaid (1989).'],
            ['name' => 'Paige O\'Hara',       'bio' => 'American actress, voice of Belle in Beauty and the Beast (1991).'],
            ['name' => 'Jerry Orbach',        'bio' => 'American actor, voice of Lumière in Beauty and the Beast (1991).'],
            ['name' => 'Scott Weinger',       'bio' => 'American actor, speaking voice of Aladdin in Aladdin (1992).'],
            ['name' => 'Robin Williams',      'bio' => 'Legendary comedian and actor, voice of the Genie in Aladdin (1992).'],
            ['name' => 'Idina Menzel',        'bio' => 'American actress and singer, voice of Elsa in Frozen (2013).'],
            ['name' => 'Kristen Bell',        'bio' => 'American actress, voice of Anna in Frozen (2013).'],
        ];

        foreach ($actorData as $actor) {
            Actor::firstOrCreate(['name' => $actor['name']], $actor);
        }

        $this->command->info('✅ Disney actors seeded.');

        // ── Load actor references ─────────────────────────────────────────
        $broderick = Actor::where('name', 'Matthew Broderick')->first();
        $irons      = Actor::where('name', 'Jeremy Irons')->first();
        $jones      = Actor::where('name', 'James Earl Jones')->first();
        $benson     = Actor::where('name', 'Jodi Benson')->first();
        $carroll    = Actor::where('name', 'Pat Carroll')->first();
        $ohara      = Actor::where('name', "Paige O'Hara")->first();
        $orbach     = Actor::where('name', 'Jerry Orbach')->first();
        $weinger    = Actor::where('name', 'Scott Weinger')->first();
        $williams   = Actor::where('name', 'Robin Williams')->first();
        $menzel     = Actor::where('name', 'Idina Menzel')->first();
        $bell       = Actor::where('name', 'Kristen Bell')->first();

        // ── Disney Movies & Tapes ─────────────────────────────────────────
        $moviesData = [
            [
                'movie' => [
                    'movie_id'    => 'MOV-006',
                    'title'       => 'The Lion King',
                    'category_id' => $animation->id,
                    'director'    => 'Roger Allers, Rob Minkoff',
                    'year'        => 1994,
                    'description' => 'A young lion prince flees his kingdom after the murder of his father, only to learn the true meaning of responsibility and bravery.',
                    'image'       => 'movies/lion_king.jpg',
                ],
                'actors' => [$broderick->id, $irons->id, $jones->id],
                'tapes'  => [
                    ['tape_number' => 'T-0011', 'format' => 'VHS', 'shelf_location' => 'D-01', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0012', 'format' => 'DVD', 'shelf_location' => 'D-01', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0013', 'format' => 'VHS', 'shelf_location' => 'D-01', 'condition' => 'Fair',  'status' => 'available'],
                ],
            ],
            [
                'movie' => [
                    'movie_id'    => 'MOV-007',
                    'title'       => 'The Little Mermaid',
                    'category_id' => $animation->id,
                    'director'    => 'Ron Clements, John Musker',
                    'year'        => 1989,
                    'description' => 'A mermaid princess makes a deal with a sea witch to become human and pursue the man she loves, at the cost of her voice.',
                    'image'       => 'movies/little_mermaid.png',
                ],
                'actors' => [$benson->id, $carroll->id],
                'tapes'  => [
                    ['tape_number' => 'T-0014', 'format' => 'VHS', 'shelf_location' => 'D-02', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0015', 'format' => 'DVD', 'shelf_location' => 'D-02', 'condition' => 'Good',  'status' => 'available'],
                ],
            ],
            [
                'movie' => [
                    'movie_id'    => 'MOV-008',
                    'title'       => 'Beauty and the Beast',
                    'category_id' => $animation->id,
                    'director'    => 'Gary Trousdale, Kirk Wise',
                    'year'        => 1991,
                    'description' => 'An intelligent and beautiful young woman is taken prisoner by a mysterious beast, only to discover his enchanted past and kind heart.',
                    'image'       => 'movies/beauty_beast.jpg',
                ],
                'actors' => [$ohara->id, $orbach->id],
                'tapes'  => [
                    ['tape_number' => 'T-0016', 'format' => 'VHS', 'shelf_location' => 'D-03', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0017', 'format' => 'DVD', 'shelf_location' => 'D-03', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0018', 'format' => 'Beta','shelf_location' => 'D-03', 'condition' => 'Fair',  'status' => 'available'],
                ],
            ],
            [
                'movie' => [
                    'movie_id'    => 'MOV-009',
                    'title'       => 'Aladdin',
                    'category_id' => $animation->id,
                    'director'    => 'Ron Clements, John Musker',
                    'year'        => 1992,
                    'description' => 'A street-rat finds a magic lamp containing a Genie with the power to grant three wishes, using it to woo a princess and defeat an evil sorcerer.',
                    'image'       => 'movies/aladdin.jpg',
                ],
                'actors' => [$weinger->id, $williams->id],
                'tapes'  => [
                    ['tape_number' => 'T-0019', 'format' => 'VHS', 'shelf_location' => 'D-04', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0020', 'format' => 'DVD', 'shelf_location' => 'D-04', 'condition' => 'Good',  'status' => 'available'],
                ],
            ],
            [
                'movie' => [
                    'movie_id'    => 'MOV-010',
                    'title'       => 'Frozen',
                    'category_id' => $animation->id,
                    'director'    => 'Chris Buck, Jennifer Lee',
                    'year'        => 2013,
                    'description' => 'When a fearless princess sets off on an epic journey with a rugged ice harvester and a reindeer to find her estranged sister Elsa, whose icy powers have trapped the kingdom in eternal winter, she must face a villain who wants to take over the throne.',
                    'image'       => 'movies/frozen.jpg',
                ],
                'actors' => [$menzel->id, $bell->id],
                'tapes'  => [
                    ['tape_number' => 'T-0021', 'format' => 'DVD', 'shelf_location' => 'D-05', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0022', 'format' => 'DVD', 'shelf_location' => 'D-05', 'condition' => 'Good',  'status' => 'available'],
                    ['tape_number' => 'T-0023', 'format' => 'VHS', 'shelf_location' => 'D-05', 'condition' => 'Good',  'status' => 'available'],
                ],
            ],
        ];

        foreach ($moviesData as $entry) {
            $movie = Movie::firstOrCreate(
                ['movie_id' => $entry['movie']['movie_id']],
                $entry['movie']
            );

            $movie->actors()->syncWithoutDetaching(array_filter($entry['actors']));

            foreach ($entry['tapes'] as $tape) {
                Tape::firstOrCreate(
                    ['tape_number' => $tape['tape_number']],
                    array_merge($tape, ['movie_id' => $movie->id])
                );
            }
        }

        $this->command->info('✅ Disney movies & tapes seeded.');
    }
}
