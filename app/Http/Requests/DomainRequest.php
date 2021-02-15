<?php

namespace App\Http\Requests;

use App\Models\Domain;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class DomainRequest
 * @package App\Http\Requests
 */
class DomainRequest extends FormRequest
{
    private $domainRegex;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Преобразование данных перед валидацией.
     */
    protected function prepareForValidation(): void
    {
        $this->domainRegex = Domain::REGEX_DOMAIN;

        if ($this->has('domain')) {
            $this->merge(['domain' => trim($this->get('domain'))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain' => ['required', 'string', "regex:/$this->domainRegex/"],
        ];
    }
}
