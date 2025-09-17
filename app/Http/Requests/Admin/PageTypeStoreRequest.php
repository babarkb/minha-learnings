<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageTypeStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'columns' => ['required', 'integer', 'min:1', 'max:10'],
            'rows' => ['required', 'integer', 'min:1', 'max:10'],
            'logo' => ['nullable', 'image', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $columns = (int) $this->input('columns', 0);
            $rows = (int) $this->input('rows', 0);

            if ($columns * $rows === 0) {
                return;
            }

            if ($columns * $rows > 12) {
                $validator->errors()->add(
                    'columns',
                    __('The selected layout must contain 12 sections or fewer.'),
                );
            }
        });
    }
}
