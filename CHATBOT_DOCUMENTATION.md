# Mjengo Chatbot System Documentation

## Overview

The Mjengo Chatbot is an intelligent assistant integrated into the Mjengo Challenge platform. It provides users with:
- Real-time answers to questions about the system
- Database queries and information retrieval
- Interactive guidance through platform features
- Message history and conversation management

## Features

### 1. **Intelligent Query Processing**
The chatbot understands user queries across multiple domains:
- **Challenges**: Information about active challenges, joining, progress tracking
- **Materials**: Product catalog, pricing, availability, Lipa Kidogo options
- **Payments**: Payment status, history, pending amounts
- **Groups**: Group information, membership, creation guidance
- **Savings**: Total savings, progress by challenge, accumulated amounts
- **Penalties**: Active penalties, reasons, status
- **Account**: User profile information, account details

### 2. **Database Integration**
The chatbot queries the database to provide:
- Real-time challenge information
- Material availability and pricing
- User savings and payment history
- Group membership and details
- Active penalties and violations
- User account information

### 3. **User-Friendly Interface**
- Floating chat widget in bottom-right corner
- Clean, modern UI with animations
- Responsive design for mobile and desktop
- Dark/Light theme support
- Message history with suggestions

### 4. **Conversation Management**
- Stores all conversations in database
- User feedback rating system (1-5 stars)
- Chat history retrieval
- Clear history functionality
- Smart suggestions based on context

## File Structure

```
app/
├── Models/
│   └── ChatbotMessage.php              # Conversation storage model
├── Services/
│   └── ChatbotService.php              # Core AI logic and query processing
└── Http/Controllers/Api/
    └── ChatbotController.php           # API endpoints

database/
└── migrations/
    └── 2025_11_27_create_chatbot_messages_table.php

resources/
├── views/components/
│   └── chatbot.blade.php               # Blade component
├── js/
│   └── chatbot.js                      # Frontend widget
└── css/
    └── chatbot.css                     # Styling

routes/
└── api.php                             # API routes configuration
```

## Installation & Setup

### 1. Run Migration
```bash
php artisan migrate
```

This creates the `chatbot_messages` table to store conversations.

### 2. The chatbot is automatically loaded in `resources/views/layouts/app.blade.php`

The component loads automatically for all authenticated users except on login/register pages.

## API Endpoints

### Send Message
**POST** `/api/chatbot/send`

Send a message to the chatbot and get a response.

**Request:**
```json
{
    "message": "Show me all active challenges"
}
```

**Response:**
```json
{
    "success": true,
    "message_id": 123,
    "response": "📊 Here are the available challenges...",
    "message_type": "challenge",
    "timestamp": "2025-11-27T10:30:00Z"
}
```

### Get Chat History
**GET** `/api/chatbot/history?limit=20&type=challenge`

Retrieve user's chat history with optional filtering.

**Response:**
```json
{
    "success": true,
    "count": 5,
    "messages": [
        {
            "id": 1,
            "user_message": "Show challenges",
            "bot_response": "...",
            "message_type": "challenge"
        }
    ]
}
```

### Rate Response
**POST** `/api/chatbot/rate`

Submit feedback rating for a bot response.

**Request:**
```json
{
    "message_id": 123,
    "rating": 5
}
```

### Clear History
**POST** `/api/chatbot/clear`

Delete all conversations for current user.

### Get Suggestions
**GET** `/api/chatbot/suggestions`

Get list of suggested questions for the user.

## Query Types

### Challenge Queries
```
"Show me all challenges"
"What are my active challenges?"
"How do challenges work?"
"How many participants in this challenge?"
"How much have I paid for challenges?"
```

**Returns:**
- List of active challenges with details
- User's active challenges with progress
- Challenge mechanics explanation

### Material Queries
```
"Show available materials"
"What materials are in stock?"
"Tell me about Lipa Kidogo"
"What's the price of materials?"
"My Lipa Kidogo plans"
```

**Returns:**
- Material catalog with prices
- Lipa Kidogo plan information
- User's active payment plans

### Payment Queries
```
"What are my pending payments?"
"How do I make a payment?"
"Show payment history"
"Payment methods available"
```

**Returns:**
- Pending payment details
- Payment instructions
- Recent transaction history

### Group Queries
```
"Show all groups"
"My groups"
"How to create a group?"
"Group members"
```

**Returns:**
- Active groups list
- User's group membership
- Group creation guidance

### Savings Queries
```
"How much have I saved?"
"My savings progress"
"Total accumulated"
```

**Returns:**
- Total savings amount
- Breakdown by challenge
- Progress metrics

### Penalty Queries
```
"Do I have penalties?"
"Active violations"
"Why was I penalized?"
```

**Returns:**
- Active penalties (if any)
- Penalty details and reasons

### Account Queries
```
"My account info"
"Profile details"
"Update account"
```

**Returns:**
- User profile information
- Account details

## ChatbotService Class

### Key Methods

#### `processMessage(string $message): array`
Main entry point for processing user messages. Returns an array with:
- `response`: The chatbot's reply
- `message_type`: Category of the message
- `context`: Additional contextual data

