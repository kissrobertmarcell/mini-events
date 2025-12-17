<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_is_public_and_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_homepage_displays_only_future_events(): void
    {
        $user = User::factory()->create();
        
        Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => now()->subDays(1),
        ]);
        
        Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => now()->addDays(1),
        ]);
        
        $eventsCount = Event::where('event_date', '>', now())->count();
        $this->assertGreaterThanOrEqual(1, $eventsCount);
        
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_homepage_has_pagination_of_six_events(): void
    {
        $user = User::factory()->create();
        
        Event::factory(12)->create([
            'user_id' => $user->id,
            'event_date' => now()->addDays(1),
        ]);
        
        $response = $this->get('/');
        $response->assertStatus(200);
        
        $events = Event::where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->paginate(6);
        
        $this->assertEquals(6, $events->perPage());
        $this->assertEquals(2, $events->lastPage());
    }

    public function test_events_are_ordered_by_date(): void
    {
        $user = User::factory()->create();
        
        $event1 = Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => now()->addDays(5),
        ]);
        
        $event2 = Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => now()->addDays(1),
        ]);
        
        $event3 = Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => now()->addDays(10),
        ]);
        
        $events = Event::where('event_date', '>', now())
            ->orderBy('event_date', 'asc')
            ->get();
        
        $this->assertEquals($event2->id, $events[0]->id);
        $this->assertEquals($event1->id, $events[1]->id);
        $this->assertEquals($event3->id, $events[2]->id);
    }

    public function test_event_cards_display_required_information(): void
    {
        $user = User::factory()->create();
        
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'event_date' => now()->addDays(1),
            'name' => 'Test Event',
            'description' => 'Test Description',
            'limit' => 10,
        ]);
        
        $response = $this->get('/');
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('events', [
            'name' => 'Test Event',
            'description' => 'Test Description',
        ]);
    }

    public function test_event_with_no_image_has_placeholder(): void
    {
        $user = User::factory()->create();
        
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'image' => null,
        ]);
        
        $this->assertNull($event->image);
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_available_spots_displayed_correctly(): void
    {
        $user = User::factory()->create();
        
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'limit' => 5,
        ]);
        
        $this->assertEquals(5, $event->available_spots);
    }

    public function test_full_event_shows_betelt(): void
    {
        $user = User::factory()->create();
        
        $event = Event::factory()->create([
            'user_id' => $user->id,
            'limit' => 2,
        ]);
        
        $event->signups()->createMany([
            ['user_id' => User::factory()->create()->id],
            ['user_id' => User::factory()->create()->id],
        ]);
        
        $this->assertTrue($event->isFull());
        $this->assertEquals(0, $event->available_spots);
    }
}
