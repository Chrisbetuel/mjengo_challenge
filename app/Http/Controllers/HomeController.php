<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        try {
            // Get approved and featured testimonials from feedback table
            $testimonials = Feedback::with('user')
                ->where('type', 'testimonial')
                ->where('status', 'resolved')
                ->where('admin_response', 'featured')
                ->latest()
                ->take(10)
                ->get();
        } catch (\Exception $e) {
            // If database is not available, use empty collection
            $testimonials = collect([]);
        }
=======
        // Get all resolved feedback from feedback table
        $feedbacks = Feedback::with('user')
            ->where('status', 'resolved')
            ->latest()
            ->take(10)
            ->get();
>>>>>>> 4e8a677 (chat system)

        // Pass feedbacks to the view
        return view('home', compact('feedbacks'));
    }
}