<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class FeedbackController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('client.feedback.index', compact('categories'));
    }   

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:100',
            'rating' => 'nullable|integer|min:1|max:5',
            'message' => 'required|string|max:2000',
        ]);

        return redirect()
            ->route('feedback.index')
            ->with('success', 'Cảm ơn bạn! Phản hồi của bạn đã được ghi nhận.');
    }
}
