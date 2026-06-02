<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        'product_name' => 'required|string|max:255',
        'product_imei' => 'required|numeric',
        'product_code'     => 'required|string|max:255',
        'brand_id' => 'required|integer|exists:brands,id',
        'series_id' => 'required|integer|exists:series,id',
        'color_id' => 'required|integer|exists:colors,id',
        'model_type_id' => 'required|integer|exists:model_types,id',
        'condition' => 'required|integer|in:1,2',
        'storage_id' => 'required|integer|exists:storages,id',
        'type_of_machine' => 'required|integer',
        'network_id' => 'nullable|required_if:type_of_machine,4|integer|exists:networks,id',
        'battery_percentage' => 'required|integer',
        'percentage' => 'required|integer',
        'purchase_price' => 'required|numeric',
        'selling_price' => 'numeric',
        'purchase_date' => 'required|date',
        'status' => 'required|integer|in:1,2,3,4',
        'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'note' => 'nullable|string',
    ];
    }
}
