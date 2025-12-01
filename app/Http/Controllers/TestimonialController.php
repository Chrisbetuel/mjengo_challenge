<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Feedback::with('user')
            ->where('type', 'testimonial')
            ->where('status', 'resolved')
            ->latest()
            ->take(10)
            ->get();

        return view('home', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|min:10|max:500',
            'rating' => 'required|integer|between:1,5'
        ]);

        Feedback::create([
            'user_id' => Auth::id(),
            'subject' => 'Testimonial',
            'message' => $request->content,
            'type' => 'testimonial',
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback! Your testimonial will be reviewed before publishing.');
    }

    // Admin methods
    public function adminIndex()
    {
        $testimonials = Feedback::with('user')
            ->where('type', 'testimonial')
            ->latest()
            ->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function approve(Feedback $testimonial)
    {
        $testimonial->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Testimonial approved successfully.');
    }

    public function reject(Feedback $testimonial)
    {
        $testimonial->update(['status' => 'pending']);
        return redirect()->back()->with('success', 'Testimonial rejected.');
    }

    public function feature(Feedback $testimonial)
    {
        // For now, we'll use admin_response to mark as featured
        $testimonial->update(['admin_response' => 'featured']);
        return redirect()->back()->with('success', 'Testimonial featured successfully.');
    }

    public function unfeature(Feedback $testimonial)
    {
        $testimonial->update(['admin_response' => null]);
        return redirect()->back()->with('success', 'Testimonial unfeatured.');
    }

    public function destroy(Feedback $testimonial)
    {
        $testimonial->delete();
        return redirect()->back()->with('success', 'Testimonial deleted successfully.');
    }
}
