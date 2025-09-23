<?php

namespace App\Http\Controllers;

use App\Models\DRC;
use App\Models\RAC;
use App\Models\SupervisorAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Synopsis;
use App\Notifications\SupervisorAssignmentApproved;
use App\Notifications\SupervisorAssignmentRejected;
use App\Notifications\SynopsisApproved;

class DRCController extends Controller
{
    public function listPendingSupervisorAssignments()
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $pendingAssignments = SupervisorAssignment::where('status', 'pending_hod_approval')
                                                ->whereHas('scholar.admission.department', function ($query) use ($hodDepartment) {
                                                    $query->where('id', $hodDepartment->id);
                                                })
                                                ->with(['scholar.user', 'supervisor.user'])
                                                ->get();
        return view('drc.supervisor_assignments.pending', compact('pendingAssignments'));
    }

    public function approveSupervisorAssignment(Request $request, SupervisorAssignment $assignment)
    {
        $drc = Auth::user()->departmentManaging->drc;
        if (! $drc || $assignment->scholar->admission->department_id !== $drc->department_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        $assignment->update([
            'status' => $request->status === 'approved' ? 'assigned' : 'rejected_by_drc',
            'remarks' => $request->remarks, // Assuming a remarks column exists or will be added
        ]);

        // If approved, create RAC
        if ($request->status === 'approved') {
            RAC::create([
                'scholar_id' => $assignment->scholar_id,
                'supervisor_id' => $assignment->supervisor_id,
                'formed_date' => now(),
                'status' => 'pending_minutes_upload',
            ]);

            $assignment->scholar->user->notify(new SupervisorAssignmentApproved($assignment));
        } elseif ($request->status === 'rejected') {
            $assignment->scholar->user->notify(new SupervisorAssignmentRejected($assignment));
        }

        return redirect()->route('drc.supervisor_assignments.pending')->with('success', 'Supervisor assignment updated.');
    }

    public function listPendingSynopses()
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $pendingSynopses = Synopsis::where('status', 'pending_hod_approval')
                                ->whereHas('rac.scholar.admission.department', function ($query) use ($hodDepartment) {
                                    $query->where('id', $hodDepartment->id);
                                })
                                ->with(['scholar.user', 'rac.supervisor.user'])
                                ->get();
        return view('drc.synopsis.pending', compact('pendingSynopses'));
    }

    public function approveSynopsis(Request $request, Synopsis $synopsis)
    {
        $drc = Auth::user()->departmentManaging->drc;
        if (! $drc || $synopsis->rac->scholar->admission->department_id !== $drc->department_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($synopsis->status !== 'pending_hod_approval') {
            abort(403, 'This synopsis is not pending HOD approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $synopsis->update([
                'status' => 'pending_da_approval',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
            ]);

            $message = 'Synopsis approved and forwarded to Dean\'s Assistant.';
        } else {
            $synopsis->update([
                'status' => 'rejected',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
            ]);

            $message = 'Synopsis rejected.';
        }

        return redirect()->route('drc.synopsis.pending')->with('success', $message);
    }

    public function listPendingProgressReports()
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $pendingReports = \App\Models\ProgressReport::where('status', 'pending_hod_approval')
                                ->whereHas('scholar.admission.department', function ($query) use ($hodDepartment) {
                                    $query->where('id', $hodDepartment->id);
                                })
                                ->with(['scholar.user', 'supervisor.user', 'supervisorApprover'])
                                ->get();
        return view('drc.progress_reports.pending', compact('pendingReports'));
    }

    public function approveProgressReport(Request $request, \App\Models\ProgressReport $progressReport)
    {
        $drc = Auth::user()->departmentManaging->drc;
        if (! $drc || $progressReport->scholar->admission->department_id !== $drc->department_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($progressReport->status !== 'pending_hod_approval') {
            abort(403, 'This progress report is not pending HOD approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $progressReport->update([
                'status' => 'pending_da_approval',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
            ]);

            $message = 'Progress report approved and forwarded to Dean\'s Assistant.';
        } else {
            $progressReport->update([
                'status' => 'rejected',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
            ]);

            $message = 'Progress report rejected.';
        }

        return redirect()->route('drc.progress_reports.pending')->with('success', $message);
    }

    public function listPendingThesisSubmissions()
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $pendingTheses = \App\Models\ThesisSubmission::where('status', 'pending_hod_approval')
                                ->whereHas('scholar.admission.department', function ($query) use ($hodDepartment) {
                                    $query->where('id', $hodDepartment->id);
                                })
                                ->with(['scholar.user', 'supervisor.user', 'supervisorApprover'])
                                ->get();
        return view('drc.thesis.pending', compact('pendingTheses'));
    }

    public function approveThesis(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        $drc = Auth::user()->departmentManaging->drc;
        if (! $drc || $thesis->scholar->admission->department_id !== $drc->department_id) {
            abort(403, 'Unauthorized action.');
        }

        if ($thesis->status !== 'pending_hod_approval') {
            abort(403, 'This thesis is not pending HOD approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
        ]);

        if ($request->action === 'approve') {
            $thesis->update([
                'status' => 'pending_da_approval',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
            ]);

            $message = 'Thesis approved and forwarded to Dean\'s Assistant.';
        } else {
            $thesis->update([
                'status' => 'rejected_by_hod',
                'hod_approver_id' => Auth::id(),
                'hod_approved_at' => now(),
                'hod_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $thesis->rejection_count + 1,
            ]);

            $message = 'Thesis rejected by HOD.';
        }

        return redirect()->route('drc.thesis.pending')->with('success', $message);
    }

    public function uploadDRCMinutesForm(Request $request)
    {
        $hodDepartment = Auth::user()->departmentManaging;
        if (! $hodDepartment) {
            abort(403, 'Unauthorized action.');
        }

        $supervisorAssignmentId = $request->query('supervisor_assignment_id');
        $synopsisId = $request->query('synopsis_id');

        $record = null;
        $type = null;

        if ($supervisorAssignmentId) {
            $record = SupervisorAssignment::where('id', $supervisorAssignmentId)
                                        ->whereHas('scholar.admission.department', function ($query) use ($hodDepartment) {
                                            $query->where('id', $hodDepartment->id);
                                        })
                                        ->firstOrFail();
            $type = 'supervisor_assignment';
        } elseif ($synopsisId) {
            $record = Synopsis::where('id', $synopsisId)
                            ->whereHas('rac.scholar.admission.department', function ($query) use ($hodDepartment) {
                                $query->where('id', $hodDepartment->id);
                            })
                            ->firstOrFail();
            $type = 'synopsis';
        } else {
            abort(400, 'Missing supervisor_assignment_id or synopsis_id.');
        }

        // Ensure the record status is appropriate for uploading minutes (e.g., approved/rejected by DRC, awaiting minutes)
        // For supervisor assignments, if status is 'approved' or 'rejected' by HOD, it's ready for minutes
        // For synopses, if status is 'approved' or 'rejected' by HOD, it's ready for minutes
        if ($record->status !== 'approved' && $record->status !== 'rejected') {
            abort(403, 'Minutes can only be uploaded for approved or rejected records.');
        }

        $drc = $hodDepartment->drc;
        if (! $drc) {
            abort(500, 'DRC not found for this department.');
        }

        return view('drc.minutes.upload', compact('record', 'type', 'drc'));
    }

    public function storeDRCMinutes(Request $request)
    {
        $hodDepartment = Auth::user()->departmentManaging;
        if (! $hodDepartment) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'record_id' => 'required|integer',
            'record_type' => 'required|in:supervisor_assignment,synopsis',
            'minutes_file' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'meeting_date' => 'required|date',
        ]);

        $drc = $hodDepartment->drc;
        if (! $drc) {
            abort(500, 'DRC not found for this department.');
        }

        $record = null;
        if ($request->record_type === 'supervisor_assignment') {
            $record = SupervisorAssignment::where('id', $request->record_id)
                                        ->whereHas('scholar.admission.department', function ($query) use ($hodDepartment) {
                                            $query->where('id', $hodDepartment->id);
                                        })
                                        ->firstOrFail();
        } elseif ($request->record_type === 'synopsis') {
            $record = Synopsis::where('id', $request->record_id)
                            ->whereHas('rac.scholar.admission.department', function ($query) use ($hodDepartment) {
                                $query->where('id', $hodDepartment->id);
                            })
                            ->firstOrFail();
        }

        if (! $record) {
            abort(404, 'Record not found or unauthorized.');
        }

        $path = $request->file('minutes_file')->store('drc_minutes', 'public');

        // Update the DRC record with the minutes file and date
        $drc->update([
            'minutes_file' => $path,
            'meeting_date' => $request->meeting_date,
            'status' => 'minutes_uploaded', // New status for DRC minutes uploaded
        ]);

        // Also update the status of the related record (SupervisorAssignment or Synopsis)
        // This will allow us to track that minutes have been uploaded for this specific event.
        $record->update(['drc_minutes_uploaded' => true]); // Assuming a new column 'drc_minutes_uploaded'

        return redirect()->back()->with('success', 'DRC minutes uploaded successfully.');
    }
}
