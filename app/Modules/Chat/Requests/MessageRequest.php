<?php

namespace App\Modules\Chat\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'chat_id' => 'required|integer|exists:chats,id',
            'text' => 'required|string|max:1000',
        ];
    }
}
