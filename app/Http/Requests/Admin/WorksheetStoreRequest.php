<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\Models\PageType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorksheetStoreRequest extends FormRequest
{
    private ?PageType $pageType = null;

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
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'page_type_id' => ['required', Rule::exists('page_types', 'id')],
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $pageType = $this->resolvePageType();

            if (! $pageType) {
                return;
            }

            $expected = $pageType->sections_count;
            $provided = count($this->file('images', []));

            if ($expected !== $provided) {
                $message = $expected === 1
                    ? __('Please upload exactly 1 image for this layout.')
                    : __('Please upload exactly :count images for this layout.', ['count' => $expected]);

                $validator->errors()->add('images', $message);
            }
        });
    }

    private function resolvePageType(): ?PageType
    {
        if ($this->pageType instanceof PageType) {
            return $this->pageType;
        }

        $pageTypeId = $this->input('page_type_id');
        if (! $pageTypeId) {
            return null;
        }

        return $this->pageType = PageType::query()->find($pageTypeId);
    }
}
