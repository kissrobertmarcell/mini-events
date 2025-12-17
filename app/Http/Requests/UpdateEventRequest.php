<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->id === $this->event->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:5|max:255',
            'description' => 'required|string|max:5000',
            'event_date' => 'required|date|after:now',
            'limit' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:3072',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Az esemény neve kötelező.',
            'name.min' => 'Az esemény neve legalább 5 karakter hosszú kell, hogy legyen.',
            'description.required' => 'A leírás kötelező.',
            'description.max' => 'A leírás maximum 5000 karakter hosszú lehet.',
            'event_date.required' => 'Az esemény dátuma kötelező.',
            'event_date.after' => 'Az esemény dátuma a jövőben kell, hogy legyen.',
            'limit.required' => 'A létszámlimit kötelező.',
            'limit.min' => 'A létszámlimit legalább 1 kell, hogy legyen.',
            'image.image' => 'A fájl egy kép kell, hogy legyen.',
            'image.max' => 'A kép maximum 3MB lehet.',
        ];
    }
}
