<?php

namespace App\Http\Requests\Admin;

use App\Services\VimeoUrlParser;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'vimeo_url' => ['required', 'string', 'max:2048', function (string $attribute, mixed $value, Closure $fail) {
                if (! app(VimeoUrlParser::class)->extractId($value)) {
                    $fail('Unesite ispravan Vimeo URL ili Vimeo iframe embed kod.');
                }
            }],
            'sort_order' => ['required', 'integer', 'min:0', 'max:99999'],
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
