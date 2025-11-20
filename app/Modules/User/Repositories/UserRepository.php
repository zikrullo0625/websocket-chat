<?php

namespace App\Modules\User\Repositories;

use App\Modules\User\Models\User;

class UserRepository
{
    public array $chatRelations = [
        'messages',
        'lastMessage',
        'users'
    ];

    public function all()
    {
        return User::all();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int $id, array $data): ?User
    {
        $user = User::find($id);
        if (! $user) return null;

        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        $user = User::find($id);
        if (! $user) return false;

        return $user->delete();
    }

    public function getUserChats($user_id)
    {
        $user = $this->find($user_id);
        return $user->chats->load($this->chatRelations);
    }
}
