<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            [
                'name' => 'Pending',
                'slug' => 'pending',
                'display_order' => 0,
                'colour_hex' => '#6c757d',
                'response_hours' => 24,
                'resolution_hours' => 72,
                'is_active' => false,
            ],
            [
                'name' => 'Low',
                'slug' => 'low',
                'display_order' => 1,
                'colour_hex' => '#28a745',
                'response_hours' => 24,
                'resolution_hours' => 72,
                'is_active' => true,
            ],
            [
                'name' => 'Normal',
                'slug' => 'normal',
                'display_order' => 2,
                'colour_hex' => '#ffc107',
                'response_hours' => 12,
                'resolution_hours' => 48,
                'is_active' => true,
            ],
            [
                'name' => 'High',
                'slug' => 'high',
                'display_order' => 3,
                'colour_hex' => '#fd7e14',
                'response_hours' => 6,
                'resolution_hours' => 24,
                'is_active' => true,
            ],
            [
                'name' => 'Critical',
                'slug' => 'critical',
                'display_order' => 4,
                'colour_hex' => '#dc3545',
                'response_hours' => 2,
                'resolution_hours' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($priorities as $priority) {
            Priority::updateOrInsert(
                ['name' => $priority['name']],
                array_merge($priority, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
