<?php

namespace Tests\Feature;

use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_booking()
    {
        $user = User::factory()->create(['role' => 'client']);
        $salon = Salon::factory()->create();
        $specialist = User::factory()->create(['role' => 'specialist', 'salon_id' => $salon->id]);
        $service = Service::factory()->create();

        $response = $this->actingAs($user)->postJson('/booking', [
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '10:00',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
        ]);
    }
}
