<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::with('user')
            ->approved()
            ->featured()
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

        Testimonial::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'rating' => $request->rating,
            'is_approved' => false // Requires admin approval
        ]);

        return redirect()->back()->with('success', 'Thank you for your feedback! Your testimonial will be reviewed before publishing.');
    }

    // Admin methods
    public function adminIndex()
    {
        $testimonials = Testimonial::with('user')->latest()->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function approve(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Testimonial approved successfully.');
    }

    public function reject(Testimonial $testimonial)
    {
        $testimonial->update(['is_approved' => false]);
        return redirect()->back()->with('success', 'Testimonial rejected.');
    }

    public function feature(Testimonial $testimonial)
    {
        $testimonial->update(['is_featured' => true]);
        return redirect()->back()->with('success', 'Testimonial featured successfully.');
    }

    public function unfeature(Testimonial $testimonial)
    {
        $testimonial->update(['is_featured' => false]);
        return redirect()->back()->with('success', 'Testimonial unfeatured.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->back()->with('success', 'Testimonial deleted successfully.');
    }
}