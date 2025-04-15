<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use Carbon\Carbon;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_task()
    {
        $payload = [
            'title' => 'Test Task',
            'description' => 'Testing create',
            'status' => 'TODO',
            'importance' => 4,
            'deadline' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Test Task']);
    }

    public function test_can_list_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonCount(3, 'data');
    }

    public function test_can_filter_tasks_by_status()
    {
        Task::factory()->create(['status' => 'TODO']);
        Task::factory()->create(['status' => 'IN_PROGRESS']);

        $response = $this->getJson('/api/tasks?status=TODO');

        $response->assertStatus(200)
                 ->assertJsonCount(1, 'data');
    }

    public function test_can_show_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $task->id]);
    }

    public function test_can_update_task()
    {
        $task = Task::factory()->create();

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Updated']);
    }

    public function test_can_delete_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_tasks_are_prioritized_correctly()
    {
        $overdue = Task::factory()->create([
            'importance' => 5,
            'deadline' => Carbon::now()->subDay(),
            'status' => 'IN_PROGRESS',
        ]);

        $soon = Task::factory()->create([
            'importance' => 4,
            'deadline' => Carbon::now()->addDay(),
            'status' => 'TODO',
        ]);

        $response = $this->getJson('/api/tasks/priority');

        $response->assertStatus(200)
                 ->assertJsonPath('data.0.id', $overdue->id);
    }
}
