<?php
 
namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
 
class ProductStoreRequest extends FormRequest
{
    public function authorize()
    {
        //return false;
        return true;
    }
 
    public function rules()
    {
        if(request()->isMethod('post')) {
            return [
                'name' => 'required|string|max:258',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string'
            ];
        } else {
            return [
                'name' => 'required|string|max:258',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'required|string'
            ];
        }
    }
 
    public function messages()
    {
        if(request()->isMethod('post')) {
            return [
                'name.required' => 'Nom du produit obligatoire!',
                'image.required' => 'Image obligatoire!',
                'description.required' => 'Descritpion obligatoire!'
            ];
        } else {
            return [
                'name.required' => 'Nom du produit obligatoire!',
                'description.required' => 'Descritpion obligatoire!'
            ];   
        }
    }
}