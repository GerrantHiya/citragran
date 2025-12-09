<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportComment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $resident = auth()->user()->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard');
        }

        $query = $resident->reports();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('resident.reports.index', compact('reports'));
    }

    public function create()
    {
        $resident = auth()->user()->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard');
        }

        return view('resident.reports.create');
    }

    public function store(Request $request)
    {
        $resident = auth()->user()->resident;

        if (!$resident) {
            return redirect()->route('resident.dashboard');
        }

        $request->validate([
            'type' => 'required|in:billing,environment,dispute,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        Report::create([
            'resident_id' => $resident->id,
            'ticket_number' => Report::generateTicketNumber(),
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'priority' => $request->priority,
        ]);

        return redirect()->route('resident.reports.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    public function show(Report $report)
    {
        $resident = auth()->user()->resident;

        if (!$resident || $report->resident_id !== $resident->id) {
            abort(403);
        }

        $report->load(['publicComments.user']);

        return view('resident.reports.show', compact('report'));
    }

    public function addComment(Request $request, Report $report)
    {
        $resident = auth()->user()->resident;

        if (!$resident || $report->resident_id !== $resident->id) {
            abort(403);
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        ReportComment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'is_internal' => false,
        ]);

        return redirect()->route('resident.reports.show', $report)
            ->with('success', 'Komentar berhasil ditambahkan!');
    }
}
