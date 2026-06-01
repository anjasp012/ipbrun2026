<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FrontlinerSeeder extends Seeder
{
    /**
     * Seed 8 frontliner users.
     * Jalankan dengan: php artisan db:seed --class=FrontlinerSeeder
     */
    public function run(): void
    {
        // Password unik untuk masing-masing frontliner
        $frontliners = [
            1 => ['name' => 'Frontliner 1', 'password' => 'Fl@Run#4821'],
            2 => ['name' => 'Frontliner 2', 'password' => 'Xp9$Mnr@17'],
            3 => ['name' => 'Frontliner 3', 'password' => 'Zk3!Qwt#56'],
            4 => ['name' => 'Frontliner 4', 'password' => 'Ry7@Bvs!93'],
            5 => ['name' => 'Frontliner 5', 'password' => 'Wd2#Lnp@38'],
            6 => ['name' => 'Frontliner 6', 'password' => 'Ht5!Crg#74'],
            7 => ['name' => 'Frontliner 7', 'password' => 'Jm8@Fxk!25'],
            8 => ['name' => 'Frontliner 8', 'password' => 'Nb6#Tys@61'],
        ];

        foreach ($frontliners as $i => $data) {
            \App\Models\User::updateOrCreate(
                ['email' => "frontliner{$i}@ipbrun2026.id"],
                [
                    'name'     => $data['name'],
                    'username' => "frontliner{$i}",
                    'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
                    'role'     => 'frontliner',
                ]
            );
        }

        $this->command->info('8 Frontliner users seeded successfully.');
        $this->command->table(
            ['#', 'Username', 'Email', 'Password'],
            collect($frontliners)->map(fn($data, $i) => [
                $i,
                "frontliner{$i}",
                "frontliner{$i}@ipbrun2026.id",
                $data['password'],
            ])->values()->toArray()
        );
    }
}