#### Message Type Detection Methods
- `isAboutChallenges()` - Detects challenge-related queries
- `isAboutMaterials()` - Detects material/product queries
- `isAboutPayments()` - Detects payment-related queries
- `isAboutGroups()` - Detects group-related queries
- `isAboutSavings()` - Detects savings/statistics queries
- `isAboutPenalties()` - Detects penalty-related queries
- `isAboutAccount()` - Detects account-related queries

#### Handler Methods (Private)
- `handleChallengeQuery()`
- `handleMaterialQuery()`
- `handlePaymentQuery()`
- `handleGroupQuery()`
- `handleSavingsQuery()`
- `handlePenaltyQuery()`
- `handleAccountQuery()`
- `handleGeneralQuery()`

### Usage Example

```php
use App\Services\ChatbotService;

$service = new ChatbotService(auth()->user());
$result = $service->processMessage("Show me all challenges");

echo $result['response'];        // The bot's response
echo $result['message_type'];    // 'challenge'
```

## Frontend Widget

### Initialization
The widget automatically initializes when the page loads for authenticated users:

```javascript
window.chatbot = new ChatbotWidget({
    position: 'bottom-right',
    theme: 'light',
    title: 'Mjengo Assistant',
    subtitle: 'Ask me anything!'
});
```

### Public Methods

#### `sendMessage(message)`
Send a message to the chatbot.

```javascript
window.chatbot.sendMessage("Show me all challenges");
```

#### `toggleChat()`
Open/close the chat window.

```javascript
window.chatbot.toggleChat();
```

#### `clearHistory()`
Clear chat history with confirmation.

```javascript
window.chatbot.clearHistory();
```

### Events & Callbacks

Messages are sent via fetch API to `/api/chatbot/send` with CSRF token authentication.

## Database Schema

### chatbot_messages table

```sql
CREATE TABLE chatbot_messages (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    user_message LONGTEXT NOT NULL,
    bot_response LONGTEXT NOT NULL,
    message_type VARCHAR(255) DEFAULT 'general',
    context JSON NULL,
    rating INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);
```

## Customization

### Adding New Query Types

To add a new query type to the chatbot:

1. Add keyword detection method in `ChatbotService`:
```php
private function isAboutNewTopic(string $message): bool
{
    $keywords = ['keyword1', 'keyword2', 'keyword3'];
    return $this->messageContainsAny($message, $keywords);
}
```

2. Add handler in `processMessage()`:
```php
elseif ($this->isAboutNewTopic($message)) {
    $response = $this->handleNewTopicQuery($message, $context);
    $messageType = 'new_topic';
}
```

3. Implement handler method:
```php
private function handleNewTopicQuery(string $message, array &$context): string
{
    // Query database and format response
    return "Response text";
}
```

### Customizing the Widget

Edit `public/css/chatbot.css` to modify:
- Color scheme (primary, dark, light colors)
- Widget size and positioning
- Animation timing
- Font and text styling

Change `public/js/chatbot.js` to modify:
- Widget behavior
- Message formatting
- API endpoints
- Suggestion loading

## Security Features

- CSRF token validation on all API endpoints
- Authentication required (middleware: `auth:sanctum`)
- User isolation - users can only access their own conversation history
- Input validation on message length (max 500 characters)
- XSS protection through proper escaping in frontend

## Performance Considerations

- Messages indexed by user_id and created_at for fast queries
- Lazy loading of chat history
- Efficient database queries with relationship loading
- Pagination support for large message histories

## Troubleshooting

### Chatbot not appearing
- Ensure user is authenticated
- Check browser console for JavaScript errors
- Verify CSRF token is present in meta tag
- Check if route is in excluded list (login, register)

### API returns 401
- User not authenticated
- Session expired
- Invalid CSRF token

### Messages not saving
- Check database migration was run
- Verify user_id foreign key constraint
- Check user is authenticated

### Styling issues
- Verify CSS file is loaded
- Check for CSS conflicts with Bootstrap
- Clear browser cache

## Future Enhancements

- [ ] Natural Language Processing (NLP) integration
- [ ] Machine Learning for better intent detection
- [ ] Multi-language support
- [ ] File upload capability
- [ ] Voice input/output
- [ ] Advanced analytics and reporting
- [ ] Admin chatbot management panel
- [ ] Custom response templates
- [ ] Escalation to human support
- [ ] Chatbot training from feedback

## Support

For issues or questions about the chatbot system, please:
1. Check the documentation
2. Review the ChatbotService class comments
3. Check the browser console for errors
4. Review Laravel logs at `storage/logs/laravel.log`

## API Reference Summary

| Method | Endpoint | Purpose |
|--------|----------|---------|
| POST | `/api/chatbot/send` | Send message and get response |
| GET | `/api/chatbot/history` | Retrieve chat history |
| POST | `/api/chatbot/rate` | Rate a response |
| POST | `/api/chatbot/clear` | Clear chat history |
| GET | `/api/chatbot/suggestions` | Get suggested questions |

---

**Version:** 1.0  
**Last Updated:** November 27, 2025  
**Status:** Production Ready
