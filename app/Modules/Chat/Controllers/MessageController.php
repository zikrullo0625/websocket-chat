<?php

namespace App\Modules\Chat\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Chat\Requests\MessageRequest;
use App\Modules\Chat\Services\MessageService;
use App\Modules\Chat\DTOs\MessageDTO;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $messageService) {}

    public function index(Request $request)
    {
        $validated = $request->validate([
            'chat_id' => 'required'
        ]);

        $messages = $this->messageService->getAll($validated['chat_id']);

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    public function show(int $id)
    {
        $message = $this->messageService->getById($id);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function store(MessageRequest $request): \Illuminate\Http\JsonResponse
    {
        $dto = MessageDTO::fromRequest($request);
        $message = $this->messageService->create($dto);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function update(MessageRequest $request, int $id)
    {
        $dto = MessageDTO::fromRequest($request);
        $message = $this->messageService->update($id, $dto);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function destroy(int $id)
    {
        $this->messageService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted'
        ]);
    }
}
