<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportingController extends Controller
{
    public function donations(Request $request)
    {
        $query = Donation::with(['donor', 'campaign'])
            ->orderByDesc('donated_at');

        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('donated_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('donated_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('donation_type')) {
            $query->where('donation_type', $request->donation_type);
        }

        $totalsQuery = clone $query;
        $totalAmount   = $totalsQuery->sum('amount_base');
        $totalCount    = $totalsQuery->count();
        $completedCount = (clone $query)->where('payment_status', 'completed')->count();
        $pendingCount   = (clone $query)->where('payment_status', 'pending')->count();

        $donations = $query->paginate(20)->withQueryString();
        $campaigns = Campaign::orderBy('name')->get();
        $donors    = Donor::orderBy('first_name')->get();

        return view('reports.donations', compact(
            'donations', 'campaigns', 'donors',
            'totalAmount', 'totalCount', 'completedCount', 'pendingCount'
        ));
    }

    public function donors(Request $request)
    {
        $totalDonors    = Donor::count();
        $activeDonors   = Donor::where('lifecycle_stage', 'active')->count();
        $inactiveDonors = Donor::where('lifecycle_stage', 'inactive')->count();
        $newDonors      = Donor::where('lifecycle_stage', 'new')->count();
        $avgEngagement  = round(Donor::avg('engagement_score') ?? 0, 1);

        // Retention: donors who donated in both prior 12 months AND last 12 months
        $now = Carbon::now();
        $priorStart = $now->copy()->subMonths(24);
        $priorEnd   = $now->copy()->subMonths(12);
        $recentStart = $now->copy()->subMonths(12);

        $priorDonorIds = Donation::whereBetween('donated_at', [$priorStart, $priorEnd])
            ->select('donor_id')
            ->groupBy('donor_id')
            ->pluck('donor_id');

        $retainedCount = 0;
        if ($priorDonorIds->isNotEmpty()) {
            $retainedCount = Donation::where('donated_at', '>=', $recentStart)
                ->whereIn('donor_id', $priorDonorIds)
                ->select('donor_id')
                ->groupBy('donor_id')
                ->get()
                ->count();
        }

        $priorCount    = $priorDonorIds->count();
        $retentionRate = $priorCount > 0 ? round(($retainedCount / $priorCount) * 100, 1) : 0;
        $churnRate     = round(100 - $retentionRate, 1);

        $topDonors = Donor::withSum('donations as total_given', 'amount_base')
            ->withCount('donations')
            ->with(['donations' => fn ($q) => $q->orderByDesc('donated_at')->limit(1)])
            ->orderByDesc('total_given')
            ->limit(20)
            ->get();

        return view('reports.donors', compact(
            'totalDonors', 'activeDonors', 'inactiveDonors', 'newDonors',
            'avgEngagement', 'retentionRate', 'churnRate', 'topDonors'
        ));
    }

    public function exportDonationsCsv(Request $request): StreamedResponse
    {
        $query = Donation::with(['donor', 'campaign'])
            ->orderByDesc('donated_at');

        if ($request->filled('donor_id')) {
            $query->where('donor_id', $request->donor_id);
        }
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('donated_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('donated_at', '<=', $request->date_to);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('donation_type')) {
            $query->where('donation_type', $request->donation_type);
        }

        $filename = 'donations_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'Donor', 'Campaign', 'Type', 'Source',
                'Currency', 'Amount', 'Amount (Base)', 'Exchange Rate',
                'Payment Status', 'Gift Aid', 'Transaction Ref', 'Donated At',
            ]);

            $query->chunk(500, function ($donations) use ($handle) {
                foreach ($donations as $d) {
                    fputcsv($handle, [
                        $d->id,
                        $d->donor ? $d->donor->first_name . ' ' . $d->donor->last_name : '',
                        $d->campaign ? $d->campaign->name : '',
                        $d->donation_type,
                        $d->source,
                        $d->currency,
                        number_format($d->amount_original, 2),
                        number_format($d->amount_base, 2),
                        $d->exchange_rate,
                        $d->payment_status,
                        $d->gift_aid_eligible ? 'Yes' : 'No',
                        $d->transaction_reference,
                        $d->donated_at ? $d->donated_at->format('Y-m-d H:i:s') : '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportDonorsCsv(Request $request): StreamedResponse
    {
        $filename = 'donors_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID', 'First Name', 'Last Name', 'Organization', 'Type', 'Category',
                'Lifecycle Stage', 'Email', 'Phone', 'Engagement Score',
                'Is Active', 'Last Engaged At',
            ]);

            Donor::chunk(500, function ($donors) use ($handle) {
                foreach ($donors as $d) {
                    fputcsv($handle, [
                        $d->id,
                        $d->first_name,
                        $d->last_name,
                        $d->organization_name,
                        $d->donor_type,
                        $d->category,
                        $d->lifecycle_stage,
                        $d->email,
                        $d->phone,
                        $d->engagement_score,
                        $d->is_active ? 'Yes' : 'No',
                        $d->last_engaged_at ? $d->last_engaged_at->format('Y-m-d H:i:s') : '',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
