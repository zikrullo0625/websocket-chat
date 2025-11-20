<?php

namespace App\Modules\Chat\Repositories;

use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Models\Message;
use App\Modules\User\Models\User;

class ChatRepository
{
    public array $relations = [
        'messages',
        'users',
        'lastMessage'
    ];
    public function all()
    {
        return Chat::with($this->relations);
    }

    public function find(int $id)
    {
        return Chat::with($this->relations)->find($id);
    }

    public function searchChats(string $query)
    {
        return User::where('name', 'like', "%{$query}%")
            ->get();
    }

    public function create(array $data): Chat
    {
        return Chat::create($data);
    }

    public function update(int $id, array $data): ?Chat
    {
        $user = Chat::find($id);
        if (! $user) return null;

        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        $user = Chat::find($id);
        if (! $user) return false;

        return $user->delete();
    }

    public function setLastMessage(Message $message, Chat $chat)
    {
        $chat->last_message_id = $message->id;
        $chat->save();
    }
}
