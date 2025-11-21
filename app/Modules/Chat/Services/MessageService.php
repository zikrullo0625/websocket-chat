<?php

namespace App\Modules\Chat\Services;

use App\Events\MessageSent;
use App\Modules\Chat\DTOs\MessageDTO;
use App\Modules\Chat\Repositories\ChatRepository;
use App\Modules\Chat\Repositories\MessageRepository;

class MessageService
{
    public function __construct(
        private readonly ChatRepository $chatRepo,
        private readonly MessageRepository $repo
    ) {}

    public function getAll(?int $chat_id = null)
    {
        return $this->repo->getAll($chat_id);
    }

    public function getById(int $id)
    {
        return $this->repo->getById($id);
    }

    public function create(MessageDTO $dto)
    {
        $message = $this->repo->create($dto->toArray());
        $chat = $this->chatRepo->find($dto->chat_id);

        $this->chatRepo->setLastMessage($message, $chat);

//        broadcast(new MessageSent($message, $dto->chat_id));

        return $message;
    }

    public function update(int $id, MessageDTO $dto)
    {
        return $this->repo->update($id, $dto->toArray());
    }

    public function delete(int $id)
    {
        return $this->repo->delete($id);
    }
}
