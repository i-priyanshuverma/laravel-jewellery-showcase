<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateVendorProfileRequest;
use App\Models\VendorProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->vendorProfile ?? new VendorProfile;

        return view('vendor.profile.edit', compact('user', 'profile'));
    }

    public function update(UpdateVendorProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->update([
            'name' => $validated['name'],
        ]);

        $profileData = [
            'business_name' => $validated['business_name'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
        ];

        $user->vendorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return back()->with('success', 'Profile updated successfully.');
    }
}
