<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\Worksheet;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class WorksheetUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['required_with:images', 'image', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->hasFile('images')) {
                return;
            }

            /** @var Worksheet $worksheet */
            $worksheet = $this->route('worksheet');
            $expected = $worksheet?->pageType?->sections_count;
            $provided = count($this->file('images', []));

            if ($expected && $expected !== $provided) {
                $message = $expected === 1
                    ? __('Please upload exactly 1 image for this layout.')
                    : __('Please upload exactly :count images for this layout.', ['count' => $expected]);

                $validator->errors()->add('images', $message);
            }
        });
    }
}
