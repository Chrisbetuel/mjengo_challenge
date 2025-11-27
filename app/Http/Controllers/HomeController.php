<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
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

        // Pass testimonials to the view
        return view('home', compact('testimonials'));
    }
}