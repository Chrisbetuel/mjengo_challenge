# Mjengo Chatbot - Deployment Guide

## Pre-Deployment Checklist

- [ ] All files created successfully
- [ ] No merge conflicts
- [ ] Database backup taken (production)
- [ ] Environment variables configured
- [ ] CSRF token meta tag in layout
- [ ] Authentication middleware active

---

## Deployment Steps

### Step 1: Pull/Merge Code

```bash
# If using git
git add .
git commit -m "Add Mjengo Chatbot system"
git push origin main
```

Or copy files manually if not using version control.

### Step 2: Run Database Migration

```bash
# Development
php artisan migrate

# Production (with confirmation)
php artisan migrate --force
```

This creates the `chatbot_messages` table.

### Step 3: Clear Cache (if applicable)

```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Step 4: Verify Installation

#### Check Files Exist

```bash
# Backend files
ls app/Models/ChatbotMessage.php
ls app/Services/ChatbotService.php
ls app/Http/Controllers/Api/ChatbotController.php
ls config/chatbot.php

# Frontend files
ls public/js/chatbot.js
ls public/css/chatbot.css
ls resources/views/components/chatbot.blade.php

# Documentation
ls CHATBOT_*.md
```

#### Check Routes

```bash
php artisan route:list | grep chatbot
```

You should see:
```
POST   /api/chatbot/send
GET    /api/chatbot/history
POST   /api/chatbot/rate
POST   /api/chatbot/clear
GET    /api/chatbot/suggestions
```

#### Check Database Table

```bash
php artisan tinker
>>> Schema::hasTable('chatbot_messages')
=> true
```

### Step 5: Test the Chatbot

1. Log in to the application
2. Navigate to any authenticated page (except login/register)
3. Look for golden chat button in bottom-right corner
4. Click to open chat widget
5. Type a test message: "Show me all challenges"
6. Verify response is returned

### Step 6: Verify Database Entries

```bash
php artisan tinker
>>> App\Models\ChatbotMessage::count()
=> 1  (or more if tested)

>>> App\Models\ChatbotMessage::latest()->first()
```

---

## Configuration

### Environment Variables (Optional)

Add to `.env` if you want to override defaults:

```env
CHATBOT_ENABLED=true
CHATBOT_THEME=light
```

These are optional - defaults are already set in `config/chatbot.php`.

---

## Monitoring

### Check Conversations

```bash
# Total conversations
php artisan tinker
>>> App\Models\ChatbotMessage::count()

# By user
>>> App\Models\ChatbotMessage::where('user_id', 1)->count()

# By message type
>>> App\Models\ChatbotMessage::select('message_type')->groupBy('message_type')->get()

# Average rating
>>> App\Models\ChatbotMessage::avg('rating')

# Recent conversations
>>> App\Models\ChatbotMessage::latest()->limit(10)->get()
```

### Database Queries

```sql
-- Total conversations
SELECT COUNT(*) FROM chatbot_messages;

-- Conversations by type
SELECT message_type, COUNT(*) as count FROM chatbot_messages GROUP BY message_type;

-- Average response rating
SELECT AVG(rating) as avg_rating FROM chatbot_messages WHERE rating IS NOT NULL;

-- Most active users
SELECT user_id, COUNT(*) as messages FROM chatbot_messages GROUP BY user_id ORDER BY messages DESC LIMIT 10;

-- Messages not yet rated
SELECT COUNT(*) FROM chatbot_messages WHERE rating IS NULL;
```

---

## Rollback Procedure (If Needed)

### To Disable Chatbot Without Removing Code

1. Update `config/chatbot.php`:
```php
'enabled' => false,  // Change to false
```

2. Or remove component from `resources/views/layouts/app.blade.php`:
```blade
{{-- Comment out or remove:
@component('components.chatbot') @endcomponent
--}}
```

### To Remove Chatbot Completely

```bash
# Backup first
php artisan migrate:rollback

# Delete files
rm -f app/Models/ChatbotMessage.php
rm -f app/Services/ChatbotService.php
rm -f app/Http/Controllers/Api/ChatbotController.php
rm -f config/chatbot.php
rm -f public/js/chatbot.js
rm -f public/css/chatbot.css
rm -f resources/views/components/chatbot.blade.php

# Revert route changes in routes/api.php
# Revert layout changes in resources/views/layouts/app.blade.php
```

---

## Performance Optimization

### 1. Database Optimization

```bash
# Analyze table
php artisan tinker
>>> DB::statement('ANALYZE TABLE chatbot_messages;')

# Optimize table
>>> DB::statement('OPTIMIZE TABLE chatbot_messages;')
```

### 2. Cache Configuration

In `config/chatbot.php`, suggestions can be cached:

```php
// Add caching
$suggestions = Cache::remember('chatbot_suggestions', 3600, function () {
    return config('chatbot.suggestions');
});
```

### 3. Archive Old Messages

```php
// Archive messages older than 90 days
use Carbon\Carbon;

$cutoffDate = Carbon::now()->subDays(
    config('chatbot.storage.keep_history_days', 90)
);

