<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Admission;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Scholar;
use App\Models\Synopsis;
use App\Models\RACCommitteeSubmission;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Traits\HasAlertResponses;

class HODController extends Controller
{
    use HasAlertResponses;
    public function showUploadMeritListForm()
    {
        $department = auth()->user()->departmentManaging;

        if (! $department) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        // Pass a collection containing only the HOD's department to the view
        $departments = collect([$department]);

        return view('hod.admissions.upload_merit_list', compact('departments'));
    }

    public function uploadMeritList(Request $request)
    {
        $request->validate([
            'merit_list_file' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'department_id' => 'required|exists:departments,id',
            'admission_date' => 'required|date',
        ]);

        $department = Department::findOrFail($request->department_id);

        if (! $request->user()->departmentManaging || $request->user()->departmentManaging->id !== $department->id) {
            abort(403, 'Unauthorized action.');
        }

        $path = $request->file('merit_list_file')->store('merit_lists', 'public');

        try {
            // Parse the uploaded file
            $data = Excel::toArray(new class implements WithHeadingRow {
                // This anonymous class handles the file parsing
            }, $request->file('merit_list_file'));

            $meritListData = $data[0]; // Get the first sheet

            // Validate that required columns exist
            $requiredColumns = ['name', 'email', 'form_number', 'mobile_number'];
            $firstRow = reset($meritListData);
            $missingColumns = array_diff($requiredColumns, array_keys($firstRow));

            if (!empty($missingColumns)) {
                return redirect()->back()->withErrors([
                    'merit_list_file' => 'Missing required columns: ' . implode(', ', $missingColumns) . '. Please ensure your file has columns: ' . implode(', ', $requiredColumns)
                ]);
            }

            $createdScholars = 0;
            $errors = [];

            DB::transaction(function () use ($request, $department, $path, $meritListData, &$createdScholars, &$errors) {
                $admission = Admission::create([
                    'department_id' => $department->id,
                    'merit_list_file' => $path,
                    'admission_date' => $request->admission_date,
                    'status' => 'merit_list_uploaded',
                ]);

                $scholarRole = Role::where('name', 'Scholar')->first();

                if (! $scholarRole) {
                    abort(500, 'Scholar role not found.');
                }

                foreach ($meritListData as $index => $row) {
                    try {
                        // Skip empty rows
                        if (empty($row['name']) || empty($row['email']) || empty($row['form_number']) || empty($row['mobile_number'])) {
                            continue;
                        }

                        // Validate email format
                        if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                            $errors[] = "Row " . ($index + 2) . ": Invalid email format for " . $row['name'];
                            continue;
                        }

                        // Validate mobile number format (basic validation)
                        $mobileNumber = preg_replace('/[^0-9]/', '', $row['mobile_number']);
                        if (strlen($mobileNumber) < 10) {
                            $errors[] = "Row " . ($index + 2) . ": Invalid mobile number format for " . $row['name'];
                            continue;
                        }

                        // Generate password from form number + last 5 digits of mobile number
                        $formNumber = trim($row['form_number']);
                        $lastFiveDigits = substr($mobileNumber, -5);
                        $generatedPassword = $formNumber ."#". $lastFiveDigits;

                        // Validate form number format (should be alphanumeric)
                        if (!preg_match('/^[A-Za-z0-9]+$/', $formNumber)) {
                            $errors[] = "Row " . ($index + 2) . ": Form number should contain only letters and numbers for " . $row['name'];
                            continue;
                        }

                        // Check if user with this email already exists
                        if (User::where('email', $row['email'])->exists()) {
                            $errors[] = "Row " . ($index + 2) . ": User with email " . $row['email'] . " already exists";
                            continue;
                        }

                        // Check if form number already exists
                        if (Scholar::where('form_number', $row['form_number'])->exists()) {
                            $errors[] = "Row " . ($index + 2) . ": Form number " . $row['form_number'] . " already exists";
                            continue;
                        }

                        // Split name into first and last name
                        $nameParts = explode(' ', trim($row['name']), 2);
                        $firstName = $nameParts[0];
                        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

                        // Create user account
                        $user = User::create([
                            'name' => trim($row['name']),
                            'email' => trim($row['email']),
                            'password' => Hash::make($generatedPassword), // Password: form_number + last 5 digits of mobile
                            'role_id' => $scholarRole->id,
                            'user_type' => 'scholar',
                        ]);

                        // Create scholar record
                        Scholar::create([
                            'user_id' => $user->id,
                            'admission_id' => $admission->id,
                            'form_number' => trim($row['form_number']),
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'contact_number' => $mobileNumber,
                            'status' => 'pending_profile_completion',
                        ]);

                        $createdScholars++;

                    } catch (\Exception $e) {
                        $errors[] = "Row " . ($index + 2) . ": Error creating scholar - " . $e->getMessage();
                    }
                }
            });

            // Store errors in session if any
            if (!empty($errors)) {
                session()->flash('warnings', $errors);
            }

            $message = "Merit list uploaded successfully. " . $createdScholars . " scholar(s) created.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " error(s) occurred during processing.";
            }

