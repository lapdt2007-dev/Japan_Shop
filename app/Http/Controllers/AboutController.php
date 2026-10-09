<?php

namespace App\Http\Controllers;

class AboutController extends Controller
{
    // Trang Giới thiệu
    public function index()
    {
        return view('client.about.index');
    }
}