<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserStatus;
use App\Events\VendorStatusUpdated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StockReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VendorController extends Controller
{
    public function __construct(protected StockReservationService $reservationService) {}

    public function index(Request $request): View
    {
        $query = User::where('role', 'vendor')->with(['vendorProfile', 'products']);

        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'suspended'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('vendorProfile', function ($vq) use ($search) {
                        $vq->where('business_name', 'like', "%{$search}%");
                    });
            });
        }

        $vendors = $query->latest()->paginate(10)->withQueryString();

        return view('admin.vendors.index', compact('vendors'));
    }

    public function show(User $vendor): View
    {
        if (! $vendor->isVendor()) {
            abort(404);
        }

        $vendor->load(['vendorProfile', 'products.category', 'products.variants']);

        return view('admin.vendors.show', compact('vendor'));
    }

    public function approve(User $vendor): RedirectResponse
    {
        if (! $vendor->isVendor()) {
            abort(400);
        }

        $vendor->update(['status' => UserStatus::Approved]);

        $message = 'Your vendor account has been approved by platform administration.';
        VendorStatusUpdated::dispatch($vendor, 'approved', $message);

        return back()->with('success', "Vendor {$vendor->name} has been approved successfully.");
    }

    public function suspend(User $vendor): RedirectResponse
    {
        if (! $vendor->isVendor()) {
            abort(400);
        }

        $vendor->update(['status' => UserStatus::Suspended]);

        $released = $this->reservationService->releaseForVendor($vendor->id);

        $message = 'Your vendor account has been suspended by platform administration.';
        VendorStatusUpdated::dispatch($vendor, 'suspended', $message);

        return back()->with('success', "Vendor {$vendor->name} has been suspended. {$released} active reservation(s) were released.");
    }

    public function reactivate(User $vendor): RedirectResponse
    {
        if (! $vendor->isVendor()) {
            abort(400);
        }

        $vendor->update(['status' => UserStatus::Approved]);

        $message = 'Your vendor account has been reactivated successfully.';
        VendorStatusUpdated::dispatch($vendor, 'reactivated', $message);

        return back()->with('success', "Vendor {$vendor->name} has been reactivated successfully.");
    }
}
