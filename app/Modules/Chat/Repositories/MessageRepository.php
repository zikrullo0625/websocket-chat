<?php

namespace App\Modules\Chat\Repositories;

use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Models\Message;

class MessageRepository
{
    public function getAll(int $chat_id)
    {
        return Message::where('chat_id', $chat_id)->orderBy('created_at')->get();
    }

    public function getById(int $id): ?Message
    {
        return Message::find($id);
    }

    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function update(int $id, array $data): ?Message
    {
        $message = $this->getById($id);
        if ($message) {
            $message->update($data);
        }
        return $message;
    }

    public function delete(int $id): bool
    {
        $message = $this->getById($id);
        if ($message) {
            return (bool) $message->delete();
        }
        return false;
    }
}
