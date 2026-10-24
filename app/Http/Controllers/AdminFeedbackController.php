<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'rating'  => 'nullable|integer|min:1|max:5',
            'keyword' => 'nullable|string|max:100',
        ]);

        $query = Feedback::with('user')->latest();

        if (!empty($filters['rating'])) {
            $query->where('rating', $filters['rating']);
        }

        if (!empty($filters['keyword'])) {
            $like = '%' . addcslashes(trim($filters['keyword']), '%_\\') . '%';

            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('email', 'like', $like)
                  ->orWhere('subject', 'like', $like)
                  ->orWhere('message', 'like', $like);
            });
        }

        $feedbacks     = $query->paginate(15)->withQueryString();
        $averageRating = round((float) Feedback::whereNotNull('rating')->avg('rating'), 1);

        return view('admin.feedback.index', compact('feedbacks', 'averageRating'));
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();

        return back()->with('success', 'Đã xóa phản hồi.');
    }
}