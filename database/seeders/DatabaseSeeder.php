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
        $categories = ['5K', '10K', '21K'];
        $catMap = [];
        foreach ($categories as $cat) {
            $catMap[$cat] = \App\Models\Category::create(['name' => $cat])->id;
        }

        // Periods
        $periods = [
            'Presale' => \App\Models\Period::create(['name' => 'Presale', 'is_active' => true])->id,
            'Normal' => \App\Models\Period::create(['name' => 'Normal', 'is_active' => false])->id,
            'Invitation & Sponsorship' => \App\Models\Period::create(['name' => 'Invitation & Sponsorship', 'is_active' => false])->id,
        ];

        // Tickets: Presale
        $presaleTickets = [
            ['name' => 'Umum', 'cat' => '5K', 'price' => 250000, 'qty' => 1],
            ['name' => 'IPB', 'cat' => '5K', 'price' => 225000, 'qty' => 150],
            ['name' => 'Umum', 'cat' => '10K', 'price' => 275000, 'qty' => 150],
            ['name' => 'IPB', 'cat' => '10K', 'price' => 250000, 'qty' => 150],
            ['name' => 'Umum', 'cat' => '21K', 'price' => 350000, 'qty' => 175],
            ['name' => 'IPB', 'cat' => '21K', 'price' => 325000, 'qty' => 175],
        ];

        foreach ($presaleTickets as $t) {
            \App\Models\Ticket::create([
                'name' => $t['name'],
                'category_id' => $catMap[$t['cat']],
                'period_id' => $periods['Presale'],
                'price' => $t['price'],
                'discount' => 25000,
                'qty' => $t['qty'],
                'display' => true
            ]);
        }

        // Tickets: Normal
        $normalTickets = [
            ['name' => 'Umum', 'cat' => '5K', 'price' => 275000, 'qty' => 750],
            ['name' => 'IPB', 'cat' => '5K', 'price' => 250000, 'qty' => 750],
            ['name' => 'Umum', 'cat' => '10K', 'price' => 300000, 'qty' => 400],
            ['name' => 'IPB', 'cat' => '10K', 'price' => 275000, 'qty' => 400],
            ['name' => 'Umum', 'cat' => '21K', 'price' => 375000, 'qty' => 200],
            ['name' => 'IPB', 'cat' => '21K', 'price' => 350000, 'qty' => 200],
        ];

        foreach ($normalTickets as $t) {
            \App\Models\Ticket::create([
                'name' => $t['name'],
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
