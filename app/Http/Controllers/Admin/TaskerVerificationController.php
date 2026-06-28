<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Notifications\TaskerVerificationNotification;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskerVerificationController extends Controller
{
    public function index(): View
    {
        $pendingTaskers = User::where('role', 'tasker')
            ->where('verification_status', 'pending')
            ->with('taskerProfile.trade', 'taskerProfile.specializations')
            ->paginate(15);

        $allTaskers = User::where('role', 'tasker')
            ->where('verification_status', 'verified')
            ->with('taskerProfile.trade')
            ->paginate(15);

        return view('admin.taskers.index', compact('pendingTaskers', 'allTaskers'));
    }

    public function show(User $tasker): View
    {
        $tasker->load('taskerProfile.trade', 'taskerProfile.specializations', 'taskerProfile.languages');
        return view('admin.taskers.show', compact('tasker'));
    }

    public function approve(User $tasker): RedirectResponse
    {
        $tasker->update(['verification_status' => 'verified']);
        $tasker->notify(new TaskerVerificationNotification('approved'));
        return redirect()->back()->with('success', 'Tasker approved');
    }

    public function reject(Request $request, User $tasker): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string']]);
        $tasker->update(['verification_status' => 'rejected']);
        $tasker->notify(new TaskerVerificationNotification('rejected', $request->reason));
        return redirect()->back()->with('success', 'Tasker rejected');
    }
}
