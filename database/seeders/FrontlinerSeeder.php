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
        for ($i = 1; $i <= 8; $i++) {
            \App\Models\User::updateOrCreate(
                ['email' => "frontliner{$i}@gmail.com"],
                [
                    'name'     => "Frontliner {$i}",
                    'username' => "frontliner{$i}",
                    'password' => \Illuminate\Support\Facades\Hash::make("frontliner{$i}@gmail.com"),
                    'role'     => 'frontliner',
                ]
            );
        }

        $this->command->info('8 Frontliner users seeded successfully.');
    }
}
