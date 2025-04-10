<?php

namespace Tests\Feature;

use App\Models\TodoTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoTaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_tasks()
    {
        TodoTask::factory()->count(3)->create();

        $response = $this->getJson('/api/todos');

        $response->assertOk()->assertJsonCount(3);
    }

    public function test_can_store_task()
    {
        $data = ['title' => 'New Task', 'completed' => false];

        $response = $this->postJson('/api/todos', $data);

        $response->assertCreated()
            ->assertJsonFragment(['title' => 'New Task', 'completed' => false]);

        $this->assertDatabaseHas('todo_tasks', $data);
    }

    public function test_store_validation_error()
    {
        $response = $this->postJson('/api/todos', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['title', 'completed']);
    }

    public function test_can_update_task()
    {
        $task = TodoTask::factory()->create();

        $data = ['title' => 'Updated Task', 'completed' => true];

        $response = $this->putJson("/api/todos/{$task->id}", $data);

        $response->assertOk()->assertJsonFragment($data);

        $this->assertDatabaseHas('todo_tasks', $data);
    }

    public function test_update_validation_error()
    {
        $task = TodoTask::factory()->create();

        $response = $this->putJson("/api/todos/{$task->id}", ['completed' => 'not-a-boolean']);

        $response->assertStatus(422)->assertJsonValidationErrors(['completed']);
    }

    public function test_can_delete_task()
    {
        $task = TodoTask::factory()->create();

        $response = $this->deleteJson("/api/todos/{$task->id}");

        $response->assertOk();
        $this->assertDatabaseMissing('todo_tasks', ['id' => $task->id]);
    }

    public function test_can_delete_all_tasks()
    {
        TodoTask::factory()->count(5)->create();

        $response = $this->deleteJson('/api/todos/delete-all');

        $response->assertOk();
        $this->assertDatabaseCount('todo_tasks', 0);
    }
}
