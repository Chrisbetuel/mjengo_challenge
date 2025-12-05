<?php

namespace App\Services;

use App\Models\Challenge;
use App\Models\Material;
use App\Models\User;
use App\Models\Group;
use App\Models\Participant;
use App\Models\Payment;
use App\Models\LipaKidogo;
use App\Models\GroupMember;
use App\Models\Penalty;
use Illuminate\Support\Str;

class ChatbotService
{
    protected $user;

    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Process user message and generate bot response
     */
    public function processMessage(string $message): array
    {
        $message = strtolower(trim($message));
        $messageType = 'general';
        $response = '';
        $context = [];

        // Check message intent and category - greetings first!
        if ($this->isGreeting($message)) {
            $response = $this->handleGreeting($message);
            $messageType = 'greeting';
        } elseif ($this->isFarewell($message)) {
            $response = $this->handleFarewell();
            $messageType = 'farewell';
        } elseif ($this->isThanks($message)) {
            $response = $this->handleThanks();
            $messageType = 'thanks';
        } elseif ($this->isAboutChallenges($message)) {
            $response = $this->handleChallengeQuery($message, $context);
            $messageType = 'challenge';
        } elseif ($this->isAboutMaterials($message)) {
            $response = $this->handleMaterialQuery($message, $context);
            $messageType = 'material';
        } elseif ($this->isAboutPayments($message)) {
            $response = $this->handlePaymentQuery($message, $context);
            $messageType = 'payment';
        } elseif ($this->isAboutGroups($message)) {
            $response = $this->handleGroupQuery($message, $context);
            $messageType = 'group';
        } elseif ($this->isAboutSavings($message)) {
            $response = $this->handleSavingsQuery($message, $context);
            $messageType = 'savings';
        } elseif ($this->isAboutPenalties($message)) {
            $response = $this->handlePenaltyQuery($message, $context);
            $messageType = 'penalty';
        } elseif ($this->isAboutAccount($message)) {
            $response = $this->handleAccountQuery($message, $context);
            $messageType = 'account';
        } elseif ($this->isAboutDirectPurchase($message)) {
            $response = $this->handleDirectPurchaseQuery($message, $context);
            $messageType = 'purchase';
        } elseif ($this->isAboutNotifications($message)) {
            $response = $this->handleNotificationQuery($message, $context);
            $messageType = 'notification';
        } else {
            $response = $this->handleGeneralQuery($message, $context);
            $messageType = 'general';
        }

        return [
            'response' => $response,
            'message_type' => $messageType,
            'context' => $context,
        ];
    }

    /**
     * Check if message is a greeting
     */
    private function isGreeting(string $message): bool
    {
        $greetings = [
            'hello', 'hi', 'hey', 'hola', 'jambo', 'habari', 'mambo', 
            'good morning', 'good afternoon', 'good evening', 'good day',
            'morning', 'afternoon', 'evening', 'howdy', 'sup', 'whats up',
            "what's up", 'greetings', 'salaam', 'salam', 'yo', 'hallo'
        ];
        return $this->messageContainsAny($message, $greetings);
    }

    /**
     * Handle greeting messages
     */
    private function handleGreeting(string $message): string
    {
        $greetings = [
            "👋 Hello! Welcome to Mjengo Challenge! I'm your assistant and I'm here to help you with:\n\n" .
            "🏆 **Challenges** - Daily savings challenges\n" .
            "🛠️ **Materials** - Building materials & tools\n" .
            "💳 **Lipa Kidogo** - Flexible installment payments\n" .
            "👥 **Groups** - Community savings groups\n" .
            "💰 **Savings** - Track your progress\n\n" .
            "How can I help you today?",

            "👋 Jambo! Karibu Mjengo Challenge! I'm here to assist you with your building and savings journey.\n\n" .
            "Ask me about challenges, materials, payments, or anything else. What would you like to know?",

            "👋 Hi there! Great to see you! I'm your Mjengo Challenge assistant.\n\n" .
            "I can help you with:\n" .
            "• Joining savings challenges\n" .
            "• Finding building materials\n" .
            "• Managing payments\n" .
            "• Tracking your savings\n\n" .
            "What can I help you with?",
        ];

        return $greetings[array_rand($greetings)];
    }

    /**
     * Check if message is a farewell
     */
    private function isFarewell(string $message): bool
    {
        $farewells = ['bye', 'goodbye', 'see you', 'later', 'take care', 'goodnight', 'good night', 'kwaheri', 'tutaonana'];
        return $this->messageContainsAny($message, $farewells);
    }

