<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCrushRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && !auth()->user()->crush;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $titleMax = config('content.crush.title_max', 100);
        $textMin = config('content.crush.text_min', 10);
        $textMax = config('content.crush.text_max', 1000);
        $imageMb = config('content.crush.image_max_mb', 3);
        
        return [
            'title' => [
                'nullable',
                'string',
                "max:$titleMax",
                'regex:/^[a-zA-Z0-9\s\-\_\'\,\.\!\?àâäéèêëïîôùûüÿçÀÂÄÉÈÊËÏÎÔÙÛÜŸÇ]+$/'
            ],
            'text' => [
                'required',
                'string',
                "min:$textMin",
                "max:$textMax"
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:' . ($imageMb * 1024),
                'dimensions:max_width=4096,max_height=4096'
            ],
            'categories' => [
                'nullable',
                'array',
                'max:5'
            ],
            'categories.*' => [
                'exists:categories,id'
            ]
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        $titleMax = config('content.crush.title_max', 100);
        $textMin = config('content.crush.text_min', 10);
        $textMax = config('content.crush.text_max', 1000);
        $imageMb = config('content.crush.image_max_mb', 3);
        
        return [
            'title.max' => "Le titre ne peut pas dépasser $titleMax caractères.",
            'title.regex' => 'Le titre contient des caractères non autorisés.',
            'text.required' => 'Le texte est obligatoire.',
            'text.min' => "Le texte doit contenir au moins $textMin caractères.",
            'text.max' => "Le texte ne peut pas dépasser $textMax caractères.",
            'image.max' => "L'image ne peut pas dépasser {$imageMb}MB.",
            'image.mimes' => 'L\'image doit être au format: jpeg, jpg, png, gif ou webp.',
            'image.dimensions' => 'L\'image est trop grande (max 4096x4096 pixels).',
            'categories.max' => 'Vous ne pouvez sélectionner que 5 catégories maximum.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Nettoyer les données avant validation
        if ($this->has('title')) {
            $this->merge([
                'title' => trim($this->title)
            ]);
        }

        if ($this->has('text')) {
            $this->merge([
                'text' => trim($this->text)
            ]);
        }
    }
}
