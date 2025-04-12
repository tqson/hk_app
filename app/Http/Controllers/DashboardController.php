<?php

namespace App\Http\Controllers;

use App\Models\ProductBatch;
use App\Models\ReturnInvoice;
use Illuminate\Http\Request;
use App\Models\SalesInvoice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Resolve filters based on priority
        $filters = $this->resolveFilterPriority($request);
        $startDate = $filters['start_date'];
        $endDate = $filters['end_date'];
        $month = $filters['month'];
        $year = $filters['year'];

        // Convert to Carbon instances for easier date manipulation
        $startDateCarbon = Carbon::parse($startDate);
        $endDateCarbon = Carbon::parse($endDate);

        // Calculate total revenue for the selected period
//        $totalRevenue = SalesInvoice::whereBetween('created_at', [$startDateCarbon->startOfDay(), $endDateCarbon->endOfDay()])
//            ->sum('total_amount');

        // For total revenue in the selected period
        $totalSales = SalesInvoice::whereBetween('created_at', [$startDateCarbon->startOfDay(), $endDateCarbon->endOfDay()])
            ->sum('total_amount');

        // Assuming there's a ReturnInvoice model with a total_amount field
        $totalReturns = ReturnInvoice::whereBetween('created_at', [$startDateCarbon->startOfDay(), $endDateCarbon->endOfDay()])
            ->sum('total_amount');

        $totalRevenue = $totalSales - $totalReturns;

        // Get today's revenue
        $todaySales = SalesInvoice::whereDate('created_at', Carbon::today())
            ->sum('total_amount');
        $todayReturns = ReturnInvoice::whereDate('created_at', Carbon::today())
            ->sum('total_amount');
        $todayRevenue = $todaySales - $todayReturns;

        // Get current week's revenue (Monday to Sunday)
        $currentWeekSales = SalesInvoice::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_amount');
        $currentWeekReturns = ReturnInvoice::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_amount');
        $currentWeekRevenue = $currentWeekSales - $currentWeekReturns;

        // Get current month's revenue
        $currentMonthSales = SalesInvoice::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('total_amount');
        $currentMonthReturns = ReturnInvoice::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('total_amount');
        $currentMonthRevenue = $currentMonthSales - $currentMonthReturns;

        // Get daily revenue data for chart within the selected date range
//        $dailyRevenue = SalesInvoice::whereBetween('created_at', [$startDateCarbon->copy()->startOfDay(), $endDateCarbon->copy()->endOfDay()])
//            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
//            ->groupBy('date')
//            ->orderBy('date')
//            ->get()
//            ->keyBy('date')
//            ->map(function ($item) {
//                return round($item->total, 2);
//            });
//
//        // Generate all dates in the range to ensure continuous data points
        $dateRange = collect(CarbonPeriod::create($startDateCarbon, $endDateCarbon))
            ->map(function ($date) {
                return $date->format('Y-m-d');
            });
