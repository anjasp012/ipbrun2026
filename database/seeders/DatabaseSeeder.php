<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@ipbrun.com',
            'username' => 'admin',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Participant User',
            'email' => 'participant@ipbrun.com',
            'username' => 'participant',
            'role' => 'participant',
        ]);
        
        User::factory()->create([
            'name' => 'Tester User',
            'email' => 'tester@ipbrun.com',
            'username' => 'tester',
            'role' => 'tester',
        ]);

        User::factory()->create([
            'name' => 'Fotographer User',
            'email' => 'fotographer@ipbrun.com',
            'username' => 'fotographer',
            'role' => 'fotographer',
        ]);

        User::factory()->create([
            'name' => 'Scanner User',
            'email' => 'scanner@ipbrun.com',
            'username' => 'scanner',
            'role' => 'scanner',
        ]);
        // ... (User creation stays above)

        // Categories
        $categories = ['5K', '10K', '21K', '42K'];
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat] = \App\Models\Category::firstOrCreate(['name' => $cat])->id;
        }

        // Periods
        $periods = [
            'Presale' => \App\Models\Period::firstOrCreate(['name' => 'Presale'], ['is_active' => true])->id,
            'Normal' => \App\Models\Period::firstOrCreate(['name' => 'Normal'], ['is_active' => false])->id,
            'Invitation & Sponsorship' => \App\Models\Period::firstOrCreate(['name' => 'Invitation & Sponsorship'], ['is_active' => false])->id,
        ];

        // ... (Presale logic can be simplified or kept if needed, but I'll focus on Normal as per the image)

        // Tickets: Normal
        $normalTickets = [
            ['type' => 'umum', 'cat' => '5K', 'price' => 275000, 'qty' => 4000],
            ['type' => 'ipb', 'cat' => '5K', 'price' => 250000, 'qty' => 4000],
            ['type' => 'umum', 'cat' => '10K', 'price' => 300000, 'qty' => 3000],
            ['type' => 'ipb', 'cat' => '10K', 'price' => 275000, 'qty' => 3000],
            ['type' => 'umum', 'cat' => '21K', 'price' => 375000, 'qty' => 2000],
            ['type' => 'ipb', 'cat' => '21K', 'price' => 350000, 'qty' => 2000],
            ['type' => 'umum', 'cat' => '42K', 'price' => 750000, 'qty' => 1000],
            ['type' => 'ipb', 'cat' => '42K', 'price' => 700000, 'qty' => 1000],
        ];

        foreach ($normalTickets as $t) {
            \App\Models\Ticket::create([
                'name' => null, // Optional name is empty as per image
                'type' => $t['type'],
                'category_id' => $catMap[$t['cat']],
                'period_id' => $periods['Normal'],
                'price' => $t['price'],
                'qty' => $t['qty'],
                'display' => true
            ]);
        }

        // Tickets: Invitation & Sponsorship
        $invitationTickets = [
            ['name' => 'Invitation & Sponsorship', 'cat' => '5K', 'price' => 0, 'qty' => 200],
            ['name' => 'Invitation & Sponsorship', 'cat' => '10K', 'price' => 0, 'qty' => 100],
            ['name' => 'Invitation & Sponsorship', 'cat' => '21K', 'price' => 0, 'qty' => 50],
        ];

        foreach ($invitationTickets as $t) {
            \App\Models\Ticket::create([
                'name' => $t['name'],
                'category_id' => $catMap[$t['cat']],
                'period_id' => $periods['Invitation & Sponsorship'],
                'price' => $t['price'],
                'qty' => $t['qty'],
                'display' => true
            ]);
        }
    }
}
