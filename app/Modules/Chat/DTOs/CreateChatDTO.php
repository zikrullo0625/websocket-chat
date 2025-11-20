<?php

namespace App\Modules\Chat\DTOs;

use App\Modules\Chat\Requests\CreateChatRequest;

class CreateChatDTO
{
    public function __construct(
        public string $user_id,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
        );
    }

    public static function fromRequest(CreateChatRequest $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->user_id
        ];
    }
}
