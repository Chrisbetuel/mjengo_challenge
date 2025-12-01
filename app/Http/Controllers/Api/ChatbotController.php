<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotMessage;
use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Send message to chatbot
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|min:1|max:500',
        ]);

        try {
            $user = auth()->user();
            
            // Require authentication for sending messages
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please log in to use the chatbot.',
                ], 401);
            }
            
            $message = $request->input('message');

            // Initialize service with authenticated user
            $this->chatbotService = new ChatbotService($user);

            // Process message
            $result = $this->chatbotService->processMessage($message);

            // Save conversation to database
            $chatbotMessage = ChatbotMessage::create([
                'user_id' => $user->id,
                'user_message' => $message,
                'bot_response' => $result['response'],
                'message_type' => $result['message_type'],
                'context' => $result['context'],
            ]);

            return response()->json([
                'success' => true,
                'message_id' => $chatbotMessage->id,
                'response' => $result['response'],
                'message_type' => $result['message_type'],
                'timestamp' => $chatbotMessage->created_at,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred processing your message.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get chat history
     */
    public function getHistory(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        // Return empty if not authenticated
        if (!$user) {
            return response()->json([
                'success' => true,
                'count' => 0,
                'messages' => [],
            ]);
        }
        
        $limit = $request->input('limit', 20);
        $type = $request->input('type');

        $query = ChatbotMessage::where('user_id', $user->id)->orderBy('created_at', 'desc');

        if ($type && in_array($type, ['general', 'challenge', 'material', 'payment', 'group', 'savings', 'penalty', 'account'])) {
            $query->where('message_type', $type);
        }

        $messages = $query->limit($limit)->get()->reverse()->values();

        return response()->json([
            'success' => true,
            'count' => $messages->count(),
            'messages' => $messages,
        ]);
    }

    /**
     * Rate bot response
     */
    public function rateResponse(Request $request): JsonResponse
    {
        $request->validate([
            'message_id' => 'required|exists:chatbot_messages,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            $user = auth()->user();
            $messageId = $request->input('message_id');
            $rating = $request->input('rating');

            $chatbotMessage = ChatbotMessage::where('id', $messageId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $chatbotMessage->update(['rating' => $rating]);

            return response()->json([
                'success' => true,
                'message' => 'Thank you for your feedback!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }

    /**
     * Clear chat history
     */
    public function clearHistory(): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Return success if not authenticated
            if (!$user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Chat history cleared.',
                ]);
            }
            
            ChatbotMessage::where('user_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Chat history cleared.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
            ], 500);
        }
    }

    /**
     * Get chatbot suggestions
     */
    public function getSuggestions(): JsonResponse
    {
        $suggestions = [
            'Show me all challenges',
            'How much have I saved?',
            'What are my pending payments?',
            'Show available materials',
            'Tell me about Lipa Kidogo',
            'Show my active groups',
            'How do challenges work?',
            'Check my account info',
        ];

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }
}
