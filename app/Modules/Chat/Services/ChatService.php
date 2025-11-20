<?php

namespace App\Modules\Chat\Services;


use App\Modules\Chat\DTOs\CreateChatDTO;
use App\Modules\Chat\Models\Chat;
use App\Modules\Chat\Repositories\ChatRepository;
use App\Modules\User\Repositories\UserRepository;
use DomainException;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public function __construct(
        private readonly ChatRepository $repo,
        private readonly UserRepository $userRepo
    )
    {}

    public function createChat(CreateChatDTO $dto)
    {
        $myId = Auth::id();
        $user_id = $dto->user_id;

        if ($myId == $user_id) {
            throw new DomainException("You can't create chat with yourself");
        }

        $existingChat = Chat::whereHas('users', function($q) use ($myId) {
            $q->where('user_id', $myId);
        })
            ->whereHas('users', function($q) use ($user_id) {
                $q->where('user_id', $user_id);
            })
            ->withCount('users')
            ->get()
            ->firstWhere('users_count', 2);

        if ($existingChat) {
            return $existingChat;
        }

        $chat = $this->repo->create($dto->toArray());

        $chat->users()->attach([
            $myId,
            $user_id
        ]);

        return $chat;
    }

    public function userChats($user_id)
    {
        $chats = $this->userRepo->getUserChats($user_id);

        return $chats;
    }

    public function searchChats($query)
    {
        $chats = $this->repo->searchChats($query);

        return $chats;
    }

    public function getChat($id)
    {
        $chat = $this->repo->find($id);

        return $chat;
    }
}
