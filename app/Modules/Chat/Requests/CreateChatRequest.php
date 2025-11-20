<?php

namespace App\Modules\Chat\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateChatRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_id' => 'required'
        ];
    }
}
