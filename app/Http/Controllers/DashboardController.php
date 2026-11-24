<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Member;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        return view('dashboard.index', [
            'totalCategories'   => Category::count(),
            'totalBooks'        => Book::count(),
            'totalStock'        => Book::sum('stock'),
            'totalMembers'      => Member::count(),
            'totalBorrowings'   => Borrowing::count(),
            'activeBorrowings'  => Borrowing::where('status', 'borrowed')->count(),
            'overdueBorrowings' => Borrowing::where('status', 'borrowed')
                ->whereDate('due_date', '<', $today)
                ->count(),
            'latestBorrowings'  => Borrowing::with('member')
                ->latest('id')
                ->take(5)
                ->get(),
        ]);
    }
}