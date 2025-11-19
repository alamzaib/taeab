<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyReviewController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        // Check if seeker already reviewed this company
        $existingReview = CompanyReview::where('company_id', $company->id)
            ->where('seeker_id', Auth::id())
            ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this company.');
        }

        CompanyReview::create([
            'company_id' => $company->id,
            'seeker_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Your review has been submitted successfully.');
    }

    public function reply(Request $request, CompanyReview $review)
    {
        $request->validate([
            'company_reply' => 'required|string|min:10|max:1000',
        ]);

        // Check if the company owns this review
        if ($review->company_id != Auth::guard('company')->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $review->update([
            'company_reply' => $request->company_reply,
            'replied_at' => now(),
        ]);

        return back()->with('success', 'Your reply has been posted successfully.');
    }

    public function edit(CompanyReview $review)
    {
        // Check if the seeker owns this review
        if ($review->seeker_id != Auth::guard('seeker')->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        return response()->json([
            'id' => $review->id,
            'rating' => $review->rating,
            'review' => $review->review,
        ]);
    }

    public function update(Request $request, CompanyReview $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:10|max:1000',
        ]);

        // Check if the seeker owns this review
        if ($review->seeker_id != Auth::guard('seeker')->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
            'edited_at' => now(),
        ]);

        return back()->with('success', 'Your review has been updated successfully.');
    }

    public function getAllReviews(Company $company)
    {
        $reviews = $company->reviews()
            ->with('seeker')
            ->latest()
            ->get();

        return response()->json($reviews);
    }
}
