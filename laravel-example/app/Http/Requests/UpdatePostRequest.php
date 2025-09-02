<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    protected function prepareForValidation()
    {
        $title = $this->input('title');
        if ($title && !$this->filled('slug')) {
            $this->merge(['slug' => Str::slug($title)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
        public function rules(): array
    {

        $postId = $this->route('post');
        return [
            'title' => ['sometimes', 'string', 'min:4', 'max:200'],
            'slug' => [
                'sometimes', 'string', 'max:220', 'unique:posts,slug',
                Rule::unique('posts', 'slug')
                ->ignore($postId)
                ->whereNull('deleted_at') // Respetamos el SoftDeletes
            ],
            'content' => ['sometimes', 'string', 'min:20'],

            'status' => ['sometimes', Rule::in(['draft', 'published', 'archived', 'default'])],
            'published_at' => ['nullable', 'date', 'required_if:status,published', 'before_or_equal:now'],
            'cover_image' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp', 'max:2048'],

            'tags'=>['nullable', 'array', 'max:20'],
            'tags.*' => ['string', 'min:2', 'max:30', 'distinct'],
            'meta' => ['nullable', 'array'],
            'meta.seo_title' =>['nullable', 'string', 'max:60'],
            'meta.seo_desc' =>['nullable', 'string', 'max:120'],

            'category_ids' => ['nullable', 'array', 'max:10'],
            'category_ids.*' => ['integer', 'exists:categories,id']
        ];
    }
}
