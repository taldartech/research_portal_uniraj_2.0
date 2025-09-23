<?php

namespace App\Services;

use App\Models\DigitalSignature;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DigitalSignatureService
{
    public function createSignature($userId, $signatureData, $signatureName = null)
    {
        // Generate unique filename
        $filename = 'signatures/' . Str::uuid() . '.png';

        // Decode base64 signature data
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData));

        // Create image from data
        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageData);

        // Resize if needed (optional)
        $image->resize(300, 150);

        // Save to storage
        Storage::disk('public')->put($filename, $image->toPng());

        // Create database record
        $signature = DigitalSignature::create([
            'user_id' => $userId,
            'signature_name' => $signatureName,
            'signature_file' => $filename,
            'signature_data' => $signatureData,
            'is_active' => true,
        ]);

        return $signature;
    }

    public function updateSignature($signatureId, $signatureData, $signatureName = null)
    {
        $signature = DigitalSignature::findOrFail($signatureId);

        // Delete old file if exists
        if ($signature->signature_file && Storage::disk('public')->exists($signature->signature_file)) {
            Storage::disk('public')->delete($signature->signature_file);
        }

        // Generate new filename
        $filename = 'signatures/' . Str::uuid() . '.png';

        // Decode base64 signature data
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signatureData));

        // Create image from data
        $manager = new ImageManager(new Driver());
        $image = $manager->read($imageData);

        // Resize if needed
        $image->resize(300, 150);

        // Save to storage
        Storage::disk('public')->put($filename, $image->toPng());

        // Update database record
        $signature->update([
            'signature_name' => $signatureName,
            'signature_file' => $filename,
            'signature_data' => $signatureData,
        ]);

        return $signature;
    }

    public function getUserSignature($userId)
    {
        return DigitalSignature::where('user_id', $userId)
            ->where('is_active', true)
            ->first();
    }

    public function getUserSignatures($userId)
    {
        return DigitalSignature::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function deleteSignature($signatureId)
    {
        $signature = DigitalSignature::findOrFail($signatureId);

        // Delete file if exists
        if ($signature->signature_file && Storage::disk('public')->exists($signature->signature_file)) {
            Storage::disk('public')->delete($signature->signature_file);
        }

        // Delete database record
        $signature->delete();

        return true;
    }

    public function setActiveSignature($userId, $signatureId)
    {
        // Deactivate all signatures for user
        DigitalSignature::where('user_id', $userId)->update(['is_active' => false]);

        // Activate selected signature
        $signature = DigitalSignature::where('user_id', $userId)
            ->where('id', $signatureId)
            ->firstOrFail();

        $signature->update(['is_active' => true]);

        return $signature;
    }

    public function getSignatureForDocument($userId)
    {
        $signature = $this->getUserSignature($userId);

        if (!$signature || !$signature->isSignatureFileExists()) {
            return null;
        }

        return $signature->getSignaturePath();
    }
}
