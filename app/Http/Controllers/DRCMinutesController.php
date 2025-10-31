<?php

namespace App\Http\Controllers;

use App\Models\DRC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DRCMinutesController extends Controller
{
    /**
     * Display a listing of DRC minutes (accessible to all authenticated users).
     */
    public function index()
    {
        // All authenticated users can view DRC minutes
        $drcMinutes = DRC::with(['department', 'hod'])
            ->orderBy('id', 'desc')
            ->paginate(15);
        return view('drc_minutes.index', compact('drcMinutes'));
    }

    /**
     * Display the specified DRC minutes.
     */
    public function show(DRC $drc_minute)
    {
        // All authenticated users can view DRC minutes
        $drc_minute->load(['department', 'hod']);

        return view('drc_minutes.show', compact('drc_minute'));
    }

    /**
     * Download the DRC minutes file.
     */
    public function download(DRC $drc_minute)
    {
        // All authenticated users can download DRC minutes
        if (!$drc_minute->minutes_file || !Storage::disk('public')->exists($drc_minute->minutes_file)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return Storage::disk('public')->download($drc_minute->minutes_file);
    }
}

