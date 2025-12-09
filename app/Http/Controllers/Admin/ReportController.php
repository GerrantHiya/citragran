<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportComment;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['resident', 'assignedTo']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ticket_number', 'like', "%{$request->search}%")
                    ->orWhere('subject', 'like', "%{$request->search}%")
                    ->orWhereHas('resident', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->search}%")
                            ->orWhere('block_number', 'like', "%{$request->search}%");
                    });
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->priority) {
            $query->where('priority', $request->priority);
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['resident', 'assignedTo', 'comments.user']);
        $staff = User::whereIn('role', ['admin', 'staff'])->get();

        return view('admin.reports.show', compact('report', 'staff'));
    }

    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:received,analyzing,processing,rejected,completed',
            'admin_notes' => 'nullable|string',
        ]);

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'resolved_at' => in_array($request->status, ['completed', 'rejected']) ? now() : null,
        ]);

        // Add comment about status change
        ReportComment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => 'Status diubah menjadi: ' . Report::STATUSES[$request->status],
            'is_internal' => false,
        ]);

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Status laporan berhasil diperbarui!');
    }

    public function assignTo(Request $request, Report $report)
    {
        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $report->update([
            'assigned_to' => $request->assigned_to,
        ]);

        if ($request->assigned_to) {
            $assignee = User::find($request->assigned_to);
            ReportComment::create([
                'report_id' => $report->id,
                'user_id' => auth()->id(),
                'comment' => 'Laporan ditugaskan ke: ' . $assignee->name,
                'is_internal' => true,
            ]);
        }

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Penugasan berhasil diperbarui!');
    }

    public function addComment(Request $request, Report $report)
    {
        $request->validate([
            'comment' => 'required|string',
            'is_internal' => 'nullable|boolean',
        ]);

        ReportComment::create([
            'report_id' => $report->id,
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'is_internal' => $request->is_internal ?? false,
        ]);

        return redirect()->route('admin.reports.show', $report)
            ->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus!');
    }
}
