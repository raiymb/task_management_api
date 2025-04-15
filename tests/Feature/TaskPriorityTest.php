<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use Carbon\Carbon;

class TaskPriorityTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_are_prioritized_correctly(): void
    {
        $overdue = Task::factory()->create([
            'title' => 'Overdue Important',
            'importance' => 5,
            'deadline' => Carbon::now()->subDays(2),
            'status' => 'IN_PROGRESS',
        ]);

        $soonHigh = Task::factory()->create([
            'title' => 'Soon High',
            'importance' => 5,
            'deadline' => Carbon::now()->addDay(),
            'status' => 'TODO',
        ]);

        $soonMedium = Task::factory()->create([
            'title' => 'Soon Medium',
            'importance' => 3,
            'deadline' => Carbon::now()->addDay(),
            'status' => 'TODO',
        ]);

        $later = Task::factory()->create([
            'title' => 'Later',
            'importance' => 5,
            'deadline' => Carbon::now()->addDays(10),
            'status' => 'TODO',
        ]);

        Task::factory()->create([
            'title' => 'Completed Task',
            'importance' => 5,
            'deadline' => Carbon::now()->addDay(),
            'status' => 'COMPLETED',
        ]);

        $response = $this->getJson('/api/tasks/priority');


        $response->assertStatus(200);
        $response->assertJsonCount(4, 'data');

        $response->assertJsonPath('data.0.id', $overdue->id);
        $response->assertJsonPath('data.1.id', $soonHigh->id);
        $response->assertJsonPath('data.2.id', $soonMedium->id);
        $response->assertJsonPath('data.3.id', $later->id);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'status',
                    'importance',
                    'deadline',
                    'is_overdue',
                    'priority_score',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
    }
}
