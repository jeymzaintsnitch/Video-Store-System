<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\Tape;

$animation = Category::where('name', 'Animation')->first();
$ohara  = Actor::where('name', "Paige O'Hara")->first();
$orbach = Actor::where('name', 'Jerry Orbach')->first();

// MOV-008 is taken by Mario Movie — use MOV-011 for Beauty and the Beast
$movie = Movie::firstOrCreate(
    ['movie_id' => 'MOV-011'],
    [
        'title'       => 'Beauty and the Beast',
        'category_id' => $animation->id,
        'director'    => 'Gary Trousdale, Kirk Wise',
        'year'        => 1991,
        'description' => 'An intelligent young woman is taken prisoner by a mysterious beast, only to discover his kind heart hidden beneath the enchanted curse.',
        'image'       => 'movies/beauty_beast.jpg',
    ]
);

$actorIds = array_filter([$ohara ? $ohara->id : null, $orbach ? $orbach->id : null]);
$movie->actors()->syncWithoutDetaching($actorIds);

$tapesData = [
    ['tape_number' => 'T-0024', 'format' => 'VHS',  'shelf_location' => 'D-06', 'condition' => 'Good', 'status' => 'available'],
    ['tape_number' => 'T-0025', 'format' => 'DVD',  'shelf_location' => 'D-06', 'condition' => 'Good', 'status' => 'available'],
    ['tape_number' => 'T-0026', 'format' => 'Beta', 'shelf_location' => 'D-06', 'condition' => 'Fair', 'status' => 'available'],
];

foreach ($tapesData as $tape) {
    Tape::firstOrCreate(
        ['tape_number' => $tape['tape_number']],
        array_merge($tape, ['movie_id' => $movie->id])
    );
}

echo "✅ Beauty and the Beast added as MOV-011!\n";
echo "   Movie ID: " . $movie->id . " | Actors: " . $movie->actors->pluck('name')->join(', ') . "\n";
echo "   Was newly created: " . ($movie->wasRecentlyCreated ? 'YES' : 'NO') . "\n";
echo "\nAll Disney movies now in DB:\n";

$disney = Movie::with('category','actors')
    ->whereIn('movie_id', ['MOV-006','MOV-007','MOV-008','MOV-009','MOV-010','MOV-011'])
    ->get();

foreach ($disney as $m) {
    echo "  - {$m->movie_id}: {$m->title} | Image: " . ($m->image ?? 'NONE') . "\n";
}
