<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index', [
            'totalBooks'      => Book::count(),
            'totalCategories' => Category::count(),
        ]);
    }
}