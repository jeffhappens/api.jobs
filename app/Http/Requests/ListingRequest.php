<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListingRequest extends FormRequest
{

    public $ruleValue = 'url';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages() {
        return [
            'apply_link.value.required' => 'Apply Link is a required field',
            'apply_link.value.url' => 'Apply Link must be a valid URL',
            'apply_link.value.email' => 'Apply Link must be a valid Email Address',
        ];

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if($this->apply_link['type'] === 'email') {
            $this->ruleValue = 'email';
        }
        return [
            'title' => 'required',
            'company_id' => 'required',
            'apply_link.value' => 'required|'.$this->ruleValue,
            'description' => 'required'
        ];
    }
}
