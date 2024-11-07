<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'pickup_address' => 'string|max:255',
            'pickup_latitude' => 'numeric',
            'pickup_longitude' => 'numeric',
            'delivery_address' => 'string|max:255',
            'delivery_latitude' => 'numeric',
            'delivery_longitude' => 'numeric',
            'current_address' => 'nullable|string|max:255',
            'current_latitude' => 'nullable|numeric',
            'current_longitude' => 'nullable|numeric',
            'status' => 'sometimes|string|in:pending,in_progress,completed,canceled',
            'client_id' => 'exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
            'pickup_at' => 'date',     
            'delivered_at' => 'nullable|date',  
        ];
    
        // W przypadku tworzenia zamówienia wymaga niektórych pól
        if ($this->isMethod('post')) {
            $rules['pickup_at'] = 'required|date';
            $rules['pickup_address'] = 'required|string|max:255';
            $rules['pickup_latitude'] = 'required|numeric';
            $rules['pickup_longitude'] = 'required|numeric';
            $rules['delivery_address'] = 'required|string|max:255';
            $rules['delivery_latitude'] = 'required|numeric';
            $rules['delivery_longitude'] = 'required|numeric';
            $rules['client_id'] = 'required|exists:users,id';
        }
    
        return $rules;
        
    }
}
