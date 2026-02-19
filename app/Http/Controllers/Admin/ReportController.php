<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Listing;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    protected $defaultDays = 30;

    public function __construct()
    {
        View::share('currentNav', 'reports');
    }

    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $type = $request->input('type', 'users');
        $startDate = $request->input('start_date', now()->subDays($this->defaultDays)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        $method = 'generate' . ucfirst($type) . 'Report';
        
        if (method_exists($this, $method)) {
            $reportData = $this->$method($startDate, $endDate);
            $reportData['start_date'] = $startDate;
            $reportData['end_date'] = $endDate;
            $reportData['report_type'] = $type;
            
            return view('admin.reports.index', [
                'reportData' => $reportData,
                'reportType' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
        }
        
        return redirect()->route('admin.reports.index')
            ->with('error', 'Invalid report type');
    }
    
    protected function generateUsersReport($startDate, $endDate)
    {
        $users = User::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('DATE(created_at) as date')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $reportData = [];
        $runningTotal = 0;
        
        // Fill in all dates in range
        $period = new \DatePeriod(
            Carbon::parse($startDate),
            new \DateInterval('P1D'),
            Carbon::parse($endDate)
        );
        
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $dayData = $users->firstWhere('date', $dateString);
            $count = $dayData ? $dayData->count : 0;
            $runningTotal += $count;
            
            $reportData['users'][$dateString] = [
                'count' => $count,
                'running_total' => $runningTotal
            ];
        }
        
        $reportData['total_users'] = User::count();
        $reportData['new_users'] = User::whereBetween('created_at', [$startDate, $endDate])->count();
        
        return $reportData;
    }
    
    protected function generateListingsReport($startDate, $endDate)
    {
        $listings = Listing::select(
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as active_count'),
                DB::raw('DATE(created_at) as date')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        $reportData = [];
        $runningTotal = 0;
        
        // Fill in all dates in range
        $period = new \DatePeriod(
            Carbon::parse($startDate),
            new \DateInterval('P1D'),
            Carbon::parse($endDate)
        );
        
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $dayData = $listings->firstWhere('date', $dateString);
            $count = $dayData ? $dayData->count : 0;
            $activeCount = $dayData ? $dayData->active_count : 0;
            $runningTotal += $count;
            
            $reportData['listings'][$dateString] = [
                'count' => $count,
                'active_count' => $activeCount,
                'running_total' => $runningTotal
            ];
        }
        
        $reportData['total_listings'] = Listing::count();
        $reportData['active_listings'] = Listing::where('status', 'active')->count();
        $reportData['new_listings'] = Listing::whereBetween('created_at', [$startDate, $endDate])->count();
        
        return $reportData;
    }
    
    protected function generateTransactionsReport($startDate, $endDate)
    {
        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $reportData = [];
        $totalAmount = $transactions->sum('amount');
        $currency = config('app.currency', 'PHP');
        
        $reportData['transactions'] = $transactions;
        $reportData['total_amount'] = $totalAmount;
        $reportData['currency'] = $currency;
        $reportData['total_transactions'] = $transactions->count();
        $reportData['completed_transactions'] = $transactions->where('status', 'completed')->count();
        $reportData['pending_transactions'] = $transactions->where('status', 'pending')->count();
        $reportData['failed_transactions'] = $transactions->where('status', 'failed')->count();
        
        return $reportData;
    }
    
    public function export($type, $format = 'pdf')
    {
        $method = 'generate' . ucfirst($type) . 'Report';
        
        if (!method_exists($this, $method)) {
            return back()->with('error', 'Invalid export type');
        }
        
        $startDate = request()->input('start_date', now()->subDays($this->defaultDays)->format('Y-m-d'));
        $endDate = request()->input('end_date', now()->format('Y-m-d'));
        
        $reportData = $this->$method($startDate, $endDate);
        $reportData['start_date'] = $startDate;
        $reportData['end_date'] = $endDate;
        
        // For now, just return a view - in a real app, you'd use a package like maatwebsite/excel
        $view = 'admin.reports.exports.' . $format . '.' . $type;
        
        if (view()->exists($view)) {
            return view($view, [
                'reportData' => $reportData,
                'reportType' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
        }
        
        return back()->with('error', 'Export format not supported');
    }
    
    /**
     * Get report data based on type
     */
    protected function getReportData($type, $startDate, $endDate)
    {
        switch ($type) {
            case 'users':
                return $this->generateUsersReport($startDate, $endDate);
                
            case 'listings':
                return $this->generateListingsReport($startDate, $endDate);
                
            case 'transactions':
                return $this->generateTransactionsReport($startDate, $endDate);
        }
        
        return [];
    }
    
    /**
     * Get view and filename based on report type
     */
    protected function getViewAndFilename($type)
    {
        $views = [
            'users' => 'admin.reports.exports.users',
            'listings' => 'admin.reports.exports.listings',
            'transactions' => 'admin.reports.exports.transactions'
        ];
        
        return [
            $views[$type] ?? 'admin.reports.exports.default',
            $type . '_report_' . now()->format('Y-m-d')
        ];
    }
    
    /**
     * Generate PDF report
     */
    protected function generatePdf($view, $data, $startDate, $endDate, $filename)
    {
        $pdf = app('dompdf.wrapper');
        $pdf->loadView($view, [
            'data' => $data,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
        return $pdf->download("{$filename}.pdf");
    }
    
    /**
     * Generate CSV report
     */
    protected function generateCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Get the first item and convert it to an array to get headers
            $firstRow = $data->first();
            if ($firstRow) {
                $headers = array_keys($firstRow->toArray());
                fputcsv($file, $headers);
                
                // Add rows
                foreach ($data as $row) {
                    fputcsv($file, $row->toArray());
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
