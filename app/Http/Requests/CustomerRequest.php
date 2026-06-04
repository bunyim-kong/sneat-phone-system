<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'customer_type' => 'required|integer',
            'gender' => 'required|integer',
            'nationality' => 'required|integer'
        ] ;
        // Check if customer_type is Corporate (value 2)
        if ($this->input('customer_type') == 2) {
            $rules = array_merge($rules, [
                'customer_company_name' => 'required|string|max:255',
                'customer_company_name_latin' => 'required|string|max:255',
                'company_commune' => 'required|string|max:255',
                'company_district' => 'required|string|max:255',
                'company_province' => 'required|string|max:255',
                'company_phone' => 'required|string|max:20',
                'salary' => 'required|numeric',
                'other_income' => 'numeric',
                'date_income' => 'required',
                'guarantor_name' => 'required|string|max:255',
                'guarantor_latin_name' => 'required|string|max:255',
                'guarantor_gender' => 'required|in:1,2',
                'guarantor_relationship' => 'required|in:0,1,2,3,4',
                'guarantor_nationality' => 'required|in:1,2',
                'guarantor_phone' => 'required|string|max:20',
                'guarantor_id_card_number' => 'required|string|min:9',
                'guarantor_dob' => 'required|date',
                'guarantor_status' => 'required|in:1,2',
                'guarantor_house_number' => 'required|string|max:220',
                'guarantor_street_number' => 'required|string|max:220',
                'guarantor_group_number' => 'required|string|max:220',
                'guarantor_village' => 'required|string|max:220',
                'guarantor_district' => 'required|string|max:220',
                'guarantor_commune' => 'required|string|max:220',
                'guarantor_province' => 'required|string|max:220',
                'guarantor_property_owner' => 'required|in:1,2,3,4',
                'guarantor_facebook' => 'required|string|max:220',
            ]);
        }

        return $rules;
    }
}
