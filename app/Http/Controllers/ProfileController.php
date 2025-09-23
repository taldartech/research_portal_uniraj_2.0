<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    // Digital Signature Management
    public function showSignatures()
    {
        $user = Auth::user();
        $signatureService = new \App\Services\DigitalSignatureService();
        $signatures = $signatureService->getUserSignatures($user->id);

        return view('profile.signatures', compact('signatures'));
    }

    public function createSignature(Request $request)
    {
        $request->validate([
            'signature_data' => 'required|string',
            'signature_name' => 'nullable|string|max:255',
        ]);

        $signatureService = new \App\Services\DigitalSignatureService();
        $signature = $signatureService->createSignature(
            Auth::id(),
            $request->signature_data,
            $request->signature_name
        );

        return redirect()->route('profile.signatures')->with('success', 'Digital signature created successfully.');
    }

    public function updateSignature(Request $request, $signatureId)
    {
        $request->validate([
            'signature_data' => 'required|string',
            'signature_name' => 'nullable|string|max:255',
        ]);

        $signatureService = new \App\Services\DigitalSignatureService();
        $signature = $signatureService->updateSignature(
            $signatureId,
            $request->signature_data,
            $request->signature_name
        );

        return redirect()->route('profile.signatures')->with('success', 'Digital signature updated successfully.');
    }

    public function deleteSignature($signatureId)
    {
        $signatureService = new \App\Services\DigitalSignatureService();
        $signatureService->deleteSignature($signatureId);

        return redirect()->route('profile.signatures')->with('success', 'Digital signature deleted successfully.');
    }

    public function setActiveSignature($signatureId)
    {
        $signatureService = new \App\Services\DigitalSignatureService();
        $signatureService->setActiveSignature(Auth::id(), $signatureId);

        return redirect()->route('profile.signatures')->with('success', 'Active signature updated successfully.');
    }
}
