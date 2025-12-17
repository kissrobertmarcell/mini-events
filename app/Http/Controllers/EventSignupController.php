<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSignup;
use App\Mail\SignupConfirmation;
use App\Mail\EventSignupNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EventSignupController extends Controller
{
    public function store(Event $event, Request $request): RedirectResponse
    {
        $user = $request->user();

        try {
            DB::transaction(function () use ($event, $user) {
                $event->refresh();

                $existingSignup = EventSignup::query()
                    ->where('event_id', $event->id)
                    ->where('user_id', $user->id)
                    ->lockForUpdate()
                    ->first();

                if ($existingSignup) {
                    throw new \Exception('Már jelentkeztél erre az eseményre');
                }

                $signupCount = EventSignup::query()
                    ->where('event_id', $event->id)
                    ->lockForUpdate()
                    ->count();

                if ($signupCount >= $event->limit) {
                    throw new \Exception('Az esemény betelt!');
                }

                EventSignup::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                ]);

                $event->refresh();
                $event->load('user', 'signups');

                Mail::to($user->email)->queue(new SignupConfirmation($event));
                Mail::to($event->user->email)->queue(new EventSignupNotification($event, $user));
            });

            return back()->with('success', 'Sikeresen jelentkeztél az eseményre');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Event $event, Request $request): RedirectResponse
    {
        $user = $request->user();

        EventSignup::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'Sikeresen leiratkoztál');
    }
}
