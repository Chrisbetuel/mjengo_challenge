#!/usr/bin/env php
<?php

/**
 * Chatbot Testing Script
 * Tests the chatbot service locally
 * 
 * Usage: php tests/chatbot-test.php
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../bootstrap/app.php';

use App\Services\ChatbotService;
use App\Models\User;

$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Test messages
$testQueries = [
    // Challenge queries
    'Show me all challenges',
    'What are my active challenges?',
    'How do challenges work?',
    
    // Material queries
    'Show available materials',
    'Tell me about Lipa Kidogo',
    
    // Payment queries
    'What are my pending payments?',
    'Show payment history',
    
    // Group queries
    'Show all groups',
    'My groups',
    
    // Savings queries
    'How much have I saved?',
    
    // Account queries
    'My account info',
    
    // General queries
    'Help',
    'What can you do?',
];

echo "===========================================\n";
echo "Mjengo Chatbot Service Test\n";
echo "===========================================\n\n";

// Get a test user (first admin or regular user)
$user = User::first();

if (!$user) {
    echo "❌ No users found in database. Please create a user first.\n";
    exit(1);
}

echo "Testing with user: {$user->username} (ID: {$user->id})\n";
echo "-------------------------------------------\n\n";

// Initialize service
$service = new ChatbotService($user);

// Test each query
$testCount = 0;
$successCount = 0;

foreach ($testQueries as $query) {
    $testCount++;
    echo "Test {$testCount}: {$query}\n";
    
    try {
        $result = $service->processMessage($query);
        
        if ($result && isset($result['response'])) {
            $successCount++;
            echo "✓ Message Type: {$result['message_type']}\n";
            echo "✓ Response Length: " . strlen($result['response']) . " characters\n";
            
            // Show first 100 chars of response
            $preview = substr($result['response'], 0, 100) . '...';
            echo "✓ Preview: {$preview}\n";
            echo "✓ Status: PASSED\n";
        } else {
            echo "✗ Invalid response format\n";
            echo "✗ Status: FAILED\n";
        }
    } catch (Exception $e) {
        echo "✗ Error: {$e->getMessage()}\n";
        echo "✗ Status: FAILED\n";
    }
    
    echo "\n";
}

// Summary
echo "-------------------------------------------\n";
echo "Test Summary\n";
echo "-------------------------------------------\n";
echo "Total Tests: {$testCount}\n";
echo "Passed: {$successCount}\n";
echo "Failed: " . ($testCount - $successCount) . "\n";
echo "Success Rate: " . round(($successCount / $testCount) * 100, 2) . "%\n\n";

if ($successCount === $testCount) {
    echo "✓ All tests passed!\n";
    exit(0);
} else {
    echo "✗ Some tests failed.\n";
    exit(1);
}
