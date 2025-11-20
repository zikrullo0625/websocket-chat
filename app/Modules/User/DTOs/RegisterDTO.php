<?php

namespace App\Modules\User\DTOs;

use App\Modules\User\Requests\RegisterRequest;

class RegisterDTO
{
    public function __construct(
        public string $email,
        public string $password,
        public string $name
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            email: $data['email'],
            password: $data['password'],
            name: $data['name']
        );
    }

    public static function fromRequest(RegisterRequest $request): self
    {
        return self::fromArray($request->validated());
    }

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
            'name' => $this->name,
        ];
    }
}