//
//        // Fill in missing dates with zero values
//        $chartData = $dateRange->mapWithKeys(function ($date) use ($dailyRevenue) {
//            // Format date for display in chart
//            $displayDate = Carbon::parse($date)->format('d/m/Y');
//            return [$displayDate => $dailyRevenue[$date] ?? 0];
//        });
        $dailySales = SalesInvoice::whereBetween('created_at', [$startDateCarbon->copy()->startOfDay(), $endDateCarbon->copy()->endOfDay()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyReturns = ReturnInvoice::whereBetween('created_at', [$startDateCarbon->copy()->startOfDay(), $endDateCarbon->copy()->endOfDay()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $chartData = $dateRange->mapWithKeys(function ($date) use ($dailySales, $dailyReturns) {
            $sales = isset($dailySales[$date]) ? $dailySales[$date]->total : 0;
            $returns = isset($dailyReturns[$date]) ? $dailyReturns[$date]->total : 0;
            $net = $sales - $returns;
            // Format date for display in chart
            $displayDate = Carbon::parse($date)->format('d/m/Y');
            return [$displayDate => round($net, 2)];
        });

        // Calculate weekly revenue data based on the selected date range
        $weeklyRevenue = [];

        // Calculate first and last week in the selected date range
        $startWeek = $startDateCarbon->copy()->startOfWeek();
        $endWeek = $endDateCarbon->copy()->endOfWeek();

        $currentWeek = $startWeek->copy();
        $weekNumber = 1;

        while ($currentWeek->lte($endWeek)) {
            $weekEndDate = $currentWeek->copy()->endOfWeek();

            // Ensure week end date doesn't exceed the filter end date
            if ($weekEndDate->gt($endDateCarbon)) {
                $weekEndDate = $endDateCarbon->copy();
            }

            // Ensure week start date isn't before the filter start date
            $weekStartDate = $currentWeek->copy();
            if ($weekStartDate->lt($startDateCarbon)) {
                $weekStartDate = $startDateCarbon->copy();
            }

            $weeklySales = SalesInvoice::whereBetween('created_at', [
                $weekStartDate->startOfDay(),
                $weekEndDate->endOfDay()
            ])->sum('total_amount');

            $weeklyReturns = ReturnInvoice::whereBetween('created_at', [
                $weekStartDate->startOfDay(),
                $weekEndDate->endOfDay()
            ])->sum('total_amount');

            $weeklyTotal = $weeklySales - $weeklyReturns;

            // Format date for display
            $weekLabel = 'Week ' . $weekNumber . ' (' . $weekStartDate->format('d/m') . ' - ' . $weekEndDate->format('d/m') . ')';
            $weeklyRevenue[$weekLabel] = round($weeklyTotal, 2);

            $currentWeek->addWeek();
            $weekNumber++;
        }

        // Calculate monthly revenue data based on the selected date range
        $monthlyRevenue = [];

        // Calculate first and last month in the selected date range
        $startMonth = $startDateCarbon->copy()->startOfMonth();
        $endMonth = $endDateCarbon->copy()->startOfMonth();

        $currentMonth = $startMonth->copy();

        while ($currentMonth->lte($endMonth)) {
            $monthEndDate = $currentMonth->copy()->endOfMonth();

            // Ensure month end date doesn't exceed the filter end date
            if ($monthEndDate->gt($endDateCarbon)) {
                $monthEndDate = $endDateCarbon->copy();
            }

            // Ensure month start date isn't before the filter start date
            $monthStartDate = $currentMonth->copy();
            if ($monthStartDate->lt($startDateCarbon)) {
                $monthStartDate = $startDateCarbon->copy();
            }

            $monthlySales = SalesInvoice::whereBetween('created_at', [
                $monthStartDate->startOfDay(),
                $monthEndDate->endOfDay()
            ])->sum('total_amount');

            $monthlyReturns = ReturnInvoice::whereBetween('created_at', [
                $monthStartDate->startOfDay(),
                $monthEndDate->endOfDay()
            ])->sum('total_amount');

            $monthlyTotal = $monthlySales - $monthlyReturns;

            // Format month for display
            $monthLabel = $currentMonth->format('m/Y');
            $monthlyRevenue[$monthLabel] = round($monthlyTotal, 2);

            $currentMonth->addMonth();
        }

        // Convert data to collections for use in charts
        $weeklyChartData = collect($weeklyRevenue);
        $monthlyChartData = collect($monthlyRevenue);

        $today = now();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        // Mặc định lấy các sản phẩm hết hạn trong tháng này
        $expiringProducts = ProductBatch::with('product')
            ->where('status', 'active')
            ->where('quantity', '>', 0)
//            ->whereBetween('expiry_date', [$today, $endOfMonth])
            ->whereBetween('expiry_date', [$startOfMonth, $endOfMonth])
            ->orderBy('expiry_date')
            ->get();

        return view('pages.dashboard', compact(
            'totalRevenue',
            'todayRevenue',
            'currentWeekRevenue',
            'currentMonthRevenue',
            'chartData',
            'weeklyChartData',
            'monthlyChartData',
            'startDate',
            'endDate',
            'month',
            'year',
            'expiringProducts'
        ));
    }

    /**
     * Handle filter priority and resolve date ranges
     * Priority: date range > month+year > month > year
     */
    private function resolveFilterPriority(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $month = $request->input('month');
        $year = $request->input('year');

        // If both start and end dates are provided, use them and ignore month/year
        if ($startDate && $endDate) {
            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'month' => null,
                'year' => $year // Keep year for context
            ];
        }

        // If month and year are provided
        if ($month && $year) {
            $startDate = "{$year}-{$month}-01";
            $endDate = date('Y-m-t', strtotime($startDate));

            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'month' => $month,
                'year' => $year
            ];
        }

        // If only month is provided, use current year
        if ($month) {
            $currentYear = date('Y');
            $startDate = "{$currentYear}-{$month}-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'month' => $month,
                'year' => $currentYear
            ];
        }

        // If only year is provided
        if ($year) {
            $startDate = "{$year}-01-01";
            $endDate = "{$year}-12-31";
            return [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'month' => null,
                'year' => $year
            ];
        }

        // Default: current month
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'month' => null,
            'year' => null
        ];
    }

    public function expiryFilter(Request $request)
    {
        $filter = $request->input('filter', 'current');
        $today = now();

        $query = ProductBatch::with('product')
            ->where('status', 'active')
            ->where('quantity', '>', 0);

        switch ($filter) {
            case 'current':
                // Tháng này
                $startOfMonth = $today->copy()->startOfMonth();
                $endOfMonth = $today->copy()->endOfMonth();
//                $query->whereBetween('expiry_date', [$today, $endOfMonth]);
                $query->whereBetween('expiry_date', [$startOfMonth, $endOfMonth]);
                break;

            case 'three':
                // 3 tháng sau
                $threeMonthsLater = $today->copy()->addMonths(3);
                $query->whereBetween('expiry_date', [$today, $threeMonthsLater]);
                break;

            case 'six':
                // 6 tháng sau
                $sixMonthsLater = $today->copy()->addMonths(6);
                $query->whereBetween('expiry_date', [$today, $sixMonthsLater]);
                break;

            case 'expired':
                // Đã hết hạn
                $query->where('expiry_date', '<', $today);
                break;
        }

        $batches = $query->orderBy('expiry_date')->get();

        // Thêm số ngày còn lại vào mỗi batch
        $batches->each(function ($batch) use ($today) {
            $batch->days_left = max(0, $today->diffInDays($batch->expiry_date, false) + 1);
        });

        return response()->json(['data' => $batches]);
    }
}
