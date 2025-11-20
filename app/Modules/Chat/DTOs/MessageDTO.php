<?php

namespace App\Modules\Chat\DTOs;

use Illuminate\Http\Request;

class MessageDTO
{
    public function __construct(
        public int $chat_id,
        public ?int $user_id,
        public string $text
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            chat_id: $request->get('chat_id'),
            user_id: $request->user()?->id,
            text: $request->get('text')
        );
    }

    public function toArray(): array
    {
        return [
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'text' => $this->text,
        ];
    }
}
