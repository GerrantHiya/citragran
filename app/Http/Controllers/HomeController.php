<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $announcements = Announcement::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('welcome', compact('announcements'));
    }
}
