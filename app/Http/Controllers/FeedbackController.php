<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('client.feedback.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'subject' => 'nullable|string|max:100',
            'rating'  => 'nullable|integer|min:1|max:5',
            'message' => 'required|string|max:2000',
        ]);

        Feedback::create($data + ['user_id' => auth()->id()]);

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Cảm ơn bạn! Phản hồi của bạn đã được ghi nhận.');
    }
}
