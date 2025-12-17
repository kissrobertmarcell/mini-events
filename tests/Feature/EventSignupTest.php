<?php

namespace Tests\Feature;

use App\Mail\EventSignupNotification;
use App\Mail\SignupConfirmation;
use App\Models\Event;
use App\Models\EventSignup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EventSignupTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_unauthenticated_user_cannot_signup_for_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->post("/events/{$event->id}/register");
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_signup_for_event(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'limit' => 10,
        ]);

        $response = $this->actingAs($participant)->post("/events/{$event->id}/register");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Sikeresen jelentkeztél az eseményre');

        $this->assertDatabaseHas('event_signups', [
            'event_id' => $event->id,
            'user_id' => $participant->id,
        ]);
    }

    public function test_user_cannot_signup_for_same_event_twice(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create(['user_id' => $eventCreator->id]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        $response = $this->actingAs($participant)->post("/events/{$event->id}/register");

        $response->assertSessionHas('error', 'Már jelentkeztél erre az eseményre');

        $this->assertEquals(1, EventSignup::where([
            'event_id' => $event->id,
            'user_id' => $participant->id,
        ])->count());
    }

    public function test_signup_creates_relationship_between_user_and_event(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create(['user_id' => $eventCreator->id]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        $signup = EventSignup::where([
            'event_id' => $event->id,
            'user_id' => $participant->id,
        ])->first();

        $this->assertNotNull($signup);
        $this->assertTrue($signup->event()->is($event));
        $this->assertTrue($signup->user()->is($participant));
    }

    public function test_cannot_signup_for_full_event(): void
    {
        $eventCreator = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'limit' => 2,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $this->actingAs($user1)->post("/events/{$event->id}/register");
        $this->actingAs($user2)->post("/events/{$event->id}/register");

        $response = $this->actingAs($user3)->post("/events/{$event->id}/register");

        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('event_signups', [
            'event_id' => $event->id,
            'user_id' => $user3->id,
        ]);
    }

    public function test_signup_sends_confirmation_email_to_participant(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'name' => 'Test Event',
        ]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        Mail::assertQueued(SignupConfirmation::class, function ($mail) use ($participant) {
            return $mail->hasTo($participant->email);
        });
    }

    public function test_signup_sends_notification_email_to_event_creator(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'name' => 'Test Event',
        ]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        Mail::assertQueued(EventSignupNotification::class, function ($mail) use ($eventCreator) {
            return $mail->hasTo($eventCreator->email);
        });
    }

    public function test_race_condition_is_handled_correctly(): void
    {
        $eventCreator = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'limit' => 1,
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1)->post("/events/{$event->id}/register");

        $response2 = $this->actingAs($user2)->post("/events/{$event->id}/register");
        $response2->assertSessionHas('error');

        $this->assertEquals(1, EventSignup::where('event_id', $event->id)->count());
    }

    public function test_user_can_unregister_from_event(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create(['user_id' => $eventCreator->id]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        $response = $this->actingAs($participant)->delete("/events/{$event->id}/register");

        $response->assertSessionHas('success', 'Sikeresen leiratkoztál');

        $this->assertDatabaseMissing('event_signups', [
            'event_id' => $event->id,
            'user_id' => $participant->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_unregister(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $response = $this->delete("/events/{$event->id}/register");
        $response->assertRedirect('/login');
    }

    public function test_signup_reduces_available_spots(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'limit' => 10,
        ]);

        $initialSpots = $event->available_spots;

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        $event->refresh();
        $this->assertEquals($initialSpots - 1, $event->available_spots);
    }

    public function test_unregister_increases_available_spots(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'limit' => 10,
        ]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");
        $event->refresh();
        $spotsAfterSignup = $event->available_spots;

        $this->actingAs($participant)->delete("/events/{$event->id}/register");
        $event->refresh();

        $this->assertEquals($spotsAfterSignup + 1, $event->available_spots);
    }

    public function test_event_signup_includes_available_spots_data(): void
    {
        $eventCreator = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create([
            'user_id' => $eventCreator->id,
            'limit' => 10,
        ]);

        $this->actingAs($participant)->post("/events/{$event->id}/register");

        $signup = EventSignup::where([
            'event_id' => $event->id,
            'user_id' => $participant->id,
        ])->first();

        $event->refresh();
        $this->assertEquals(9, $event->available_spots);
    }
}
