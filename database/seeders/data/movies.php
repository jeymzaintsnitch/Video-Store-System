<?php

/**
 * ═══════════════════════════════════════════════════════════════════════
 *  VIDEOSTORE — MOVIE CATALOG DATA FILE
 * ═══════════════════════════════════════════════════════════════════════
 *
 *  HOW TO ADD A NEW MOVIE:
 *  ────────────────────────────────────────────────────────────────────
 *  1. Copy your poster image (.jpg / .png / .webp) into:
 *         storage/app/public/movies/
 *
 *  2. Add a new entry to the $movies array below following the template.
 *
 *  3. Run the seeder (safe to re-run anytime — no duplicates):
 *         php artisan db:seed --class=MoviesTableSeeder
 *
 *  ────────────────────────────────────────────────────────────────────
 *  FIELD REFERENCE:
 *
 *  movie_id     → Unique catalog ID. Format: MOV-001 … MOV-999
 *  title        → Full movie title
 *  category     → One of: Comedy | Suspense | Drama | Action | SciFi |
 *                          Horror | Romance | Animation | Western | Thriller
 *  director     → Director name(s)
 *  year         → 4-digit release year (1888 – current+2)
 *  description  → Short synopsis (optional, leave '' to skip)
 *  image        → Filename only (e.g. 'lion_king.jpg') — null if no image yet
 *  actors       → Array of actor names (will be created if they don't exist)
 *  tapes        → Array of physical copy records:
 *                   tape_number  → Unique T-0000 format
 *                   format       → VHS | Beta | DVD
 *                   shelf        → Zone-Number format e.g. A-01 (or null)
 *                   condition    → Good | Fair | Poor
 * ═══════════════════════════════════════════════════════════════════════
 */

