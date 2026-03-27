<?php

namespace Tests\Feature;

use App\Models\Salon;
use App\Models\Service;
use App\Models\User;
use App\Models\Booking;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_booking()
    {
        $client = Client::factory()->create();
        $salon = Salon::factory()->create();
        $specialist = User::factory()->create(['role' => 'specialist', 'salon_id' => $salon->id]);
        $service = Service::factory()->create(['duration_minutes' => 60]);

        $response = $this->actingAs($client, 'client')->postJson('/booking', [
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time' => '10:00',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bookings', [
            'client_id' => $client->id,
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
        ]);
    }

    public function test_booking_conflict_is_prevented()
    {
        $client = Client::factory()->create();
        $salon = Salon::factory()->create();
        $specialist = User::factory()->create(['role' => 'specialist', 'salon_id' => $salon->id]);
        $service = Service::factory()->create(['duration_minutes' => 60]);

        $date = Carbon::tomorrow()->format('Y-m-d');
        
        // Create first booking
        Booking::create([
            'client_id' => $client->id,
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
            'start_time' => Carbon::parse("$date 10:00"),
            'end_time' => Carbon::parse("$date 11:00"),
            'status' => 'confirmed',
        ]);

        // Try to book overlapping slot
        $response = $this->actingAs($client, 'client')->postJson('/booking', [
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
            'date' => $date,
            'time' => '10:30',
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('error', 'Выбранный мастер занят на это время.');
    }

    public function test_admin_cannot_delete_booking_from_another_salon()
    {
        $salonA = Salon::factory()->create();
        $salonB = Salon::factory()->create();
        
        $adminA = User::factory()->create(['role' => 'admin', 'salon_id' => $salonA->id]);
        $client = Client::factory()->create();
        $service = Service::factory()->create();
        
        $bookingB = Booking::create([
            'client_id' => $client->id,
            'salon_id' => $salonB->id,
            'service_id' => $service->id,
            'specialist_id' => User::factory()->create(['role' => 'specialist', 'salon_id' => $salonB->id])->id,
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHour(),
            'status' => 'pending',
        ]);

        $response = $this->actingAs($adminA)->deleteJson("/admin/bookings/{$bookingB->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('bookings', ['id' => $bookingB->id]);
    }

    public function test_booking_outside_working_hours_fails()
    {
        $client = Client::factory()->create();
        $salon = Salon::factory()->create();
        $specialist = User::factory()->create(['role' => 'specialist', 'salon_id' => $salon->id]);
        $service = Service::factory()->create();

        $response = $this->actingAs($client, 'client')->postJson('/booking', [
            'salon_id' => $salon->id,
            'service_id' => $service->id,
            'specialist_id' => $specialist->id,
            'date' => Carbon::tomorrow()->format('Y-m-d'),
            'time' => '08:00', // Before 9:00
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['time']);
    }
}
