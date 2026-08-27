<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_stats_api_returns_json(): void
    {
        $this->seed();
        $response = $this->getJson('/api/v1/stats');
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => [
                         'total_patients',
                         'total_doctors',
                         'today_appointments',
                         'available_rooms',
                         'total_rooms',
                         'total_revenue',
                     ]
                 ]);
    }
}
