<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ChatbotMessage;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $userId = Auth::id();

        // Save user message
        ChatbotMessage::create([
            'user_id' => $userId,
            'message' => $userMessage,
            'is_bot' => false,
        ]);

        // Generate bot response (simple implementation)
        $botResponse = $this->generateBotResponse($userMessage);

        // Save bot response
        ChatbotMessage::create([
            'user_id' => $userId,
            'message' => $botResponse,
            'is_bot' => true,
        ]);

        return response()->json([
            'response' => $botResponse,
        ]);
    }

    private function generateBotResponse($userMessage)
    {
        $message = strtolower($userMessage);

        // Simple keyword-based responses
        if (strpos($message, 'hello') !== false || strpos($message, 'hi') !== false) {
            return "Hello! How can I help you with your savings and investment journey today?";
        }

        if (strpos($message, 'challenge') !== false) {
            return "Challenges are a great way to save money! You can participate in various challenges to build your savings habit. Would you like me to show you available challenges?";
        }

        if (strpos($message, 'lipa kidogo') !== false) {
            return "Lipa Kidogo is our installment payment plan that helps you purchase materials in affordable monthly payments. It's perfect for bigger investments!";
        }

        if (strpos($message, 'direct purchase') !== false) {
            return "Direct purchases allow you to buy materials immediately. This is great for smaller items or when you have the funds available.";
        }

        if (strpos($message, 'group') !== false) {
            return "Groups help you save together with friends or community members. You can create or join groups to pool your savings and achieve bigger goals!";
        }

        if (strpos($message, 'help') !== false) {
            return "I can help you with information about challenges, Lipa Kidogo plans, direct purchases, groups, and general savings advice. What would you like to know?";
        }

        // Default response
        return "I'm here to help you with your savings journey. You can ask me about challenges, Lipa Kidogo, direct purchases, groups, or general savings tips!";
    }
}
