<?php

namespace App\Modules\Chat\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Chat\DTOs\CreateChatDTO;
use App\Modules\Chat\Requests\CreateChatRequest;
use App\Modules\Chat\Requests\SearchChatRequest;
use App\Modules\Chat\Services\ChatService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ChatController extends Controller
{
    public function __construct(
        public ChatService $chatService
    )
    {}
    public function createChat(CreateChatRequest $request)
    {
        $chat = $this->chatService->createChat(CreateChatDTO::fromRequest($request));

        return response()->json([
            'success' => true,
            'chat' => $chat
        ]);
    }

    public function myChats()
    {
        $chats = $this->chatService->userChats(Auth::id());

        return response()->json([
            'success' => true,
            'chats' => $chats
        ]);
    }

    public function getChat($id)
    {
        $chat = $this->chatService->getChat($id);

        return response()->json([
            'success' => true,
            'chat' => $chat
        ]);
    }

    public function searchChats(SearchChatRequest $request)
    {
        $chat = $this->chatService->searchChats($request->validated()['query']);

        return response()->json([
            'success' => true,
            'chats' => $chat
        ]);
    }
}
