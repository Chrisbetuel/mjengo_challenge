<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chatbot Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Mjengo Chatbot system
    |
    */

    'enabled' => env('CHATBOT_ENABLED', true),

    'widget' => [
        'position' => 'bottom-right',
        'theme' => env('CHATBOT_THEME', 'light'),
        'title' => 'Mjengo Assistant',
        'subtitle' => 'Ask me anything!',
        'show_suggestions' => true,
        'show_rating' => true,
    ],

    'storage' => [
        'keep_history_days' => 90,
        'max_messages_per_user' => 1000,
    ],

    'api' => [
        'max_message_length' => 500,
        'response_timeout' => 30,
    ],

    'features' => [
        'challenges' => true,
        'materials' => true,
        'payments' => true,
        'groups' => true,
        'savings' => true,
        'penalties' => true,
        'account' => true,
        'general_help' => true,
        
    ],

    'suggestions' => [
        'Show me all challenges',
        'How much have I saved?',
        'What are my pending payments?',
        'Show available materials',
        'Tell me about Lipa Kidogo',
        'Show my active groups',
        'How do challenges work?',
        'Check my account info',
    ],

    'exclude_routes' => [
        'login',
        'register',
        'admin.login',
        'password.forgot',
        'password.reset',
    ],

    'message_types' => [
        'general' => 'General Inquiry',
        'challenge' => 'Challenge Related',
        'material' => 'Material/Product',
        'payment' => 'Payment Related',
        'group' => 'Group Related',
        'savings' => 'Savings/Statistics',
        'penalty' => 'Penalty Related',
        'account' => 'Account Information',
    ],
];
