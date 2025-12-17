<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $this->assertTrue($event->user()->is($user));
    }

    public function test_event_has_many_signups(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $signup1 = $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $signup2 = $event->signups()->create(['user_id' => User::factory()->create()->id]);

        $this->assertCount(2, $event->signups);
        $this->assertTrue($event->signups->contains($signup1));
        $this->assertTrue($event->signups->contains($signup2));
    }

    public function test_event_calculates_available_spots_correctly(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'limit' => 10,
        ]);

        $this->assertEquals(10, $event->available_spots);

        $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $event->refresh();
        $this->assertEquals(9, $event->available_spots);

        $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $event->refresh();
        $this->assertEquals(8, $event->available_spots);
    }

    public function test_event_available_spots_never_goes_negative(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'limit' => 2,
        ]);

        // Create 3 signups manually (bypassing validation)
        $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $event->signups()->create(['user_id' => User::factory()->create()->id]);

        $event->refresh();
        $this->assertEquals(0, $event->available_spots);
    }

    public function test_event_is_full_when_limit_reached(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'limit' => 2,
        ]);

        $this->assertFalse($event->isFull());

        $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $event->refresh();
        $this->assertFalse($event->isFull());

        $event->signups()->create(['user_id' => User::factory()->create()->id]);
        $event->refresh();
        $this->assertTrue($event->isFull());
    }

    public function test_event_detects_user_signup(): void
    {
        $user = User::factory()->create();
        $participant = User::factory()->create();

        $event = Event::factory()->create(['user_id' => $user->id]);

        $this->assertFalse($event->isUserSignedUp($participant->id));

        $event->signups()->create(['user_id' => $participant->id]);

        $this->assertTrue($event->isUserSignedUp($participant->id));
    }

    public function test_event_date_is_casted_to_datetime(): void
    {
        $user = User::factory()->create();
        $eventDate = now()->addDays(5);

        $event = Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => $eventDate,
        ]);

        $this->assertInstanceOf(\DateTime::class, $event->event_date);
    }

    public function test_event_uses_soft_deletes(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $event->delete();

        $this->assertTrue($event->trashed());
        $this->assertNull(Event::find($event->id));
        $this->assertNotNull(Event::withTrashed()->find($event->id));
    }

    public function test_event_appends_available_spots(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['user_id' => $user->id]);

        $attributes = $event->toArray();

        $this->assertArrayHasKey('available_spots', $attributes);
        $this->assertIsInt($attributes['available_spots']);
    }

    public function test_event_has_correct_table_name(): void
    {
        $event = new Event();
        $this->assertEquals('events', $event->getTable());
    }

    public function test_event_has_correct_fillable_attributes(): void
    {
        $event = new Event();
        $fillable = $event->getFillable();

        $this->assertContains('name', $fillable);
        $this->assertContains('description', $fillable);
        $this->assertContains('event_date', $fillable);
        $this->assertContains('limit', $fillable);
        $this->assertContains('image', $fillable);
        $this->assertContains('user_id', $fillable);
    }
}
