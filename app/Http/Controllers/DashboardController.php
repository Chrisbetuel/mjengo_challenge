<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\Payment;
use App\Models\Participant;
use App\Models\LipaKidogo;
use App\Models\Testimonial;
use App\Models\Feedback;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Check if user is admin and redirect to admin dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $activeChallenges = $user->participants()
            ->with('challenge')
            ->where('participants.status', 'active')
            ->get();

        $recentPayments = Payment::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->latest()->take(5)->get();

        $totalSavings = Payment::whereHas('participant', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->where('payments.status', 'paid')->sum('amount');

        $lipaKidogoPlans = LipaKidogo::where('user_id', $user->id)
            ->with('material')
            ->latest()
            ->take(5)
            ->get();

        // Get available challenges (not joined by user)
        $availableChallenges = Challenge::where('status', 'active')
            ->whereDoesntHave('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id)->where('status', 'active');
            })
            ->latest()
            ->take(6)
            ->get();

        // Get materials for active challenges
        $challengeMaterials = collect();
        foreach ($activeChallenges as $participant) {
            $materials = Material::where('challenge_id', $participant->challenge_id)
                ->where('status', 'active')
                ->get();
            $challengeMaterials = $challengeMaterials->merge($materials);
        }

        // Get approved and featured testimonials from database
        $testimonials = Testimonial::with('user')
            ->where('is_approved', true)
            ->where('is_featured', true)
            ->latest()
            ->take(10)
            ->get();

        // If no testimonials in database, create sample data
        if ($testimonials->isEmpty()) {
            $testimonials = collect([
                (object)[
                    'id' => 1,
                    'content' => 'Oweru has transformed how I save for my construction projects. The group savings feature is amazing!',
                    'rating' => 5,
                    'user' => (object)[
                        'username' => 'John D.',
                        'email' => 'john@example.com'
                    ]
                ],
                (object)[
                    'id' => 2,
                    'content' => 'The daily challenges keep me motivated and the material purchases are so convenient.',
                    'rating' => 5,
                    'user' => (object)[
                        'username' => 'Sarah M.',
                        'email' => 'sarah@example.com'
                    ]
                ],
                (object)[
                    'id' => 3,
                    'content' => 'As a first-time builder, Oweru made the process so much easier. Highly recommended!',
                    'rating' => 4,
                    'user' => (object)[
                        'username' => 'Mike T.',
                        'email' => 'mike@example.com'
                    ]
                ]
            ]);
        }

        return view('dashboard', compact(
            'activeChallenges',
            'recentPayments',
            'totalSavings',
            'lipaKidogoPlans',
            'availableChallenges',
            'challengeMaterials',
            'testimonials'
        ));
    }

    public function storeFeedback(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:general,challenge,material,payment,technical'
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'type' => $request->type,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback! We will review it shortly.');
    }
}