    /**
     * Handle farewell messages
     */
    private function handleFarewell(): string
    {
        $farewells = [
            "👋 Goodbye! Keep saving and building your dreams! See you soon! 🏗️",
            "👋 Kwaheri! Remember, every small savings brings you closer to your goals. Take care!",
            "👋 See you later! Don't forget to check your challenges. Keep building! 💪",
            "👋 Bye for now! Wishing you success in your building journey! 🏠",
        ];
        return $farewells[array_rand($farewells)];
    }

    /**
     * Check if message is expressing thanks
     */
    private function isThanks(string $message): bool
    {
        $thanks = ['thank', 'thanks', 'asante', 'shukran', 'appreciate', 'grateful', 'thx'];
        return $this->messageContainsAny($message, $thanks);
    }

    /**
     * Handle thank you messages
     */
    private function handleThanks(): string
    {
        $responses = [
            "😊 You're welcome! I'm always here to help. Is there anything else you'd like to know?",
            "🙏 Karibu sana! Happy to help! Feel free to ask if you have more questions.",
            "😊 My pleasure! Don't hesitate to ask if you need more assistance with your savings journey!",
            "🤗 Asante! I'm glad I could help. Let me know if there's anything else!",
        ];
        return $responses[array_rand($responses)];
    }

    /**
     * Check if message is about direct purchase
     */
    private function isAboutDirectPurchase(string $message): bool
    {
        $keywords = ['direct purchase', 'direct buy', 'buy now', 'purchase now', 'immediate purchase', 'instant buy'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle direct purchase queries
     */
    private function handleDirectPurchaseQuery(string $message, array &$context): string
    {
        return "🛒 **Direct Purchase**\n\n" .
            "Direct Purchase allows you to buy building materials immediately with full payment.\n\n" .
            "**How it works:**\n" .
            "1️⃣ Browse our materials catalog\n" .
            "2️⃣ Select the item you want\n" .
            "3️⃣ Choose 'Direct Purchase'\n" .
            "4️⃣ Complete payment\n" .
            "5️⃣ Receive your materials!\n\n" .
            "**Benefits:**\n" .
            "✅ Instant ownership\n" .
            "✅ No installment fees\n" .
            "✅ Quick delivery\n\n" .
            "Type 'show materials' to browse available products!";
    }

    /**
     * Check if message is about notifications
     */
    private function isAboutNotifications(string $message): bool
    {
        $keywords = ['notification', 'notifications', 'alert', 'alerts', 'reminder', 'reminders', 'updates'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle notification queries
     */
    private function handleNotificationQuery(string $message, array &$context): string
    {
        return "🔔 **Notifications**\n\n" .
            "Stay updated with our notification system!\n\n" .
            "**You'll receive notifications for:**\n" .
            "📅 Payment reminders\n" .
            "🏆 Challenge updates\n" .
            "👥 Group activities\n" .
            "💰 Savings milestones\n" .
            "📦 Material availability\n" .
            "⚠️ Penalty alerts\n\n" .
            "Check your notifications in the bell icon on your dashboard!";
    }

    /**
     * Check if message is about challenges
     */
    private function isAboutChallenges(string $message): bool
    {
        $keywords = ['challenge', 'challenges', 'join', 'active', 'participate', 'trophy', 'daily amount', 'end date', 'start date'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle challenge-related queries
     */
    private function handleChallengeQuery(string $message, array &$context): string
    {
        if ($this->containsKeywords($message, ['list', 'all', 'available', 'show', 'view'])) {
            return $this->getChallengesList();
        }

        if ($this->containsKeywords($message, ['join', 'participate'])) {
            if ($this->user) {
                return $this->getJoinChallengeInfo();
            }
            return "Please log in to join a challenge.";
        }

        if ($this->containsKeywords($message, ['my', 'active', 'current'])) {
            if ($this->user) {
                return $this->getUserActiveChallenges();
            }
            return "Please log in to view your active challenges.";
        }

        if ($this->containsKeywords($message, ['how', 'work', 'does'])) {
            return $this->getChallengesExplanation();
        }

        return $this->getChallengeOverview();
    }

    /**
     * Get list of all active challenges
     */
    private function getChallengesList(): string
    {
        $challenges = Challenge::where('status', 'active')->get();

        if ($challenges->isEmpty()) {
            return "No active challenges available at the moment. Please check back later!";
        }

        $response = "📊 Here are the available challenges:\n\n";
        foreach ($challenges as $challenge) {
            $response .= "🏆 **" . $challenge->name . "**\n";
            $response .= "   Daily: TZS " . number_format($challenge->daily_amount, 2) . "\n";
            $response .= "   Duration: " . $challenge->start_date->format('M d') . " - " . $challenge->end_date->format('M d') . "\n";
            $response .= "   Participants: " . $challenge->activeParticipants()->count() . "/" . $challenge->max_participants . "\n";
            $response .= "   Total Collected: TZS " . number_format($challenge->getTotalCollected(), 2) . "\n\n";
        }
        return $response;
    }
    /**
     * Get user's active challenges
     */
    private function getUserActiveChallenges(): string
    {
        $challenges = $this->user->getActiveChallenges();

        if ($challenges->isEmpty()) {
            return "You haven't joined any challenges yet. 🤔\n\nWould you like to join one? Type 'show challenges' to see what's available!";
        }
        $response = "💪 Your Active Challenges:\n\n";
        foreach ($challenges as $participant) {
            $challenge = $participant->challenge;
            $paid = $participant->getTotalPaid();
            $response .= "🏆 " . $challenge->name . "\n";
            $response .= "   Status: " . ucfirst($participant->status) . "\n";
            $response .= "   Daily Amount: TZS " . number_format($challenge->daily_amount, 2) . "\n";
            $response .= "   Paid So Far: TZS " . number_format($paid, 2) . "\n";
            $response .= "   Ends: " . $challenge->end_date->format('M d, Y') . "\n\n";
        }

        return $response;
    }

    /**
     * Check if message is about materials
     */
    private function isAboutMaterials(string $message): bool
    {
        $keywords = ['material', 'materials', 'products', 'tools', 'equipment', 'building', 'purchase', 'lipa kidogo', 'buy', 'order', 'stock'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle material-related queries
     */
    private function handleMaterialQuery(string $message, array &$context): string
    {
        if ($this->containsKeywords($message, ['list', 'all', 'available', 'show', 'view'])) {
            return $this->getMaterialsList();
        }

        if ($this->containsKeywords($message, ['lipa kidogo', 'installment', 'payment plan'])) {
            return $this->getLipaKidogoInfo();
        }

        if ($this->containsKeywords($message, ['price', 'cost', 'how much'])) {
            return $this->getMaterialPricing();
        }

        if ($this->containsKeywords($message, ['my', 'plans'])) {
            if ($this->user) {
                return $this->getUserLipaKidogoPlans();
            }
            return "Please log in to view your plans.";
        }

        return $this->getMaterialOverview();
    }

    /**
     * Get list of available materials
     */
    private function getMaterialsList(): string
    {
        $materials = Material::where('status', 'active')->get();

        if ($materials->isEmpty()) {
            return "No materials available at the moment.";
        }

        $response = "🛠️ Available Materials:\n\n";
        foreach ($materials->take(10) as $material) {
            $response .= "📦 **" . $material->name . "**\n";
            $response .= "   Category: " . $material->category . "\n";
            $response .= "   Price: TZS " . number_format($material->price, 2) . "\n";
            $response .= "   In Stock: " . $material->stock_quantity . " units\n";
            $response .= "   Description: " . Str::limit($material->description, 80) . "\n\n";
        }

        return $response;
    }

    /**
     * Get Lipa Kidogo explanation
     */
    private function getLipaKidogoInfo(): string
    {
        return "💳 **Lipa Kidogo (Pay Little by Little)** is our flexible payment plan!\n\n" .
            "How it works:\n" .
            "✅ Choose a material you want\n" .
            "✅ Pay in small installments over time\n" .
            "✅ No hefty upfront payment needed\n" .
            "✅ Make payments as you save from challenges\n\n" .
            "Perfect for expensive tools and building materials! 🏗️\n\n" .
            "Type 'show my plans' to see your active Lipa Kidogo plans.";
    }

    /**
     * Check if message is about payments
     */
    private function isAboutPayments(string $message): bool
    {
        $keywords = ['payment', 'payments', 'paid', 'pay', 'pending', 'due', 'receipt', 'transaction', 'money', 'balance'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle payment-related queries
     */
    private function handlePaymentQuery(string $message, array &$context): string
    {
        if ($this->containsKeywords($message, ['my', 'status', 'pending', 'due'])) {
            if ($this->user) {
                return $this->getUserPaymentStatus();
            }
            return "Please log in to check payment status.";
        }

        if ($this->containsKeywords($message, ['how', 'make', 'process'])) {
            return $this->getPaymentInstructions();
        }

        if ($this->containsKeywords($message, ['history', 'recent', 'past'])) {
            if ($this->user) {
                return $this->getUserPaymentHistory();
            }
            return "Please log in to view payment history.";
        }

        return $this->getPaymentOverview();
    }

    /**
     * Get user payment status
     */
    private function getUserPaymentStatus(): string
    {
        $pendingPayments = Payment::whereHas('participant', function ($q) {
            $q->where('user_id', $this->user->id);
        })->where('status', 'pending')->get();

        if ($pendingPayments->isEmpty()) {
            return "✅ Great! You have no pending payments. Keep up the savings!";
        }

        $response = "⏰ You have " . $pendingPayments->count() . " pending payment(s):\n\n";
        $totalDue = 0;

        foreach ($pendingPayments as $payment) {
            $response .= "💰 " . $payment->participant->challenge->name . "\n";
            $response .= "   Amount: TZS " . number_format($payment->amount, 2) . "\n";
            $response .= "   Due: " . $payment->due_date->format('M d, Y') . "\n\n";
            $totalDue += $payment->amount;
        }

        $response .= "**Total Due: TZS " . number_format($totalDue, 2) . "**";

        return $response;
    }

    /**
     * Check if message is about groups
     */
    private function isAboutGroups(string $message): bool
    {
        $keywords = ['group', 'groups', 'team', 'members', 'leader', 'join group', 'create group', 'fellowship'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle group-related queries
     */
    private function handleGroupQuery(string $message, array &$context): string
    {
        if ($this->containsKeywords($message, ['list', 'all', 'available', 'show', 'view'])) {
            return $this->getGroupsList();
        }

        if ($this->containsKeywords($message, ['my', 'joined', 'member'])) {
            if ($this->user) {
                return $this->getUserGroups();
            }
            return "Please log in to view your groups.";
        }

        if ($this->containsKeywords($message, ['create', 'start', 'new'])) {
            return "To create a group, go to the Groups section and click 'Create New Group'. Set a name, description, and maximum members!";
        }

        return $this->getGroupsOverview();
    }

    /**
     * Get list of all groups
     */
    private function getGroupsList(): string
    {
        $groups = Group::where('status', 'active')->get();

        if ($groups->isEmpty()) {
            return "No active groups available at the moment.";
        }

        $response = "👥 Active Groups:\n\n";
        foreach ($groups->take(10) as $group) {
            $response .= "🏘️ **" . $group->name . "**\n";
            $response .= "   Leader: " . $group->leader->username . "\n";
            $response .= "   Members: " . $group->getMemberCount() . "/" . $group->max_members . "\n";
            $response .= "   Description: " . Str::limit($group->description, 60) . "\n\n";
        }

        return $response;
    }

    /**
     * Check if message is about savings
     */
    private function isAboutSavings(string $message): bool
    {
        $keywords = ['saving', 'savings', 'total', 'progress', 'how much', 'accumulated', 'amount saved'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle savings-related queries
     */
    private function handleSavingsQuery(string $message, array &$context): string
    {
        if ($this->user) {
            $totalSavings = $this->user->getTotalSavings();
            $activeChallenges = $this->user->getActiveChallenges();

            $response = "💰 **Your Savings Summary**\n\n";
            $response .= "Total Saved: TZS " . number_format($totalSavings, 2) . "\n";
            $response .= "Active Challenges: " . $activeChallenges->count() . "\n\n";

            if ($activeChallenges->isNotEmpty()) {
                $response .= "Progress by Challenge:\n";
                foreach ($activeChallenges as $participant) {
                    $paid = $participant->getTotalPaid();
                    $response .= "• " . $participant->challenge->name . ": TZS " . number_format($paid, 2) . "\n";
                }
            }

            return $response;
        }

        return "Please log in to check your savings.";
    }

    /**
     * Check if message is about penalties
     */
    private function isAboutPenalties(string $message): bool
    {
        $keywords = ['penalty', 'penalties', 'penalty', 'fine', 'late', 'missed', 'violation'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle penalty-related queries
     */
    private function handlePenaltyQuery(string $message, array &$context): string
    {
        if ($this->user) {
            $penalties = $this->user->getActivePenalties();

            if ($penalties->isEmpty()) {
                return "✅ Good news! You have no active penalties.";
            }

            $response = "⚠️ You have " . $penalties->count() . " active penalty/penalties:\n\n";
            foreach ($penalties as $penalty) {
                $response .= "🚨 **" . $penalty->reason . "**\n";
                $response .= "   Amount: TZS " . number_format($penalty->amount, 2) . "\n";
                $response .= "   Status: " . ucfirst($penalty->status) . "\n";
                $response .= "   Date: " . $penalty->created_at->format('M d, Y') . "\n\n";
            }

            return $response;
        }

        return "Please log in to check penalties.";
    }

    /**
     * Check if message is about account
     */
    private function isAboutAccount(string $message): bool
    {
        $keywords = ['account', 'profile', 'personal', 'settings', 'info', 'information', 'details', 'phone', 'email'];
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Handle account-related queries
     */
    private function handleAccountQuery(string $message, array &$context): string
    {
        if ($this->user) {
            $response = "👤 **Your Account Information**\n\n";
            $response .= "Username: " . $this->user->username . "\n";
            $response .= "Email: " . $this->user->email . "\n";
            $response .= "Phone: " . $this->user->phone_number . "\n";
            $response .= "Role: " . ucfirst($this->user->role) . "\n";

            return $response;
        }

        return "Please log in to view account information.";
    }

    /**
     * Handle general queries with system overview
     */
    private function handleGeneralQuery(string $message, array &$context): string
    {
        if ($this->containsKeywords($message, ['help', 'what can', 'how can'])) {
            return $this->getHelpMenu();
        }

        if ($this->containsKeywords($message, ['about', 'system', 'explain', 'what is', 'tell me about', 'describe'])) {
            return $this->getSystemOverview();
        }

        if ($this->containsKeywords($message, ['how are you', 'how do you do', 'how you doing'])) {
            return $this->handleHowAreYou();
        }

        if ($this->containsKeywords($message, ['who are you', 'what are you', 'your name'])) {
            return $this->handleWhoAreYou();
        }

        if ($this->containsKeywords($message, ['features', 'services', 'offer', 'provide', 'do you have'])) {
            return $this->getFeaturesOverview();
        }

        if ($this->containsKeywords($message, ['register', 'sign up', 'create account', 'join platform'])) {
            return $this->getRegistrationInfo();
        }

        if ($this->containsKeywords($message, ['login', 'sign in', 'log in', 'access account'])) {
            return $this->getLoginInfo();
        }

        if ($this->containsKeywords($message, ['contact', 'support', 'reach', 'customer service'])) {
            return $this->getContactInfo();
        }

        if ($this->containsKeywords($message, ['dashboard', 'home', 'main page'])) {
            return $this->getDashboardInfo();
        }

        if ($this->containsKeywords($message, ['oweru', 'mjengo'])) {
            return $this->getSystemOverview();
        }

        return $this->getDefaultResponse();
    }

    /**
     * Handle "how are you" questions
     */
    private function handleHowAreYou(): string
    {
        $responses = [
            "😊 I'm doing great, thanks for asking! I'm here and ready to help you with your savings and building materials. How can I assist you today?",
            "🤖 I'm functioning perfectly and ready to serve! What would you like to know about Mjengo Challenge?",
            "💪 I'm excellent! Always energized to help users like you achieve their building dreams. What can I help you with?",
        ];
        return $responses[array_rand($responses)];
    }

    /**
     * Handle "who are you" questions
     */
    private function handleWhoAreYou(): string
    {
        return "🤖 **About Me**\n\n" .
            "I'm the Mjengo Challenge Assistant - your friendly chatbot!\n\n" .
            "**What I can do:**\n" .
            "💬 Answer questions about the platform\n" .
            "🏆 Help you find challenges to join\n" .
            "🛠️ Show you available materials\n" .
            "💰 Check your savings progress\n" .
            "📊 Explain how features work\n" .
            "❓ Guide you through the system\n\n" .
            "I'm here 24/7 to assist you on your building journey! Just ask away! 😊";
    }

    /**
     * Get comprehensive features overview
     */
    private function getFeaturesOverview(): string
    {
        return "🌟 **Mjengo Challenge Features**\n\n" .
            "**1. 🏆 Daily Challenges**\n" .
            "Join savings challenges with daily targets. Save consistently and build your fund!\n\n" .
            "**2. 🛠️ Building Materials**\n" .
            "Access quality construction materials - tools, equipment, and building supplies.\n\n" .
            "**3. 💳 Lipa Kidogo (Pay Little)**\n" .
            "Buy materials through affordable installment plans. No big upfront costs!\n\n" .
            "**4. 🛒 Direct Purchase**\n" .
            "Buy materials immediately with full payment for instant ownership.\n\n" .
            "**5. 👥 Savings Groups**\n" .
            "Create or join groups to save together with friends and community.\n\n" .
            "**6. 💰 Savings Tracking**\n" .
            "Monitor your progress, view history, and celebrate milestones!\n\n" .
            "**7. 🔔 Notifications**\n" .
            "Stay updated with payment reminders and challenge alerts.\n\n" .
            "**8. 📊 Dashboard**\n" .
            "Your personal hub to manage everything in one place.\n\n" .
            "Want to know more about any feature? Just ask!";
    }

    /**
     * Get registration info
     */
    private function getRegistrationInfo(): string
    {
        return "📝 **How to Register**\n\n" .
            "Getting started is easy!\n\n" .
            "**Steps:**\n" .
            "1️⃣ Click 'Get Started' or 'Register' on the home page\n" .
            "2️⃣ Enter your username\n" .
            "3️⃣ Provide your email address\n" .
            "4️⃣ Add your phone number\n" .
            "5️⃣ Create a secure password\n" .
            "6️⃣ Submit and you're in!\n\n" .
            "**After registration you can:**\n" .
            "✅ Join savings challenges\n" .
            "✅ Purchase materials\n" .
            "✅ Create or join groups\n" .
            "✅ Track your savings\n\n" .
            "Ready to start building your future? 🏗️";
    }

    /**
     * Get login info
     */
    private function getLoginInfo(): string
    {
        return "🔐 **How to Login**\n\n" .
            "Access your account easily:\n\n" .
            "**Steps:**\n" .
            "1️⃣ Click 'Login' on the home page\n" .
            "2️⃣ Enter your email or username\n" .
            "3️⃣ Enter your password\n" .
            "4️⃣ Click 'Sign In'\n\n" .
            "**Forgot Password?**\n" .
            "Click 'Forgot Password' and enter your email to receive a reset link.\n\n" .
            "Having trouble? Contact our support team!";
    }

    /**
     * Get contact info
     */
    private function getContactInfo(): string
    {
        return "📞 **Contact & Support**\n\n" .
            "Need help? We're here for you!\n\n" .
            "**Ways to reach us:**\n" .
            "📧 Email: support@oweru.com\n" .
            "📱 Phone: +255 XXX XXX XXX\n" .
            "💬 Chat: Use this chatbot anytime!\n\n" .
            "**Support Hours:**\n" .
            "Monday - Friday: 8:00 AM - 6:00 PM\n" .
            "Saturday: 9:00 AM - 2:00 PM\n\n" .
            "You can also submit feedback through your dashboard!";
    }

    /**
     * Get dashboard info
     */
    private function getDashboardInfo(): string
    {
        return "📊 **Your Dashboard**\n\n" .
            "The dashboard is your control center!\n\n" .
            "**What you'll find:**\n" .
            "🏆 Active Challenges - Your current savings challenges\n" .
            "💰 Savings Overview - Total amount saved\n" .
            "📦 Materials - Browse and purchase items\n" .
            "👥 Groups - Your savings groups\n" .
            "💳 Payments - Payment history and pending dues\n" .
            "🔔 Notifications - Updates and reminders\n" .
            "📈 Progress - Track your journey\n\n" .
            "Login to access your personalized dashboard!";
    }

    /**
     * Get help menu
     */
    private function getHelpMenu(): string
    {
        return "🤖 **Mjengo Challenge Assistant - Help Menu**\n\n" .
            "I can help you with:\n\n" .
            "**💬 Conversations**\n" .
            "• Say 'hello' or 'hi' to greet me\n" .
            "• Ask 'how are you' for a friendly chat\n" .
            "• Say 'bye' when you're done\n\n" .
            "**🏆 Challenges**\n" .
            "• 'Show challenges' - List available challenges\n" .
            "• 'My challenges' - Your active challenges\n" .
            "• 'How do challenges work' - Learn about challenges\n\n" .
            "**🛠️ Materials**\n" .
            "• 'Show materials' - Browse products\n" .
            "• 'Material prices' - Check pricing\n" .
            "• 'What is Lipa Kidogo' - Installment info\n\n" .
            "**💰 Savings & Payments**\n" .
            "• 'My savings' - Check your savings\n" .
            "• 'My payments' - Payment status\n" .
            "• 'Payment history' - Past transactions\n\n" .
            "**👥 Groups**\n" .
            "• 'Show groups' - Available groups\n" .
            "• 'My groups' - Your memberships\n\n" .
            "**📖 General**\n" .
            "• 'Features' - All platform features\n" .
            "• 'About system' - Learn about Mjengo\n" .
            "• 'How to register' - Sign up help\n\n" .
            "Just type your question naturally! 😊";
    }

    /**
     * Get system overview
     */
    private function getSystemOverview(): string
    {
        return "🏗️ **Welcome to Mjengo Challenge (Oweru)!**\n\n" .
            "Mjengo Challenge is a savings and building materials platform designed to help Tanzanians build their dreams!\n\n" .
            "**🎯 Our Mission:**\n" .
            "Empower individuals to save money through disciplined daily challenges and access affordable building materials.\n\n" .
            "**✨ What We Offer:**\n\n" .
            "🏆 **Daily Savings Challenges**\n" .
            "Join challenges with daily saving targets. Build your fund consistently!\n\n" .
            "🛠️ **Quality Building Materials**\n" .
            "Access construction materials, tools, and equipment at competitive prices.\n\n" .
            "💳 **Lipa Kidogo (Pay Little by Little)**\n" .
            "Purchase expensive items through easy installment payments.\n\n" .
            "🛒 **Direct Purchase**\n" .
            "Buy materials immediately when you have the funds.\n\n" .
            "👥 **Savings Groups**\n" .
            "Create or join community savings groups to achieve bigger goals together.\n\n" .
            "📊 **Progress Tracking**\n" .
            "Monitor your savings journey with detailed dashboards.\n\n" .
            "**🚀 Get Started:**\n" .
            "Register, join a challenge, and start building your future today!\n\n" .
            "Ask me anything about these features! 💬";
    }

    /**
     * Get default response
     */
    private function getDefaultResponse(): string
    {
        $responses = [
            "🤔 I'm not sure I understood that. Let me help you!\n\n" .
            "You can ask me about:\n" .
            "• Challenges - 'Show challenges'\n" .
            "• Materials - 'Show materials'\n" .
            "• Savings - 'My savings'\n" .
            "• Payments - 'My payments'\n\n" .
            "Or type 'help' for the full menu!",

            "🤖 Hmm, I didn't quite catch that. Try asking about:\n\n" .
            "🏆 Challenges\n" .
            "🛠️ Materials\n" .
            "💳 Lipa Kidogo\n" .
            "💰 Savings\n" .
            "👥 Groups\n\n" .
            "Type 'help' for more options!",

            "👋 I'm your Mjengo Challenge assistant! I can help with:\n\n" .
            "• Savings challenges\n" .
            "• Building materials\n" .
            "• Payment plans\n" .
            "• Group savings\n\n" .
            "What would you like to know? (Type 'help' for all options)",
        ];
        return $responses[array_rand($responses)];
    }

    /**
     * Check if message contains any of the keywords
     */
    private function messageContainsAny(string $message, array $keywords): bool
    {
        foreach ($keywords as $keyword) {
            if (Str::contains($message, $keyword)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if message contains keywords
     */
    private function containsKeywords(string $message, array $keywords): bool
    {
        return $this->messageContainsAny($message, $keywords);
    }

    /**
     * Get challenges overview
     */
    private function getChallengesExplanation(): string
    {
        return "🏆 **How Challenges Work:**\n\n" .
            "1. **Join** - Pick a challenge that interests you\n" .
            "2. **Commit** - Save the daily amount set by the challenge\n" .
            "3. **Progress** - Your savings accumulate as you participate\n" .
            "4. **Complete** - Reach the end date and achieve your savings goal!\n\n" .
            "**Benefits:**\n" .
            "✅ Disciplined saving\n" .
            "✅ Community support\n" .
            "✅ Access to materials\n" .
            "✅ Flexible purchase options\n\n" .
            "Ready to join? Type 'show challenges' to see what's available!";
    }

    /**
     * Get payment instructions
     */
    private function getPaymentInstructions(): string
    {
        return "💸 **How to Make Payments:**\n\n" .
            "1. Go to your Dashboard\n" .
            "2. Select the challenge you want to pay for\n" .
            "3. Click 'Make Payment'\n" .
            "4. Enter the amount (usually the daily amount)\n" .
            "5. Choose your payment method\n" .
            "6. Confirm and complete\n\n" .
            "**Payment Methods Available:**\n" .
            "📱 Mobile Money (M-Pesa, Airtel Money, etc.)\n" .
            "🏦 Bank Transfer\n" .
            "💳 Card Payment\n\n" .
            "Need help? Contact support!";
    }

    /**
     * Get payment history
     */
    private function getUserPaymentHistory(): string
    {
        $payments = Payment::whereHas('participant', function ($q) {
            $q->where('user_id', $this->user->id);
        })->orderBy('created_at', 'desc')->take(10)->get();

        if ($payments->isEmpty()) {
            return "No payment history found.";
        }

        $response = "💳 Your Recent Payments (Last 10):\n\n";
        foreach ($payments as $payment) {
            $response .= "✓ " . $payment->participant->challenge->name . "\n";
            $response .= "   Amount: TZS " . number_format($payment->amount, 2) . "\n";
            $response .= "   Status: " . ucfirst($payment->status) . "\n";
            $response .= "   Date: " . $payment->created_at->format('M d, Y H:i') . "\n\n";
        }

        return $response;
    }

    /**
     * Get material pricing info
     */
    private function getMaterialPricing(): string
    {
        $materials = Material::where('status', 'active')->orderBy('price', 'desc')->take(5)->get();

        $response = "💰 **Material Pricing Guide:**\n\n";
        $response .= "Most Expensive:\n";
        foreach ($materials as $material) {
            $response .= "• " . $material->name . ": TZS " . number_format($material->price, 2) . "\n";
        }

        $response .= "\nUse **Lipa Kidogo** for expensive materials and pay in installments!";

        return $response;
    }

    /**
     * Get user's lipa kidogo plans
     */
    private function getUserLipaKidogoPlans(): string
    {
        $plans = $this->user->lipaKidogoPlans()->where('status', 'active')->get();

        if ($plans->isEmpty()) {
            return "No active Lipa Kidogo plans. Want to start one? Browse materials and choose 'Lipa Kidogo' option!";
        }

        $response = "📦 Your Active Lipa Kidogo Plans:\n\n";
        foreach ($plans as $plan) {
            $paid = $plan->getTotalPaid();
            $remaining = $plan->total_amount - $paid;
            $progress = $plan->total_amount > 0 ? round(($paid / $plan->total_amount) * 100) : 0;

            $response .= "🛠️ " . $plan->material->name . "\n";
            $response .= "   Total: TZS " . number_format($plan->total_amount, 2) . "\n";
            $response .= "   Paid: TZS " . number_format($paid, 2) . "\n";
            $response .= "   Remaining: TZS " . number_format($remaining, 2) . "\n";
            $response .= "   Progress: " . $progress . "%\n\n";
        }

        return $response;
    }

    /**
     * Get groups overview
     */
    private function getGroupsOverview(): string
    {
        return "👥 **About Groups:**\n\n" .
            "Groups are community-based savings circles where members:\n" .
            "✅ Save together towards a common goal\n" .
            "✅ Support each other\n" .
            "✅ Access group materials\n" .
            "✅ Build social connections\n\n" .
            "**Types of Groups:**\n" .
            "• Challenge Groups - Save for specific challenges\n" .
            "• General Groups - Community-based saving circles\n\n" .
            "Type 'show groups' to see available groups or create your own!";
    }

    /**
     * Get user groups
     */
    private function getUserGroups(): string
    {
        $groups = $this->user->groupMemberships()->where('status', 'active')->get();

        if ($groups->isEmpty()) {
            return "You haven't joined any groups yet. 👥\n\nType 'show groups' to find groups to join!";
        }

        $response = "👥 Your Groups:\n\n";
        foreach ($groups as $membership) {
            $group = $membership->group;
            $response .= "🏘️ " . $group->name . "\n";
            $response .= "   Leader: " . $group->leader->username . "\n";
            $response .= "   Members: " . $group->getMemberCount() . "\n";
            $response .= "   Status: " . ucfirst($membership->status) . "\n\n";
        }

        return $response;
    }

    /**
     * Get material overview
     */
    private function getMaterialOverview(): string
    {
        return "🛠️ **About Materials:**\n\n" .
            "We offer a wide range of building materials:\n" .
            "• Hand Tools\n" .
            "• Power Tools\n" .
            "• Building Supplies\n" .
            "• Equipment\n\n" .
            "**Purchase Options:**\n" .
            "💰 **Direct Purchase** - Buy immediately\n" .
            "💳 **Lipa Kidogo** - Pay in installments\n\n" .
            "Type 'show materials' to browse our catalog!";
    }

    /**
     * Get payment overview
     */
    private function getPaymentOverview(): string
    {
        return "💸 **Payment Information:**\n\n" .
            "We accept multiple payment methods for your convenience:\n" .
            "📱 Mobile Money\n" .
            "🏦 Bank Transfer\n" .
            "💳 Card Payment\n\n" .
            "**Payment Schedule:**\n" .
            "Payments are due based on your challenge schedule.\n" .
            "You can make partial or full payments anytime.\n\n" .
            "Type 'show my status' to check pending payments!";
    }
}
