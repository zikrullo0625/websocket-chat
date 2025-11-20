<?php

namespace App\Modules\Chat\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchChatRequest extends FormRequest
{
    public function rules()
    {
        return [
            'query' => 'required'
        ];
    }
}
