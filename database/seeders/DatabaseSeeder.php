<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Category;
use App\Models\Actor;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. ROLES & PERMISSIONS ───────────────────────────────
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view movies',      'create movies',      'edit movies',      'delete movies',
            'view tapes',       'create tapes',       'edit tapes',       'delete tapes',
            'view actors',      'create actors',      'edit actors',      'delete actors',
            'view categories',  'create categories',  'edit categories',  'delete categories',
            'view users',       'create users',       'edit users',       'delete users',
            'view audit logs',
            'view roles',       'manage roles',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'view movies',     'create movies',     'edit movies',     'delete movies',
            'view tapes',      'create tapes',      'edit tapes',      'delete tapes',
            'view actors',     'create actors',     'edit actors',     'delete actors',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view audit logs',
        ]);

        $staff = Role::firstOrCreate(['name' => 'Staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'view movies', 'view tapes', 'view actors', 'view categories',
        ]);

        $this->command->info('✅ Roles & permissions seeded.');

        // ── 2. USERS ─────────────────────────────────────────────
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@videostore.com'],
            ['name' => 'System Admin', 'password' => Hash::make('password')]
        );
        $adminUser->syncRoles(['Admin']);

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@videostore.com'],
            ['name' => 'Store Manager', 'password' => Hash::make('password')]
        );
        $managerUser->syncRoles(['Manager']);

        $staffUser = User::firstOrCreate(
            ['email' => 'staff@videostore.com'],
            ['name' => 'Store Staff', 'password' => Hash::make('password')]
        );
        $staffUser->syncRoles(['Staff']);

        $this->command->info('✅ Users seeded.');

        // ── 3. CATEGORIES ─────────────────────────────────────────
        $categoryData = [
            ['name' => 'Comedy',    'description' => 'Funny and lighthearted films'],
            ['name' => 'Suspense',  'description' => 'Thrilling suspense and mystery films'],
            ['name' => 'Drama',     'description' => 'Dramatic and emotional storytelling'],
            ['name' => 'Action',    'description' => 'High-energy action and adventure'],
            ['name' => 'SciFi',     'description' => 'Science fiction and futuristic stories'],
            ['name' => 'Horror',    'description' => 'Scary and horror genre films'],
            ['name' => 'Romance',   'description' => 'Love stories and romantic films'],
            ['name' => 'Animation', 'description' => 'Animated films for all ages'],
            ['name' => 'Western',   'description' => 'Classic and modern Western films'],
            ['name' => 'Thriller',  'description' => 'Edge-of-your-seat thriller films'],
        ];

        foreach ($categoryData as $cat) {
            Category::firstOrCreate(['name' => $cat['name']], $cat);
        }

        $this->command->info('✅ Categories seeded.');

        // ── 4. SEED DEFAULT ACTORS ────────────────────────────────
        // (MoviesTableSeeder handles actor creation too — this ensures
        //  legacy actors are present even on a clean install)
        $actorData = [
            ['name' => 'John Wayne',        'bio' => 'Iconic American actor known for Western and war films.'],
            ['name' => 'Katherine Hepburn', 'bio' => 'Legendary actress known for strong, independent roles.'],
            ['name' => 'Humphrey Bogart',   'bio' => 'Classic Hollywood leading man known for noir films.'],
            ['name' => 'Audrey Hepburn',    'bio' => 'Acclaimed actress and humanitarian icon.'],
            ['name' => 'James Stewart',     'bio' => 'Beloved actor known for his everyman roles.'],
            ['name' => 'Marilyn Monroe',    'bio' => 'Cultural icon and actress of the golden age.'],
            ['name' => 'Cary Grant',        'bio' => 'Dashing leading man of classic Hollywood comedies.'],
            ['name' => 'Grace Kelly',       'bio' => 'Elegant actress who became Princess of Monaco.'],
        ];

        foreach ($actorData as $actor) {
            Actor::firstOrCreate(['name' => $actor['name']], $actor);
        }

        $this->command->info('✅ Actors seeded.');

        // ── 5. MOVIES & TAPES ─────────────────────────────────────
        // All movie data lives in: database/seeders/data/movies.php
        // To add more movies: edit that file and re-run MoviesTableSeeder.
        $this->call(MoviesTableSeeder::class);

        // ── SUMMARY ───────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('VideoStore database ready!');
        $this->command->info('');
        $this->command->info('  admin@videostore.com   / password  (Admin)');
        $this->command->info('  manager@videostore.com / password  (Manager)');
        $this->command->info('  staff@videostore.com   / password  (Staff)');
        $this->command->info('');
    }
}