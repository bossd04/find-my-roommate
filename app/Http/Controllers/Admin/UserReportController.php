<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserReport;
use App\Models\User;
use Illuminate\Http\Request;

class UserReportController extends Controller
{
    /**
     * Display a listing of user reports
     */
    public function index(Request $request)
    {
        $query = UserReport::with(['reporter', 'reported', 'resolver']);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'reviewing', 'resolved', 'dismissed'])) {
            $query->where('status', $request->status);
        }

        // Filter by report type
        if ($request->has('type') && $request->type) {
            $query->where('report_type', $request->type);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('reporter', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('reported', function($sq) use ($search) {
                    $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('reason', 'like', "%{$search}%");
            });
        }

        $reports = $query->latest()->paginate(15);

        // Statistics
        $stats = [
            'total' => UserReport::count(),
            'pending' => UserReport::where('status', 'pending')->count(),
            'reviewing' => UserReport::where('status', 'reviewing')->count(),
            'resolved' => UserReport::where('status', 'resolved')->count(),
            'dismissed' => UserReport::where('status', 'dismissed')->count(),
        ];

        // Report types for filter
        $reportTypes = [
            'inappropriate_behavior' => 'Inappropriate Behavior',
            'harassment' => 'Harassment',
            'spam' => 'Spam',
            'fake_profile' => 'Fake Profile',
            'scam' => 'Scam/Fraud',
            'other' => 'Other',
        ];

        return view('admin.user-reports.index', compact('reports', 'stats', 'reportTypes'));
    }

    /**
     * Show a specific report
     */
    public function show(UserReport $report)
    {
        $report->load(['reporter', 'reported', 'resolver']);

        // Get reported user's other reports
        $otherReports = UserReport::where('reported_id', $report->reported_id)
            ->where('id', '!=', $report->id)
            ->with(['reporter'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.user-reports.show', compact('report', 'otherReports'));
    }

    /**
     * Mark report as under review
     */
    public function markReviewing(UserReport $report)
    {
        $report->markAsReviewing();

        return back()->with('success', 'Report marked as under review');
    }

    /**
     * Resolve a report
     */
    public function resolve(Request $request, UserReport $report)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->markAsResolved(auth()->id(), $validated['admin_notes'] ?? null);

        // Confirmation of violation - penalize compatibility
        if ($report->reporter && $report->reported) {
            $compatibilityService = app(\App\Services\CompatibilityService::class);
            $compatibilityService->trackViolation($report->reporter, $report->reported);
        }

        return back()->with('success', 'Report resolved successfully. User compatibility scores have been adjusted.');
    }

    /**
     * Dismiss a report
     */
    public function dismiss(Request $request, UserReport $report)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $report->markAsDismissed(auth()->id(), $validated['admin_notes'] ?? null);

        return back()->with('success', 'Report dismissed');
    }

    /**
     * Get reported users list with counts
     */
    public function reportedUsers()
    {
        $reportedUsers = UserReport::select('reported_id')
            ->selectRaw('COUNT(*) as report_count')
            ->selectRaw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_count')
            ->with('reported')
            ->groupBy('reported_id')
            ->having('report_count', '>', 0)
            ->orderByDesc('report_count')
            ->paginate(20);

        return view('admin.user-reports.users', compact('reportedUsers'));
    }
}
