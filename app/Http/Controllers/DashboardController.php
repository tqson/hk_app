<?php

namespace App\Http\Controllers;

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
        $totalRevenue = SalesInvoice::whereBetween('created_at', [$startDateCarbon->startOfDay(), $endDateCarbon->endOfDay()])
            ->sum('total_amount');

        // Get today's revenue
        $todayRevenue = SalesInvoice::whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // Get current week's revenue (Monday to Sunday)
        $currentWeekRevenue = SalesInvoice::whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_amount');

        // Get current month's revenue
        $currentMonthRevenue = SalesInvoice::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('total_amount');

        // Get daily revenue data for chart within the selected date range
        $dailyRevenue = SalesInvoice::whereBetween('created_at', [$startDateCarbon->copy()->startOfDay(), $endDateCarbon->copy()->endOfDay()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(function ($item) {
                return round($item->total, 2);
            });

        // Generate all dates in the range to ensure continuous data points
        $dateRange = collect(CarbonPeriod::create($startDateCarbon, $endDateCarbon))
            ->map(function ($date) {
                return $date->format('Y-m-d');
            });

        // Fill in missing dates with zero values
        $chartData = $dateRange->mapWithKeys(function ($date) use ($dailyRevenue) {
            // Format date for display in chart
            $displayDate = Carbon::parse($date)->format('d/m/Y');
            return [$displayDate => $dailyRevenue[$date] ?? 0];
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

            $weeklyTotal = SalesInvoice::whereBetween('created_at', [
                $weekStartDate->startOfDay(),
                $weekEndDate->endOfDay()
            ])->sum('total_amount');

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

            $monthlyTotal = SalesInvoice::whereBetween('created_at', [
                $monthStartDate->startOfDay(),
                $monthEndDate->endOfDay()
            ])->sum('total_amount');

            // Format month for display
            $monthLabel = $currentMonth->format('m/Y');
            $monthlyRevenue[$monthLabel] = round($monthlyTotal, 2);

            $currentMonth->addMonth();
        }

        // Convert data to collections for use in charts
        $weeklyChartData = collect($weeklyRevenue);
        $monthlyChartData = collect($monthlyRevenue);

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
            'year'
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
            var_dump($startDate);
            var_dump($endDate);
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
}
