<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HabitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $weekDays = implode(',', get_week_days());
        return [
            'habit_id' => 'sometimes|int',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|int|in:1,2',
            'goal' => 'required|int',
            'reward' => 'required|int',
            'week_days' => 'required|array|in:'.$weekDays,
            'default_unit_id' => 'nullable|int|exists:default_units,id',
            'user_unit_id' => 'nullable|int|exists:user_units,id',
            'due_to' => 'nullable|date',
        ];
    }
}
