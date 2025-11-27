# Chatbot Integration Guide

## Overview

This document explains how the chatbot is integrated into the Mjengo Challenge application and how to use it.

## Current Integration

### 1. **Blade Layout Integration**
The chatbot component is loaded in `resources/views/layouts/app.blade.php`:

```blade
{{-- Chatbot Component --}}
@component('components.chatbot') @endcomponent
```

This ensures the chatbot appears on every authenticated page automatically.

### 2. **Route Exclusion**
The chatbot component checks the current route and excludes:
- `login` - Login page
- `register` - Registration page  
- `admin.login` - Admin login page
- `password.forgot` - Forgot password page
- `password.reset` - Reset password page

### 3. **API Routes**
Chatbot API endpoints are registered in `routes/api.php` under the `/api/chatbot` prefix:

```php
Route::prefix('chatbot')->name('chatbot.')->group(function () {
    Route::post('/send', [ChatbotController::class, 'sendMessage'])->name('send');
    Route::get('/history', [ChatbotController::class, 'getHistory'])->name('history');
    Route::post('/rate', [ChatbotController::class, 'rateResponse'])->name('rate');
    Route::post('/clear', [ChatbotController::class, 'clearHistory'])->name('clear');
    Route::get('/suggestions', [ChatbotController::class, 'getSuggestions'])->name('suggestions');
});
```

## Database Integration

### Chatbot Messages Table

The chatbot stores all conversations in the `chatbot_messages` table:

```sql
- id (BIGINT) - Primary key
- user_id (BIGINT) - Foreign key to users table
- user_message (LONGTEXT) - The user's input
- bot_response (LONGTEXT) - The chatbot's response
- message_type (VARCHAR) - Category of the message
- context (JSON) - Additional context data
- rating (INT) - User feedback rating (1-5)
- created_at / updated_at (TIMESTAMP) - Timestamps
```

### Model Queries

The chatbot queries these models to fetch data:

#### Users
```php
$user->getActiveChallenges()
$user->getTotalSavings()
$user->getActivePenalties()
$user->getActiveGroups()
```

#### Challenges
```php
Challenge::where('status', 'active')->get()
$challenge->getTotalCollected()
$challenge->activeParticipants()
```

#### Materials
```php
Material::where('status', 'active')->get()
$material->directPurchases()
$material->lipaKidogoPlans()
```

#### Payments
```php
Payment::where('status', 'pending')->get()
Payment::where('status', 'paid')->get()
```

#### Groups
```php
Group::where('status', 'active')->get()
$group->getMemberCount()
$group->isUserMember($userId)
```

#### LipaKidogo Plans
```php
$user->lipaKidogoPlans()->where('status', 'active')->get()
```

#### Penalties
```php
$user->getActivePenalties()
```

## Service Architecture

### ChatbotService Class

Located in `app/Services/ChatbotService.php`, this is the core of the chatbot:

```php
class ChatbotService {
    public function processMessage(string $message): array
    
    // Query type detection
    private function isAboutChallenges(string $message): bool
    private function isAboutMaterials(string $message): bool
    private function isAboutPayments(string $message): bool
    private function isAboutGroups(string $message): bool
    private function isAboutSavings(string $message): bool
    private function isAboutPenalties(string $message): bool
    private function isAboutAccount(string $message): bool
    
    // Query handlers
    private function handleChallengeQuery()
    private function handleMaterialQuery()
    private function handlePaymentQuery()
    private function handleGroupQuery()
    private function handleSavingsQuery()
    private function handlePenaltyQuery()
    private function handleAccountQuery()
    private function handleGeneralQuery()
    
    // Utility methods
    private function messageContainsAny(string $message, array $keywords): bool
    private function containsKeywords(string $message, array $keywords): bool
}
```

### Controller Layer

Located in `app/Http/Controllers/Api/ChatbotController.php`:

```php
class ChatbotController {
    public function sendMessage(Request $request): JsonResponse
    public function getHistory(Request $request): JsonResponse
    public function rateResponse(Request $request): JsonResponse
    public function clearHistory(): JsonResponse
    public function getSuggestions(): JsonResponse
}
```

## Frontend Implementation

### JavaScript Widget

Located in `public/js/chatbot.js`:

```javascript
class ChatbotWidget {
    constructor(config = {})
    init()
    
    // User interactions
    toggleChat()
    handleFormSubmit(e)
    sendMessage(message)
    
    // UI management
    createWidgetHTML()
    attachEventListeners()
    addMessage(text, sender, metadata)
    
    // Data management
    loadChatHistory()
    loadSuggestions()
    rateResponse(messageId, rating)
    clearHistory()
    
    // Utilities
    formatMessageText(text)
    scrollToBottom()
}
```

### CSS Styling

Located in `public/css/chatbot.css`:

- Widget positioning and animations
- Message bubble styling
- Input area design
- Responsive breakpoints for mobile/tablet/desktop
- Dark/light theme support

## Data Flow Diagram

```
User Types Message
        ↓
JavaScript Widget (chatbot.js)
        ↓
Fetch to /api/chatbot/send
        ↓
ChatbotController::sendMessage()
        ↓
ChatbotService::processMessage()
    ├─→ Detect message type
    ├─→ Query relevant models
    ├─→ Format response
    └─→ Return result array
        ↓
Store in ChatbotMessage model
        ↓
Return JSON response
        ↓
JavaScript displays in widget
        ↓
User sees response with options to:
- Rate the response
- Send another message
- Clear history
- View suggestions
```

## Adding Features

### Adding a New Query Type

1. **Add detection method in ChatbotService:**
```php
private function isAboutNewFeature(string $message): bool
{
    $keywords = ['keyword1', 'keyword2'];
    return $this->messageContainsAny($message, $keywords);
}
```

2. **Add handler in processMessage():**
```php
elseif ($this->isAboutNewFeature($message)) {
    $response = $this->handleNewFeatureQuery($message, $context);
    $messageType = 'new_feature';
}
```

3. **Implement handler method:**
```php
private function handleNewFeatureQuery(string $message, array &$context): string
{
    // Query models and build response
    return $response;
}
```

4. **Update config if needed:**
```php
// config/chatbot.php
'features' => [
    'new_feature' => true,
],
```

### Customizing the UI

Edit `public/css/chatbot.css` to modify:
- Colors (CSS variables at top of file)
- Widget size
- Positioning
- Animations
- Responsive breakpoints

Edit `public/js/chatbot.js` to modify:
- Widget initialization options
- Message formatting
- API endpoints
- Event handling

## Security Considerations

### Authentication
- All endpoints require `auth:sanctum` middleware
- Users can only access their own conversations
- CSRF token validation on all POST requests

### Validation
- Message length limited to 500 characters
- Message type must be in allowed list
- Rating must be between 1-5
- User ID validated from authentication

### Authorization
- Users cannot access other users' messages
- Admin routes should be added separately if needed

## Performance Optimization

1. **Database Indexing**
   - Messages indexed by `user_id` and `created_at`
   - Foreign key indexed for fast joins

2. **Lazy Loading**
   - Chat history loaded only when requested
   - Suggestions loaded on widget init

3. **Query Optimization**
   - Use `limit()` for result sets
   - Eager load relationships with `with()`
   - Use `select()` to fetch only needed columns

4. **Caching**
   - Suggestions can be cached
   - Material listings could use cache

## Monitoring & Analytics

### Data Available

All conversations are stored with:
- User ID
- Query type (challenge, material, etc.)
- User feedback rating (1-5)
- Timestamps

### Queries to Monitor

```php
// Most common query types
ChatbotMessage::select('message_type')
    ->groupBy('message_type')
    ->get();

// Average response ratings
ChatbotMessage::avg('rating');

// High volume users
ChatbotMessage::select('user_id')
    ->groupBy('user_id')
    ->orderByRaw('count(*) desc')
    ->limit(10)
    ->get();
```

## Troubleshooting

### Common Issues

1. **Chatbot not showing**
   - Check if user is authenticated
   - Verify route is not in exclude list
   - Check browser console for errors

2. **API returns 401**
   - Session expired
   - Invalid CSRF token
   - User not authenticated

3. **Database errors**
   - Migration not run: `php artisan migrate`
   - Wrong connection string in .env
   - Table doesn't exist

4. **Styling issues**
   - CSS file not loading: check browser Network tab
   - CSS conflicts: check Bootstrap version
   - Browser cache: clear cache or use incognito

## Migration & Deployment

1. **Local Development**
   ```bash
   php artisan migrate
   npm run dev  # if needed
   ```

2. **Production**
   ```bash
   php artisan migrate --force
   ```

3. **Clear Cache (if needed)**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   ```

## Configuration Options

From `config/chatbot.php`:

```php
'enabled' => true                    // Enable/disable chatbot
'theme' => 'light'                   // 'light' or 'dark'
'show_suggestions' => true           // Show suggested questions
'show_rating' => true                // Show rating buttons
'max_message_length' => 500          // Max characters per message
'keep_history_days' => 90            // How long to keep messages
```

## Next Steps

1. ✅ Migration: `php artisan migrate`
2. ✅ Test: Open any authenticated page and look for chat widget
3. ✅ Monitor: Check database for conversations
4. ✅ Customize: Adjust config and CSS as needed
5. ✅ Extend: Add more query types as needed

---

**Integration Status:** ✅ Complete  
**Testing Status:** Ready  
**Deployment Status:** Ready  
**Documentation Status:** ✅ Complete
