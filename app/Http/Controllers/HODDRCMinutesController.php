<?php

namespace App\Http\Controllers;

use App\Models\DRC;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasAlertResponses;

class HODDRCMinutesController extends Controller
{
    use HasAlertResponses;

    /**
     * Display a listing of DRC minutes.
     */
    public function index()
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $drcMinutes = DRC::where('department_id', $hodDepartment->id)
            ->with(['department', 'hod'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('hod.drc_minutes.index', compact('drcMinutes'));
    }

    /**
     * Show the form for creating a new DRC minutes.
     */
    public function create()
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        return view('hod.drc_minutes.create', compact('hodDepartment'));
    }

    /**
     * Store a newly created DRC minutes in storage.
     */
    public function store(Request $request)
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment) {
            abort(403, 'You are not assigned as HOD to any department.');
        }

        $request->validate([
            'minutes_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'meeting_date' => 'required|date',
            'status' => 'nullable|string|max:255',
        ]);

        try {
            $filePath = $request->file('minutes_file')->store('drc_minutes', 'public');

            $drc = DRC::create([
                'department_id' => $hodDepartment->id,
                'hod_id' => Auth::id(),
                'minutes_file' => $filePath,
                'meeting_date' => $request->meeting_date,
                'status' => $request->status ?? 'active',
            ]);

            return $this->successResponse('DRC minutes created successfully!', 'hod.drc_minutes.index');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create DRC minutes: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified DRC minutes.
     */
    public function show(DRC $drc_minute)
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment || $drc_minute->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        $drc_minute->load(['department', 'hod']);

        return view('hod.drc_minutes.show', compact('drc_minute'));
    }

    /**
     * Show the form for editing the specified DRC minutes.
     */
    public function edit(DRC $drc_minute)
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment || $drc_minute->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('hod.drc_minutes.edit', compact('drc_minute', 'hodDepartment'));
    }

    /**
     * Update the specified DRC minutes in storage.
     */
    public function update(Request $request, DRC $drc_minute)
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment || $drc_minute->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'minutes_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'meeting_date' => 'required|date',
            'status' => 'nullable|string|max:255',
        ]);

        try {
            $updateData = [
                'meeting_date' => $request->meeting_date,
                'status' => $request->status ?? $drc_minute->status,
            ];

            // If a new file is uploaded, delete the old one and store the new one
            if ($request->hasFile('minutes_file')) {
                // Delete old file
                if ($drc_minute->minutes_file && Storage::disk('public')->exists($drc_minute->minutes_file)) {
                    Storage::disk('public')->delete($drc_minute->minutes_file);
                }

                $filePath = $request->file('minutes_file')->store('drc_minutes', 'public');
                $updateData['minutes_file'] = $filePath;
            }

            $drc_minute->update($updateData);

            return $this->successResponse('DRC minutes updated successfully!', 'hod.drc_minutes.index');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update DRC minutes: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified DRC minutes from storage.
     */
    public function destroy(DRC $drc_minute)
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment || $drc_minute->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        try {
            // Delete the file if it exists
            if ($drc_minute->minutes_file && Storage::disk('public')->exists($drc_minute->minutes_file)) {
                Storage::disk('public')->delete($drc_minute->minutes_file);
            }

            $drc_minute->delete();

            return $this->successResponse('DRC minutes deleted successfully!', 'hod.drc_minutes.index');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete DRC minutes: ' . $e->getMessage());
        }
    }

    /**
     * Download the DRC minutes file.
     */
    public function download(DRC $drc_minute)
    {
        $hodDepartment = Auth::user()->departmentManaging;

        if (!$hodDepartment || $drc_minute->department_id !== $hodDepartment->id) {
            abort(403, 'Unauthorized action.');
        }

        if (!$drc_minute->minutes_file || !Storage::disk('public')->exists($drc_minute->minutes_file)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($drc_minute->minutes_file);
    }
}