            return $this->successResponse($message);

        } catch (\Exception $e) {
            return $this->errorResponse('Error parsing file: ' . $e->getMessage());
        }
    }

    public function downloadMeritListTemplate()
    {
        $templateData = [
            [
                'name' => 'John Doe',
                'email' => 'john.doe@example.com',
                'form_number' => 'FORM001',
                'mobile_number' => '9876543210'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'form_number' => 'FORM002',
                'mobile_number' => '9876543211'
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert.johnson@example.com',
                'form_number' => 'FORM003',
                'mobile_number' => '9876543212'
            ],
            [
                'name' => 'INSTRUCTIONS:',
                'email' => '1. Replace sample data with actual scholar information',
                'form_number' => '2. Form number should be unique (alphanumeric only)',
                'mobile_number' => '3. Mobile number should be 10 digits minimum'
            ],
            [
                'name' => 'PASSWORD GENERATION:',
                'email' => 'Password = form_number + last 5 digits of mobile',
                'form_number' => 'Example: FORM001 + 43210 = FORM00143210',
                'mobile_number' => 'Scholars will use this password to login'
            ]
        ];

        return Excel::download(new class($templateData) implements FromArray, WithHeadings, WithStyles {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function array(): array
            {
                return $this->data;
            }

            public function headings(): array
            {
                return [
                    'name',
                    'email',
                    'form_number',
                    'mobile_number'
                ];
            }

            public function styles(Worksheet $sheet)
            {
                return [
                    // Style the first row as bold
                    1 => ['font' => ['bold' => true]],
                    // Style instruction rows with different background
                    4 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]],
                    5 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FF0000']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'FFFF00']]],
                ];
            }
        }, 'merit_list_template.xlsx');
    }

    public function viewMeritLists()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $admissions = Admission::where('department_id', $hodDepartment->id)
                                ->latest()
                                ->get();

        return view('hod.admissions.view_merit_lists', compact('admissions'));
    }

    public function listScholars(Request $request)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $query = Scholar::whereHas('admission.department', function ($query) use ($hodDepartment) {
                                $query->where('id', $hodDepartment->id);
                            })
                            ->with(['user', 'admission', 'currentSupervisor.supervisor.user', 'synopses', 'registrationForm', 'thesisSubmissions' => function($query) {
                                $query->where('status', 'approved_by_da')->latest();
                            }]);

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Apply filter based on request parameter
        $filter = $request->get('filter', 'all');
        if ($filter === 'assigned') {
            $query->where('status', 'supervisor_assigned');
        } elseif ($filter === 'unassigned') {
            $query->where('status', '!=', 'supervisor_assigned');
        } elseif ($filter === 'submitted_forms') {
            $query->where('registration_form_status', '!=', 'not_started');
        } elseif ($filter === 'submitted_synopses') {
            $query->whereHas('synopses');
        } elseif ($filter === 'pending_approval') {
            $query->whereHas('synopses', function($q) {
                $q->where('status', 'pending_hod_approval');
            });
        }

        $scholars = $query->get();

        return view('hod.scholars.list', compact('scholars'));
    }

    /**
     * List scholars with their submission status for HOD review
     */
    public function listScholarsWithSubmissions(Request $request)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $query = Scholar::whereHas('admission.department', function ($query) use ($hodDepartment) {
                                $query->where('id', $hodDepartment->id);
                            })
                            ->with([
                                'user',
                                'admission',
                                'currentSupervisor.supervisor.user',
                                'synopses' => function($query) {
                                    $query->latest();
                                },
                                'registrationForm',
                                'supervisorAssignments.supervisor.user'
                            ]);

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                  ->orWhere('last_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // Apply filter based on request parameter
        $filter = $request->get('filter', 'all');
        if ($filter === 'submitted_forms') {
            $query->where('registration_form_status', '!=', 'not_started');
        } elseif ($filter === 'submitted_synopses') {
            $query->whereHas('synopses');
        } elseif ($filter === 'pending_synopsis_approval') {
            $query->whereHas('synopses', function($q) {
                $q->where('status', 'pending_hod_approval');
            });
        } elseif ($filter === 'supervisor_verified') {
            $query->where('registration_form_status', 'approved');
        } elseif ($filter === 'needs_attention') {
            $query->where(function($q) {
                $q->whereHas('synopses', function($sq) {
                    $sq->where('status', 'pending_hod_approval');
                })->orWhere('registration_form_status', 'submitted');
            });
        }

        $scholars = $query->get();

        // Add workflow status for each scholar
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);
        $scholarsWithStatus = $scholars->map(function($scholar) use ($workflowSyncService) {
            $scholar->workflow_status = $workflowSyncService->getScholarWorkflowStatus($scholar);
            return $scholar;
        });

        return view('hod.scholars.submissions', compact('scholarsWithStatus'));
    }

    public function viewScholarDetails(Scholar $scholar)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment || $scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        $scholar->load(['user', 'admission.department', 'supervisorAssignments.supervisor.user', 'racs.supervisor.user', 'synopses']);

        return view('hod.scholars.show', compact('scholar'));
    }

    public function assignSupervisorForm(Scholar $scholar)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment || $scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        // Get any pending supervisor assignments for this scholar
        $pendingAssignments = $scholar->supervisorAssignments()
            ->where('status', 'pending_hod_approval')
            ->with(['supervisor.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ensure the scholar does not already have an assigned supervisor
        if ($scholar->currentSupervisor) {
            return redirect()->route('hod.scholars.show', $scholar)->with('info', 'Scholar already has an assigned supervisor.');
        }

        // Get supervisor preferences for this scholar
        $preferences = $scholar->supervisorPreferences()
            ->where('status', 'pending')
            ->with(['supervisor.user', 'supervisor' => function($query) {
                $query->with(['assignedScholars' => function ($q) {
                    $q->wherePivot('status', 'assigned');
                }]);
            }])
            ->orderBy('preference_order')
            ->get();

        $supervisors = \App\Models\Supervisor::whereHas('user', function ($query) use ($hodDepartment) {
                                            $query->where('department_id', $hodDepartment->id);
                                        })
                                        ->with(['user', 'assignedScholars' => function ($query) {
                                            $query->wherePivot('status', 'assigned');
                                        }])
                                        ->get()
                                        ->filter(function ($supervisor) {
                                            return $supervisor->canAcceptMoreScholars();
                                        });

        return view('hod.scholars.assign_supervisor', compact('scholar', 'supervisors', 'pendingAssignments', 'preferences'));
    }

    public function storeSupervisorAssignment(Request $request, Scholar $scholar)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment || $scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure the scholar does not already have an assigned supervisor
        if ($scholar->currentSupervisor) {
            return redirect()->route('hod.scholars.show', $scholar)->with('info', 'Scholar already has an assigned supervisor.');
        }

        // Determine if selecting from preferences or manual selection
        $assignmentType = $request->input('assignment_type', 'manual'); // 'preference' or 'manual'

        if ($assignmentType === 'preference') {
            // Validate preference selection
            $request->validate([
                'selected_preference_id' => 'required|exists:supervisor_preferences,id',
                'remarks' => 'nullable|string|max:1000',
            ]);

            $selectedPreference = \App\Models\SupervisorPreference::where('id', $request->selected_preference_id)
                ->where('scholar_id', $scholar->id)
                ->where('status', 'pending')
                ->with('supervisor')
                ->firstOrFail();

            $supervisorId = $selectedPreference->supervisor_id;
            $justification = $selectedPreference->justification;

            // Approve the selected preference
            $selectedPreference->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'remarks' => $request->remarks,
            ]);

            // Reject all other preferences for this scholar
            \App\Models\SupervisorPreference::where('scholar_id', $scholar->id)
                ->where('id', '!=', $selectedPreference->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id(),
                ]);
        } else {
            // Manual selection
            $request->validate([
                'supervisor_id' => 'required|exists:supervisors,id',
            ]);

            $supervisorId = $request->supervisor_id;
            $justification = null;

            // Reject all pending preferences if manually selecting
            \App\Models\SupervisorPreference::where('scholar_id', $scholar->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'rejected_at' => now(),
                    'rejected_by' => auth()->id(),
                ]);
        }

        $supervisor = \App\Models\Supervisor::where('id', $supervisorId)
                                        ->whereHas('user', function ($query) use ($hodDepartment) {
                                            $query->where('department_id', $hodDepartment->id);
                                        })
                                        ->with(['assignedScholars' => function ($query) {
                                            $query->wherePivot('status', 'assigned');
                                        }])
                                        ->firstOrFail();

        // Check if supervisor can accept more scholars
        if (!$supervisor->canAcceptMoreScholars()) {
            return redirect()->back()->withErrors([
                'supervisor_id' => "This supervisor has reached their maximum capacity of {$supervisor->getScholarLimit()} scholars."
            ]);
        }

        // First, reject any existing pending assignments for this scholar
        \App\Models\SupervisorAssignment::where('scholar_id', $scholar->id)
            ->where('status', 'pending_hod_approval')
            ->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

        // Create the new assignment with 'assigned' status (directly assigned by HOD)
        \App\Models\SupervisorAssignment::create([
            'scholar_id' => $scholar->id,
            'supervisor_id' => $supervisorId,
            'assigned_date' => now(),
            'status' => 'assigned', // Directly assigned by HOD
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'justification' => $justification,
        ]);

        // Update scholar status to reflect supervisor assignment
        $scholar->update(['status' => 'supervisor_assigned']);

        $message = $assignmentType === 'preference'
            ? 'Supervisor assigned successfully from preferences.'
            : 'Supervisor assigned successfully.';

        return redirect()->route('hod.scholars.list')->with('success', $message);
    }

    public function listSupervisors()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (! $hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $supervisors = \App\Models\Supervisor::whereHas('user', function ($query) use ($hodDepartment) {
                                            $query->where('department_id', $hodDepartment->id);
                                        })
                                        ->withCount(['assignedScholars as assigned_scholars_count' => function ($query) {
                                            $query->where('supervisor_assignments.status', 'assigned');
                                        }])
                                        ->with('user')
                                        ->get();

        return view('hod.supervisors.list', compact('supervisors'));
    }

    public function listPendingSynopses()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $synopses = Synopsis::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->whereIn('status', ['pending_supervisor_approval', 'pending_hod_approval'])
        ->with(['scholar.user', 'scholar.admission.department', 'rac.supervisor.user', 'scholar.currentSupervisor.supervisor.user'])
        ->orderBy('status', 'asc') // Show pending_hod_approval first, then pending_supervisor_approval
        ->get();

        return view('hod.synopsis.pending', compact('synopses'));
    }

    public function approveSynopsisForm(Synopsis $synopsis)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $synopsis->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($synopsis->status, ['pending_supervisor_approval', 'pending_hod_approval'])) {
            abort(403, 'This synopsis is not pending approval.');
        }

        // Load additional relationships for registration details
        $synopsis->load([
            'scholar.user',
            'scholar.admission.department',
            'scholar.currentSupervisor.supervisor.user',
            'rac.supervisor.user'
        ]);

        return view('hod.synopsis.approve', compact('synopsis'));
    }

    public function approveSynopsis(Request $request, Synopsis $synopsis)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $synopsis->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($synopsis->status !== 'pending_hod_approval') {
            abort(403, 'This synopsis is not pending HOD approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'remarks' => 'required|string|max:500',
            'drc_date' => 'required',
        ]);

        // Use WorkflowSyncService for syncing
        $workflowSyncService = app(\App\Services\WorkflowSyncService::class);

        if ($request->action === 'approve') {
            // Upload DRC minutes file
            $synopsis->update([
                'hod_remarks' => $request->remarks,
                'drc_date' => $request->drc_date,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'hod_approve', Auth::user());
            $message = 'Synopsis approved and forwarded to DA.';
        } else {
            $synopsis->update([
                'hod_remarks' => $request->remarks,
                'drc_date' => $request->drc_date,
            ]);

            // Sync workflow
            $workflowSyncService->syncSynopsisWorkflow($synopsis, 'hod_reject', Auth::user());

            $message = 'Synopsis rejected by HOD.';
        }

        return redirect()->route('hod.synopsis.pending')->with('success', $message);
    }

    /**
     * List pending progress reports for HOD approval
     */
    public function listPendingProgressReports()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $progressReports = \App\Models\ProgressReport::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->where('status', 'pending_hod_approval')
        ->with(['scholar.user', 'scholar.admission.department', 'supervisor.user'])
        ->latest()
        ->get();

        return view('hod.progress_reports.pending', compact('progressReports'));
    }

    /**
     * Show progress report approval form
     */
    public function approveProgressReportForm(\App\Models\ProgressReport $progressReport)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $progressReport->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($progressReport->status !== 'pending_hod_approval') {
            abort(403, 'This progress report is not pending HOD approval.');
        }

        return view('hod.progress_reports.approve', compact('progressReport'));
    }

    /**
     * Process progress report approval/rejection
     */
    public function approveProgressReport(Request $request, \App\Models\ProgressReport $progressReport)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $progressReport->scholar->admission->department_id !== $hodDepartment->id) {
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
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
            ]);

            $message = 'Progress report approved and forwarded to DA.';
        } else {
            $progressReport->update([
                'status' => 'rejected',
                'da_approver_id' => Auth::id(),
                'da_approved_at' => now(),
                'da_remarks' => $request->remarks,
                'rejected_by' => Auth::id(),
                'rejected_at' => now(),
                'rejection_reason' => $request->remarks,
                'rejection_count' => $progressReport->rejection_count + 1,
            ]);

            $message = 'Progress report rejected by HOD.';
        }

        return redirect()->route('hod.progress_reports.pending')->with('success', $message);
    }

    /**
     * List pending thesis submissions for HOD approval
     */
    public function listPendingThesisSubmissions()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $thesisSubmissions = \App\Models\ThesisSubmission::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->where('status', 'pending_hod_approval')
        ->with(['scholar.user', 'scholar.admission.department', 'supervisor.user'])
        ->latest()
        ->get();

        return view('hod.thesis.pending', compact('thesisSubmissions'));
    }

    /**
     * Show thesis approval form
     */
    public function approveThesisForm(\App\Models\ThesisSubmission $thesis)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $thesis->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($thesis->status !== 'pending_hod_approval') {
            abort(403, 'This thesis is not pending HOD approval.');
        }

        return view('hod.thesis.approve', compact('thesis'));
    }

    /**
     * Process thesis approval/rejection
     */
    public function approveThesis(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $thesis->scholar->admission->department_id !== $hodDepartment->id) {
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

            $message = 'Thesis approved and forwarded to DA.';
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

        return redirect()->route('hod.thesis.pending')->with('success', $message);
    }

    /**
     * Show comprehensive list of all scholar submissions for HOD
     */
    public function listAllScholarSubmissions()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        // Get all scholars in the department
        $scholars = Scholar::whereHas('admission.department', function ($query) use ($hodDepartment) {
            $query->where('id', $hodDepartment->id);
        })
        ->with(['user', 'admission.department'])
        ->get();

        // Get all synopses for these scholars
        $synopses = Synopsis::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->with(['scholar.user', 'scholar.admission.department', 'rac.supervisor.user'])
        ->latest()
        ->get();

        // Get all progress reports for these scholars
        $progressReports = \App\Models\ProgressReport::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->with(['scholar.user', 'scholar.admission.department', 'supervisor.user'])
        ->latest()
        ->get();

        // Get all thesis submissions for these scholars
        $thesisSubmissions = \App\Models\ThesisSubmission::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->with(['scholar.user', 'scholar.admission.department', 'supervisor.user'])
        ->latest()
        ->get();

        // Get all RAC committee submissions for these scholars
        $racCommitteeSubmissions = \App\Models\RACCommitteeSubmission::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->with(['scholar.user', 'scholar.admission.department', 'supervisor.user', 'hod'])
        ->latest()
        ->get();

        return view('hod.scholars.all_submissions', compact(
            'scholars',
            'synopses',
            'progressReports',
            'thesisSubmissions',
            'racCommitteeSubmissions'
        ));
    }

    // Viva Examination Management
    public function listVivaExaminations()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $vivaExaminations = \App\Models\VivaExamination::whereHas('scholar.admission', function($query) use ($hodDepartment) {
                $query->where('department_id', $hodDepartment->id);
            })
            ->with(['scholar.user', 'thesisSubmission', 'supervisor', 'externalExaminer', 'internalExaminer'])
            ->latest()
            ->get();

        return view('hod.viva.examinations', compact('vivaExaminations'));
    }

    public function showScheduleVivaForm(\App\Models\ThesisSubmission $thesis)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $thesis->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if thesis is approved and ready for viva
        if ($thesis->status !== 'approved_by_da') {
            return redirect()->back()->with('error', 'Thesis must be approved by DA before scheduling viva.');
        }

        // Get potential examiners from the same department
        $examiners = User::whereHas('roles', function($query) {
                $query->whereIn('name', ['supervisor', 'staff']);
            })
            ->where('department_id', $hodDepartment->id)
            ->get();

        return view('hod.viva.schedule', compact('thesis', 'examiners'));
    }

    public function scheduleViva(Request $request, \App\Models\ThesisSubmission $thesis)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $thesis->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if thesis is approved and ready for viva
        if ($thesis->status !== 'approved_by_da') {
            return redirect()->back()->with('error', 'Thesis must be approved by DA before scheduling viva.');
        }

        $request->validate([
            'examination_type' => 'required|in:offline,online',
            'examination_date' => 'required|date|after:today',
            'examination_time' => 'required',
            'venue' => 'required|string|max:255',
            'external_examiner_id' => 'required|exists:users,id',
            'internal_examiner_id' => 'nullable|exists:users,id',
            'examination_notes' => 'nullable|string|max:1000',
        ]);

        // Create viva examination
        $vivaExamination = \App\Models\VivaExamination::create([
            'thesis_submission_id' => $thesis->id,
            'scholar_id' => $thesis->scholar_id,
            'supervisor_id' => $thesis->supervisor_id,
            'external_examiner_id' => $request->external_examiner_id,
            'internal_examiner_id' => $request->internal_examiner_id,
            'hod_id' => Auth::id(),
            'examination_type' => $request->examination_type,
            'examination_date' => $request->examination_date,
            'examination_time' => $request->examination_time,
            'venue' => $request->venue,
            'examination_notes' => $request->examination_notes,
            'status' => 'scheduled',
        ]);

        // Update thesis status
        $thesis->update(['status' => 'viva_scheduled']);

        return redirect()->route('hod.viva.examinations')->with('success', 'Viva examination scheduled successfully.');
    }

    public function showVivaDetails(\App\Models\VivaExamination $vivaExamination)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $vivaExamination->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('hod.viva.details', compact('vivaExamination'));
    }

    public function updateVivaStatus(Request $request, \App\Models\VivaExamination $vivaExamination)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $vivaExamination->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
            'result' => 'nullable|in:pass,fail,conditional_pass,pending',
            'examiner_comments' => 'nullable|string|max:1000',
            'supervisor_comments' => 'nullable|string|max:1000',
            'additional_remarks' => 'nullable|string|max:1000',
        ]);

        $updateData = [
            'status' => $request->status,
            'examiner_comments' => $request->examiner_comments,
            'supervisor_comments' => $request->supervisor_comments,
            'additional_remarks' => $request->additional_remarks,
        ];

        if ($request->status === 'completed') {
            $updateData['completed_at'] = now();
            if ($request->result) {
                $updateData['result'] = $request->result;
            }
        }

        $vivaExamination->update($updateData);

        return redirect()->route('hod.viva.examinations')->with('success', 'Viva examination status updated successfully.');
    }

    public function downloadVivaReport(\App\Models\VivaReport $vivaReport)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $vivaReport->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$vivaReport->report_file) {
            return redirect()->back()->with('error', 'Viva report not yet generated.');
        }

        $reportService = new \App\Services\VivaReportGenerationService();
        $filePath = $reportService->downloadVivaReport($vivaReport);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Viva report file not found.');
        }

        return response()->download($filePath, 'viva_report_' . $vivaReport->id . '.pdf');
    }

    public function downloadOfficeNote(\App\Models\VivaExamination $vivaExamination)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $vivaExamination->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$vivaExamination->office_note_file) {
            return redirect()->back()->with('error', 'Office note not yet generated.');
        }

        $officeNoteService = new \App\Services\OfficeNoteGenerationService();
        $filePath = $officeNoteService->downloadOfficeNote($vivaExamination);

        if (!$filePath) {
            return redirect()->back()->with('error', 'Office note file not found.');
        }

        return response()->download($filePath, 'office_note_' . $vivaExamination->id . '.pdf');
    }

    /**
     * List pending supervisor assignments for HOD approval
     */
    public function listPendingSupervisorAssignments()
    {
        $user = auth()->user();
        if ($user->user_type !== 'hod') {
            abort(403, 'Unauthorized access.');
        }

        $department = $user->departmentManaging;
        if (!$department) {
            abort(403, 'No department assigned.');
        }

        // Get supervisor preferences from scholars in HOD's department that are pending approval
        // Only show preferences where the scholar doesn't already have an approved assignment
        $pendingPreferences = \App\Models\SupervisorPreference::whereHas('scholar', function ($query) use ($department) {
            $query->whereHas('admission', function ($q) use ($department) {
                $q->where('department_id', $department->id);
            });
        })
        ->where('status', 'pending')
        ->whereDoesntHave('scholar', function ($query) {
            $query->whereHas('supervisorAssignments', function ($q) {
                $q->where('status', 'assigned');
            });
        })
        ->with(['scholar.user', 'supervisor.user'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('scholar_id');

        return view('hod.supervisor_assignments.pending', compact('pendingPreferences'));
    }

    /**
     * List pending supervisor preferences
     */
    public function listPendingSupervisorPreferences()
    {
        $user = auth()->user();
        if ($user->user_type !== 'hod') {
            abort(403, 'Unauthorized access.');
        }

        $department = $user->departmentManaging;
        if (!$department) {
            abort(403, 'No department assigned.');
        }

        // Get supervisor preferences from scholars in HOD's department that are pending approval
        $pendingPreferences = \App\Models\SupervisorPreference::whereHas('scholar', function ($query) use ($department) {
            $query->whereHas('admission', function ($q) use ($department) {
                $q->where('department_id', $department->id);
            });
        })
        ->where('status', 'pending')
        ->whereDoesntHave('scholar', function ($query) {
            $query->whereHas('supervisorAssignments', function ($q) {
                $q->where('status', 'assigned');
            });
        })
        ->with(['scholar.user', 'supervisor.user'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('scholar_id');

        return view('hod.supervisor_preferences.pending', compact('pendingPreferences'));
    }

    /**
     * Show supervisor preferences approval form
     */
    public function showSupervisorPreferencesApprovalForm($scholarId)
    {
        $user = auth()->user();
        if ($user->user_type !== 'hod') {
            abort(403, 'Unauthorized access.');
        }

        $department = $user->departmentManaging;
        if (!$department) {
            abort(403, 'No department assigned.');
        }

        $scholar = \App\Models\Scholar::whereHas('admission', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        })->findOrFail($scholarId);

        // Get all preferences for this scholar
        $preferences = $scholar->supervisorPreferences()
            ->where('status', 'pending')
            ->with('supervisor.user')
            ->orderBy('preference_order')
            ->get();

        if ($preferences->isEmpty()) {
            return redirect()->route('hod.supervisor_preferences.pending')
                ->with('error', 'No pending preferences found for this scholar.');
        }

        return view('hod.supervisor_preferences.approve', compact('scholar', 'preferences'));
    }

    /**
     * Approve supervisor preferences
     */
    public function approveSupervisorPreferences(Request $request, $scholarId)
    {
        $user = auth()->user();
        if ($user->user_type !== 'hod') {
            abort(403, 'Unauthorized access.');
        }

        $department = $user->departmentManaging;
        if (!$department) {
            abort(403, 'No department assigned.');
        }

        $request->validate([
            'selected_preference_id' => 'required|exists:supervisor_preferences,id',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $scholar = \App\Models\Scholar::whereHas('admission', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        })->findOrFail($scholarId);

        $selectedPreference = \App\Models\SupervisorPreference::where('id', $request->selected_preference_id)
            ->where('scholar_id', $scholarId)
            ->where('status', 'pending')
            ->firstOrFail();

        // Approve the selected preference
        $selectedPreference->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'remarks' => $request->remarks,
        ]);

        // Reject all other preferences for this scholar
        \App\Models\SupervisorPreference::where('scholar_id', $scholarId)
            ->where('id', '!=', $selectedPreference->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

        // Create supervisor assignment
        \App\Models\SupervisorAssignment::create([
            'scholar_id' => $scholarId,
            'supervisor_id' => $selectedPreference->supervisor_id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'justification' => $selectedPreference->justification,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Update scholar status
        $scholar->update([
            'status' => 'assigned',
        ]);

        return redirect()->route('hod.supervisor_preferences.pending')
            ->with('success', 'Supervisor preferences approved successfully!');
    }

    /**
     * Approve supervisor assignment
     */
    public function approveSupervisorAssignment(\App\Models\SupervisorAssignment $assignment)
    {
        $user = auth()->user();
        if ($user->user_type !== 'hod') {
            abort(403, 'Unauthorized access.');
        }

        // Check if assignment belongs to HOD's department
        $department = $user->departmentManaging;
        if (!$department || $assignment->scholar->admission->department_id !== $department->id) {
            abort(403, 'Unauthorized access to this assignment.');
        }

        // Update assignment status
        $assignment->update([
            'status' => 'assigned',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        // Reject any other pending assignments for the same scholar
        \App\Models\SupervisorAssignment::where('scholar_id', $assignment->scholar_id)
            ->where('id', '!=', $assignment->id)
            ->where('status', 'pending_hod_approval')
            ->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

        // Update scholar status
        $assignment->scholar->update([
            'status' => 'assigned',
        ]);

        return $this->successResponse('Supervisor assignment approved successfully.');
    }

    /**
     * Reject supervisor assignment
     */
    public function rejectSupervisorAssignment(\App\Models\SupervisorAssignment $assignment)
    {
        $user = auth()->user();
        if ($user->user_type !== 'hod') {
            abort(403, 'Unauthorized access.');
        }

        // Check if assignment belongs to HOD's department
        $department = $user->departmentManaging;
        if (!$department || $assignment->scholar->admission->department_id !== $department->id) {
            abort(403, 'Unauthorized access to this assignment.');
        }

        // Update assignment status
        $assignment->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
        ]);

        // Update scholar status back to pending
        $assignment->scholar->update([
            'status' => 'pending_supervisor_assignment',
        ]);

        return $this->successResponse('Supervisor assignment rejected successfully.');
    }

    /**
     * List pending RAC committee submissions for HOD approval
     */
    public function listPendingRACCommitteeSubmissions()
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $submissions = RACCommitteeSubmission::whereHas('scholar.admission', function ($query) use ($hodDepartment) {
            $query->where('department_id', $hodDepartment->id);
        })
        ->where('status', 'pending_hod_approval')
        ->with(['scholar.user', 'supervisor.user'])
        ->latest()
        ->get();

        return view('hod.rac_committee.pending', compact('submissions'));
    }

    /**
     * Show approval form for RAC committee submission
     */
    public function showRACCommitteeApprovalForm(RACCommitteeSubmission $racCommitteeSubmission)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $racCommitteeSubmission->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($racCommitteeSubmission->status !== 'pending_hod_approval') {
            abort(403, 'This RAC committee submission is not pending approval.');
        }

        $racCommitteeSubmission->load(['scholar.user', 'supervisor.user', 'scholar.admission.department']);

        return view('hod.rac_committee.approve', compact('racCommitteeSubmission'));
    }

    /**
     * Process RAC committee submission approval/rejection
     */
    public function processRACCommitteeApproval(Request $request, RACCommitteeSubmission $racCommitteeSubmission)
    {
        $hodDepartment = auth()->user()->departmentManaging;

        if (!$hodDepartment || $racCommitteeSubmission->scholar->admission->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if ($racCommitteeSubmission->status !== 'pending_hod_approval') {
            abort(403, 'This RAC committee submission is not pending approval.');
        }

        $request->validate([
            'action' => 'required|in:approve,reject',
            'drc_date' => 'required|date',
            'hod_remarks' => 'required|string|max:1000',
        ]);

        if ($request->action === 'approve') {
            $racCommitteeSubmission->update([
                'status' => 'approved',
                'hod_id' => Auth::id(),
                'drc_date' => $request->drc_date,
                'hod_remarks' => $request->hod_remarks,
                'approved_at' => now(),
            ]);

            $message = 'RAC committee members approved successfully.';
        } else {
            $racCommitteeSubmission->update([
                'status' => 'rejected',
                'hod_id' => Auth::id(),
                'drc_date' => $request->drc_date,
                'hod_remarks' => $request->hod_remarks,
                'rejected_at' => now(),
            ]);

            $message = 'RAC committee submission rejected.';
        }

        return redirect()->route('hod.rac_committee.pending')->with('success', $message);
    }
}
