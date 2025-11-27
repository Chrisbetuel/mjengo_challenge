# Mjengo Chatbot - Quick Setup Guide

## What's New?

The Mjengo Challenge platform now includes an AI-powered chatbot assistant that helps users:
- Answer questions about challenges, materials, payments, groups, and savings
- Fetch real-time data from the database
- Navigate the platform with guidance
- Access their account and transaction information

## Quick Start

### 1. Run Database Migration
```bash
php artisan migrate
```

This creates the `chatbot_messages` table to store all conversations.

### 2. The Chatbot is Ready!
The chatbot will automatically appear on all pages for authenticated users (except login/register).

- Look for the **golden chat button** in the bottom-right corner
- Click to open the chat window
- Start asking questions!

## Example Questions to Try

### Challenges
- "Show me all challenges"
- "What are my active challenges?"
- "How do challenges work?"

### Materials
- "Show available materials"
- "Tell me about Lipa Kidogo"
- "My Lipa Kidogo plans"

### Payments
- "What are my pending payments?"
- "Show payment history"
- "How do I make a payment?"

### Groups
- "Show all groups"
- "My groups"
- "How to create a group?"

### Savings
- "How much have I saved?"
- "My savings progress"

### Account
- "My account info"
- "Profile details"

### General Help
- "Help"
- "What can you do?"
- "About this system"

## Files Created

### Backend
- `app/Models/ChatbotMessage.php` - Conversation storage model
- `app/Services/ChatbotService.php` - AI logic and query processing (800+ lines)
- `app/Http/Controllers/Api/ChatbotController.php` - API endpoints
- `database/migrations/2025_11_27_create_chatbot_messages_table.php` - Database table

### Frontend
- `public/js/chatbot.js` - Widget JavaScript (400+ lines)
- `public/css/chatbot.css` - Widget styling (500+ lines)
- `resources/views/components/chatbot.blade.php` - Blade component

### Configuration
- Updated `routes/api.php` - Added chatbot API routes
- Updated `resources/views/layouts/app.blade.php` - Integrated chatbot

### Documentation
- `CHATBOT_DOCUMENTATION.md` - Full technical documentation

## Architecture

```
User Message
    ↓
ChatbotController::sendMessage()
    ↓
ChatbotService::processMessage()
    ├── Detect query type (challenge, material, payment, etc.)
    ├── Query database models
    ├── Format response
    └── Return result
    ↓
Save to ChatbotMessage model
    ↓
Return JSON response
    ↓
Frontend displays in chat widget
```

## Key Features

### 1. **Intelligent Query Understanding**
- Recognizes 7+ query categories
- Natural language processing with keyword matching
- Context-aware responses

### 2. **Real-Time Database Queries**
- Fetches live data from all relevant models
- Shows user-specific information
- Updates automatically

### 3. **Modern Chat Interface**
- Floating widget with animations
- Message history
- Suggested quick questions
- Rating system for feedback

### 4. **Comprehensive Coverage**
Every authenticated page includes the chatbot EXCEPT:
- Login page
- Register page
- Admin login page
- Password reset pages

### 5. **Data Persistence**
- All conversations stored in database
- Users can view chat history
- Feedback ratings collected for improvements

## API Endpoints

All endpoints require authentication (`auth:sanctum`):

```
POST   /api/chatbot/send           - Send a message
GET    /api/chatbot/history        - Get chat history
POST   /api/chatbot/rate           - Rate a response (1-5)
POST   /api/chatbot/clear          - Clear chat history
GET    /api/chatbot/suggestions    - Get suggested questions
```

## Configuration

### Widget Customization

In `public/js/chatbot.js`, modify the initialization:

```javascript
window.chatbot = new ChatbotWidget({
    position: 'bottom-right',      // Widget position
    theme: 'light',                // 'light' or 'dark'
    title: 'Mjengo Assistant',     // Widget title
    subtitle: 'Ask me anything!',  // Widget subtitle
});
```

### Styling

Edit `public/css/chatbot.css` to customize:
- Colors (update CSS variables at top)
- Size and positioning
- Animations and effects
- Responsive breakpoints

## Database Schema

The chatbot stores conversations in `chatbot_messages` table:

| Column | Type | Purpose |
|--------|------|---------|
| id | BIGINT | Primary key |
| user_id | BIGINT | User reference |
| user_message | LONGTEXT | What user asked |
| bot_response | LONGTEXT | Chatbot's answer |
| message_type | VARCHAR | Category (challenge, material, etc.) |
| context | JSON | Additional data context |
| rating | INT | User feedback (1-5) |
| created_at | TIMESTAMP | Message timestamp |

## Performance

- Efficient database indexing on user_id and created_at
- Lazy loading of chat history
- Optimized query building with relationships
- Caching of suggestions

## Security

- ✅ CSRF token validation
- ✅ Authentication required
- ✅ User isolation (can't see others' data)
- ✅ Input validation
- ✅ XSS protection

## Troubleshooting

### Chatbot not showing?
1. Make sure you're logged in
2. Check browser console (F12) for errors
3. Verify CSRF token exists: `<meta name="csrf-token">`
4. Clear browser cache

### Getting error 401?
- Your session may have expired
- Try logging out and back in
- Verify CSRF token is fresh

### Messages not sending?
- Check browser network tab (F12)
- Verify API response
- Check Laravel logs: `storage/logs/laravel.log`

## Next Steps

### For Developers
1. Review `CHATBOT_DOCUMENTATION.md` for full technical details
2. Check `ChatbotService.php` for query logic
3. Extend with new query types as needed
4. Customize styling in `chatbot.css`

### For Administrators
1. Monitor chatbot conversations in database
2. Review user feedback ratings
3. Analyze common questions
4. Plan improvements based on feedback

## Support & Questions

For more detailed information, see `CHATBOT_DOCUMENTATION.md` which includes:
- Detailed API reference
- How to add new query types
- Architecture explanation
- Advanced customization
- Future enhancement plans

---

## Summary

✅ **Fully functional chatbot integrated**
✅ **7 query categories covered** (challenges, materials, payments, groups, savings, penalties, account)
✅ **Database queries working** for all content
✅ **Beautiful modern UI** with animations
✅ **Production ready** with security measures
✅ **Easily extensible** for future features

Enjoy your new Mjengo Assistant! 🤖