return [

    // ──────────────────────────────────────────────────────────────────
    //  CLASSIC FILMS
    // ──────────────────────────────────────────────────────────────────

    [
        'movie_id'    => 'MOV-001',
        'title'       => 'The African Queen',
        'category'    => 'Drama',
        'director'    => 'John Huston',
        'year'        => 1951,
        'description' => 'A mismatched couple travel down a dangerous river during World War I.',
        'image'       => 'the_african_queen.jpg',           // ← Drop your image in storage/app/public/movies/ then add filename here
        'actors'      => [
            ['name' => 'Humphrey Bogart',   'bio' => 'Classic Hollywood leading man known for noir films.'],
            ['name' => 'Katherine Hepburn', 'bio' => 'Legendary actress known for strong, independent roles.'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0001', 'format' => 'VHS',  'shelf' => 'A-01', 'condition' => 'Good'],
            ['tape_number' => 'T-0002', 'format' => 'DVD',  'shelf' => 'A-01', 'condition' => 'Good'],
        ],
    ],

    [
        'movie_id'    => 'MOV-002',
        'title'       => 'Stagecoach',
        'category'    => 'Western',
        'director'    => 'John Ford',
        'year'        => 1939,
        'description' => 'Strangers travel by stagecoach through dangerous Apache territory.',
        'image'       => 'stagecoach.jpg',           // ← Add poster filename here once you have it
        'actors'      => [
            ['name' => 'John Wayne', 'bio' => 'Iconic American actor known for Western and war films.'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0003', 'format' => 'VHS',  'shelf' => 'B-02', 'condition' => 'Good'],
            ['tape_number' => 'T-0004', 'format' => 'Beta', 'shelf' => 'B-02', 'condition' => 'Fair'],
        ],
    ],

    [
        'movie_id'    => 'MOV-003',
        'title'       => 'Rear Window',
        'category'    => 'Suspense',
        'director'    => 'Alfred Hitchcock',
        'year'        => 1954,
        'description' => 'A photographer with a broken leg believes he witnessed a murder from his apartment window.',
        'image'       => 'rear_window.jpg',           // ← Add poster filename here once you have it
        'actors'      => [
            ['name' => 'James Stewart', 'bio' => 'Beloved actor known for his everyman roles.'],
            ['name' => 'Grace Kelly',   'bio' => 'Elegant actress who became Princess of Monaco.'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0005', 'format' => 'DVD', 'shelf' => 'C-03', 'condition' => 'Good'],
            ['tape_number' => 'T-0006', 'format' => 'VHS', 'shelf' => 'C-03', 'condition' => 'Good'],
        ],
    ],

    [
        'movie_id'    => 'MOV-004',
        'title'       => "Breakfast at Tiffany's",
        'category'    => 'Drama',
        'director'    => 'Blake Edwards',
        'year'        => 1961,
        'description' => 'A young woman navigates life and love in New York City.',
        'image'       => 'breakfast_at_tiffanys.jpg',           // ← Add poster filename here once you have it
        'actors'      => [
            ['name' => 'Audrey Hepburn', 'bio' => 'Acclaimed actress and humanitarian icon.'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0007', 'format' => 'VHS', 'shelf' => 'A-04', 'condition' => 'Good'],
            ['tape_number' => 'T-0008', 'format' => 'DVD', 'shelf' => 'A-04', 'condition' => 'Good'],
            ['tape_number' => 'T-0009', 'format' => 'DVD', 'shelf' => 'A-05', 'condition' => 'Fair'],
        ],
    ],

    [
        'movie_id'    => 'MOV-005',
        'title'       => 'North by Northwest',
        'category'    => 'Suspense',
        'director'    => 'Alfred Hitchcock',
        'year'        => 1959,
        'description' => 'A man is mistaken for a spy and must clear his name across the country.',
        'image'       => 'north_by_northwest.jpg',           // ← Add poster filename here once you have it
        'actors'      => [
            ['name' => 'Cary Grant',    'bio' => 'Dashing leading man of classic Hollywood comedies.'],
            ['name' => 'James Stewart', 'bio' => 'Beloved actor known for his everyman roles.'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0010', 'format' => 'DVD', 'shelf' => 'C-05', 'condition' => 'Good'],
        ],
    ],

    // ──────────────────────────────────────────────────────────────────
    //  DISNEY ANIMATED FILMS
    // ──────────────────────────────────────────────────────────────────

    [
        'movie_id'    => 'MOV-006',
        'title'       => 'The Lion King',
        'category'    => 'Animation',
        'director'    => 'Roger Allers, Rob Minkoff',
        'year'        => 1994,
        'description' => 'A young lion prince flees his kingdom after the murder of his father, only to learn the true meaning of responsibility and bravery.',
        'image'       => 'lion_king.jpg',
        'actors'      => [
            ['name' => 'Matthew Broderick', 'bio' => 'American actor, voice of adult Simba in The Lion King (1994).'],
            ['name' => 'Jeremy Irons',      'bio' => 'British actor, voice of Scar in The Lion King (1994).'],
            ['name' => 'James Earl Jones',  'bio' => 'Legendary actor, voice of Mufasa in The Lion King (1994).'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0011', 'format' => 'VHS', 'shelf' => 'D-01', 'condition' => 'Good'],
            ['tape_number' => 'T-0012', 'format' => 'DVD', 'shelf' => 'D-01', 'condition' => 'Good'],
            ['tape_number' => 'T-0013', 'format' => 'VHS', 'shelf' => 'D-01', 'condition' => 'Fair'],
        ],
    ],

    [
        'movie_id'    => 'MOV-007',
        'title'       => 'The Little Mermaid',
        'category'    => 'Animation',
        'director'    => 'Ron Clements, John Musker',
        'year'        => 1989,
        'description' => 'A mermaid princess makes a deal with a sea witch to become human and pursue the man she loves.',
        'image'       => 'little_mermaid.png',
        'actors'      => [
            ['name' => 'Jodi Benson', 'bio' => 'American actress, voice of Ariel in The Little Mermaid (1989).'],
            ['name' => 'Pat Carroll', 'bio' => 'American actress, voice of Ursula in The Little Mermaid (1989).'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0014', 'format' => 'VHS', 'shelf' => 'D-02', 'condition' => 'Good'],
            ['tape_number' => 'T-0015', 'format' => 'DVD', 'shelf' => 'D-02', 'condition' => 'Good'],
        ],
    ],

    [
        'movie_id'    => 'MOV-009',
        'title'       => 'Aladdin',
        'category'    => 'Animation',
        'director'    => 'Ron Clements, John Musker',
        'year'        => 1992,
        'description' => 'A street-rat finds a magic lamp containing a Genie with the power to grant three wishes.',
        'image'       => 'aladdin.jpg',
        'actors'      => [
            ['name' => 'Scott Weinger', 'bio' => 'American actor, speaking voice of Aladdin in Aladdin (1992).'],
            ['name' => 'Robin Williams', 'bio' => 'Legendary comedian and actor, voice of the Genie in Aladdin (1992).'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0019', 'format' => 'VHS', 'shelf' => 'D-04', 'condition' => 'Good'],
            ['tape_number' => 'T-0020', 'format' => 'DVD', 'shelf' => 'D-04', 'condition' => 'Good'],
        ],
    ],

    [
        'movie_id'    => 'MOV-010',
        'title'       => 'Frozen',
        'category'    => 'Animation',
        'director'    => 'Chris Buck, Jennifer Lee',
        'year'        => 2013,
        'description' => 'A fearless princess sets off on an epic journey to find her estranged sister whose icy powers have trapped the kingdom in eternal winter.',
        'image'       => 'frozen.jpg',
        'actors'      => [
            ['name' => 'Idina Menzel', 'bio' => 'American actress and singer, voice of Elsa in Frozen (2013).'],
            ['name' => 'Kristen Bell',  'bio' => 'American actress, voice of Anna in Frozen (2013).'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0021', 'format' => 'DVD', 'shelf' => 'D-05', 'condition' => 'Good'],
            ['tape_number' => 'T-0022', 'format' => 'DVD', 'shelf' => 'D-05', 'condition' => 'Good'],
            ['tape_number' => 'T-0023', 'format' => 'VHS', 'shelf' => 'D-05', 'condition' => 'Good'],
        ],
    ],

    [
        'movie_id'    => 'MOV-011',
        'title'       => 'Beauty and the Beast',
        'category'    => 'Animation',
        'director'    => 'Gary Trousdale, Kirk Wise',
        'year'        => 1991,
        'description' => 'An intelligent young woman is taken prisoner by a mysterious beast, only to discover his kind heart hidden beneath the enchanted curse.',
        'image'       => 'beauty_beast.jpg',
        'actors'      => [
            ['name' => "Paige O'Hara", 'bio' => "American actress, voice of Belle in Beauty and the Beast (1991)."],
            ['name' => 'Jerry Orbach', 'bio' => 'American actor, voice of Lumière in Beauty and the Beast (1991).'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0024', 'format' => 'VHS',  'shelf' => 'D-06', 'condition' => 'Good'],
            ['tape_number' => 'T-0025', 'format' => 'DVD',  'shelf' => 'D-06', 'condition' => 'Good'],
            ['tape_number' => 'T-0026', 'format' => 'Beta', 'shelf' => 'D-06', 'condition' => 'Fair'],
        ],
    ],

    // ──────────────────────────────────────────────────────────────────
    //  ↓↓↓  ADD YOUR NEW MOVIES BELOW THIS LINE  ↓↓↓
    //
    //  TEMPLATE — copy, paste, and fill in the fields:
    //
    //  [
    //      'movie_id'    => 'MOV-012',              // ← next available ID
    //      'title'       => 'My Movie Title',
    //      'category'    => 'Animation',             // ← see category list above
    //      'director'    => 'Director Name',
    //      'year'        => 2000,
    //      'description' => 'Short synopsis here.',
    //      'image'       => 'my_movie.jpg',          // ← filename in storage/app/public/movies/
    //      'actors'      => [
    //          ['name' => 'Actor Name', 'bio' => 'Short bio.'],
    //      ],
    //      'tapes' => [
    //          ['tape_number' => 'T-0027', 'format' => 'DVD', 'shelf' => 'E-01', 'condition' => 'Good'],
    //      ],
    //  ],
    // ──────────────────────────────────────────────────────────────────

    [
        'movie_id'    => 'MOV-008',
        'title'       => 'The Super Mario Bros. Movie',
        'category'    => 'Animation',
        'director'    => 'Aaron Horvath, Michael Jelenic',
        'year'        => 2023,
        'description' => 'A plumber named Mario travels through an underground labyrinth with his brother, Luigi, trying to save a captured princess.',
        'image'       => 'super_mario.jpg',
        'actors'      => [
            ['name' => 'Chris Pratt', 'bio' => 'American actor, voice of Mario.'],
            ['name' => 'Anya Taylor-Joy', 'bio' => 'Actress, voice of Princess Peach.'],
            ['name' => 'Jack Black', 'bio' => 'American actor and musician, voice of Bowser.'],
        ],
        'tapes' => [
            ['tape_number' => 'T-0027', 'format' => 'DVD', 'shelf' => 'E-01', 'condition' => 'Good'],
            ['tape_number' => 'T-0028', 'format' => 'DVD', 'shelf' => 'E-01', 'condition' => 'Good'],
        ],
    ],


];
