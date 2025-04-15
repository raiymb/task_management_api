<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Task::factory()->count(10)->create();
    }
}