ChatbotMessage::where('created_at', '<', $cutoffDate)->delete();
```

---

## Security Hardening

### 1. Rate Limiting

Add rate limiting to API routes in `routes/api.php`:

```php
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::prefix('chatbot')->group(function () {
        // Chatbot routes
    });
});
```

### 2. Input Validation

Already implemented in `ChatbotController@sendMessage`:
- Max message length: 500 characters
- Message type validation
- User authorization check

### 3. CORS (if needed for mobile apps)

```bash
php artisan make:middleware CorsMiddleware
```

---

## Troubleshooting Deployment

### Issue: Migration fails

**Solution:**
```bash
# Check if table already exists
php artisan tinker
>>> Schema::hasTable('chatbot_messages')

# If true, create migration to add missing columns
php artisan make:migration add_missing_columns_to_chatbot_messages
```

### Issue: 404 on API endpoints

**Solution:**
```bash
# Clear route cache
php artisan route:clear

# Verify routes exist
php artisan route:list | grep chatbot
```

### Issue: CSRF token missing

**Solution:**
Verify in `resources/views/layouts/app.blade.php`:
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### Issue: Chat widget not showing

**Solution:**
1. Check user is authenticated
2. Check browser console (F12) for errors
3. Verify route is not in exclude list
4. Check CSS/JS files loaded in Network tab

### Issue: Messages not saving

**Solution:**
```bash
# Verify table structure
php artisan tinker
>>> Schema::getColumns('chatbot_messages')

# Check user exists
>>> App\Models\User::find(1)

# Test insert manually
>>> App\Models\ChatbotMessage::create([
    'user_id' => 1,
    'user_message' => 'test',
    'bot_response' => 'test response',
    'message_type' => 'general',
])
```

---

## Post-Deployment

### 1. User Communication

Inform users about new chatbot feature:
- Email announcement
- In-app notification
- Help documentation
- Tutorial/video

### 2. Monitor First Week

- Check error logs
- Monitor database growth
- Review user feedback (ratings)
- Track feature usage

### 3. Gather Feedback

- Analyze low-rated responses
- Identify missing features
- Plan improvements
- Document enhancement requests

### 4. Plan Maintenance

- Schedule weekly monitoring
- Plan quarterly optimizations
- Update documentation as needed
- Plan for feature additions

---

## Maintenance Tasks

### Weekly
- [ ] Check error logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor database size: `chatbot_messages` table
- [ ] Review low ratings: (rating < 3)

### Monthly
- [ ] Archive old messages (>90 days)
- [ ] Analyze query patterns
- [ ] Update documentation
- [ ] Plan improvements

### Quarterly
- [ ] Full database optimization
- [ ] Performance review
- [ ] Security audit
- [ ] Feature planning

---

## Useful Commands

```bash
# Check if migration was run
php artisan migrate:status

# View table structure
php artisan tinker
>>> Schema::getColumns('chatbot_messages')

# Count conversations
>>> App\Models\ChatbotMessage::count()

# Export conversations (CSV)
>>> $messages = App\Models\ChatbotMessage::all();
>>> $messages->to_array()

# Clear all messages (careful!)
>>> App\Models\ChatbotMessage::truncate()

# Find user's messages
>>> App\Models\ChatbotMessage::where('user_id', 1)->get()

# Find messages by type
>>> App\Models\ChatbotMessage::where('message_type', 'challenge')->get()

# Delete old messages
>>> App\Models\ChatbotMessage::where('created_at', '<', now()->subDays(90))->delete()
```

---

## Support & Documentation

- **Quick Start:** `CHATBOT_QUICKSTART.md`
- **Full Documentation:** `CHATBOT_DOCUMENTATION.md`
- **Integration Details:** `CHATBOT_INTEGRATION.md`
- **Implementation Summary:** `CHATBOT_IMPLEMENTATION_SUMMARY.md`

---

## Success Criteria

After deployment, verify:

- ✅ Migration runs without errors
- ✅ Chatbot widget appears on authenticated pages
- ✅ Messages can be sent and received
- ✅ Responses are relevant and helpful
- ✅ Messages are stored in database
- ✅ User ratings work
- ✅ Chat history loads
- ✅ No console errors
- ✅ API endpoints respond correctly
- ✅ Performance is acceptable

---

## Emergency Contacts

For urgent issues:
1. Check logs: `storage/logs/laravel.log`
2. Review error: PHP error message or API response
3. Check database: `chatbot_messages` table exists
4. Verify configuration: `config/chatbot.php`
5. Test API: Manual request to `/api/chatbot/send`

---

## Final Notes

- Keep database backups before each deployment
- Monitor system after deployment
- Collect user feedback for improvements
- Document any customizations made
- Plan for future enhancements
- Share success with team

---

**Deployment Completed:** ✅

**Next Steps:**
1. Run migration
2. Test chatbot
3. Monitor usage
4. Gather feedback
5. Plan improvements

---

**Last Updated:** November 27, 2025  
**Status:** Ready for Deployment
