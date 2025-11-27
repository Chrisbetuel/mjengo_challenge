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

        // Check message intent and category
        if ($this->isAboutChallenges($message)) {
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

        if ($this->containsKeywords($message, ['about', 'system', 'explain', 'what is'])) {
            return $this->getSystemOverview();
        }

        return $this->getDefaultResponse();
    }

    /**
     * Get help menu
     */
    private function getHelpMenu(): string
    {
        return "🤖 **How can I help you?**\n\n" .
            "I can answer questions about:\n\n" .
            "💰 **Challenges** - Ask about active challenges, join challenges, or your progress\n" .
            "🛠️ **Materials** - Browse available tools and materials\n" .
            "💳 **Lipa Kidogo** - Flexible payment plans for materials\n" .
            "👥 **Groups** - Find or create savings groups\n" .
            "💸 **Payments** - Check payment status and history\n" .
            "💎 **Savings** - View your total savings and progress\n" .
            "⚠️ **Penalties** - Check for any penalties\n" .
            "👤 **Account** - View your account details\n\n" .
            "Try asking: 'Show me all challenges' or 'How much have I saved?'";
    }

    /**
     * Get system overview
     */
    private function getSystemOverview(): string
    {
        return "🏗️ **Welcome to Mjengo Challenge!**\n\n" .
            "Our mission is to help you build savings and access affordable building materials through group challenges.\n\n" .
            "**Key Features:**\n\n" .
            "🏆 **Challenges** - Join daily savings challenges with others\n" .
            "🛠️ **Materials** - Access quality building materials at competitive prices\n" .
            "💳 **Flexible Payments** - Our Lipa Kidogo plan lets you pay installments\n" .
            "👥 **Groups** - Save together with others in group challenges\n" .
            "💰 **Rewards** - Earn as you participate and achieve goals\n\n" .
            "Get started by joining a challenge today!";
    }

    /**
     * Get default response
     */
    private function getDefaultResponse(): string
    {
        return "👋 I'm here to help! I can answer questions about challenges, materials, payments, savings, and more.\n\n" .
            "Type 'help' to see what I can do, or ask me anything about the platform!";
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
