<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class ProfileDetailsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,' . $this->user()->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'in:male,female,other,prefer_not_to_say'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', File::image()->max(2 * 1024)], // 2MB max (in KB)
            'university' => ['nullable', 'string', 'max:255'],
            'other_university' => ['nullable', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'year_level' => ['nullable', 'string', 'max:50'],
            'department' => ['nullable', 'string', 'max:255'],
            'cleanliness_level' => ['nullable', 'string', 'in:very_messy,somewhat_messy,average,somewhat_clean,very_clean'],
            'sleep_pattern' => ['nullable', 'string', 'in:early_bird,night_owl,flexible'],
            'study_habit' => ['nullable', 'string', 'in:intense,moderate,social,quiet'],
            'noise_tolerance' => ['nullable', 'string', 'in:quiet,moderate,loud'],
            'budget_min' => ['nullable', 'numeric', 'min:0', 'lt:budget_max'],
            'budget_max' => ['nullable', 'numeric', 'gt:budget_min'],
            'hobbies' => ['nullable', 'array'],
            'hobbies.*' => ['string', 'max:255'],
            'lifestyle_tags' => ['nullable', 'array'],
            'lifestyle_tags.*' => ['string', 'max:255'],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Convert comma-separated strings to arrays for storage
        if (is_string($this->hobbies)) {
            $this->merge([
                'hobbies' => array_filter(array_map('trim', explode(',', $this->hobbies))),
            ]);
        } elseif (is_array($this->hobbies)) {
            $this->merge([
                'hobbies' => array_values(array_filter(array_map('trim', $this->hobbies))),
            ]);
        }

        if (is_string($this->lifestyle_tags)) {
            $this->merge([
                'lifestyle_tags' => array_filter(array_map('trim', explode(',', $this->lifestyle_tags))),
            ]);
        } elseif (is_array($this->lifestyle_tags)) {
            $this->merge([
                'lifestyle_tags' => array_values(array_filter(array_map('trim', $this->lifestyle_tags))),
            ]);
        }
    }
}
