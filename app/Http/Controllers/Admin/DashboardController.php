<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\JobRequest;
use App\Models\Quote;
use App\Models\Rating;
use App\Models\TaskerReport;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_taskers' => User::where('role', 'tasker')->where('verification_status', 'verified')->count(),
            'pending_taskers' => User::where('role', 'tasker')->where('verification_status', 'pending')->count(),
            'active_jobs' => JobRequest::where('status', 'open')->count(),
            'pending_reports' => TaskerReport::whereNull('admin_action')->count(),
        ];

        $pendingTaskers = User::where('role', 'tasker')
            ->where('verification_status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingTaskers'));
    }
}
