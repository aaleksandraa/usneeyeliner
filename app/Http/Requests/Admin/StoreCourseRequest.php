<?php

namespace App\Http\Requests\Admin;

use App\Services\VimeoUrlParser;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'vimeo_url' => ['nullable', 'string', 'max:2048', function (string $attribute, mixed $value, Closure $fail) {
                if ($value && ! app(VimeoUrlParser::class)->extractId($value)) {
                    $fail('Unesite ispravan Vimeo URL ili Vimeo iframe embed kod.');
                }
            }],
            'status' => ['nullable', 'in:active,inactive'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('vimeo_url')) {
            $normalizedUrl = app(VimeoUrlParser::class)->normalizeInput($this->string('vimeo_url')->toString());

            if ($normalizedUrl) {
                $this->merge(['vimeo_url' => $normalizedUrl]);
            }
        }
    }
}
