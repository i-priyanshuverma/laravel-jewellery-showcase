<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\StockReservation;
use App\Services\StockReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class ReservationController extends Controller
{
    public function __construct(protected StockReservationService $reservationService) {}

    protected function getSessionId(Request $request): string
    {
        if ($request->session()->has('reservation_session_id')) {
            return (string) $request->session()->get('reservation_session_id');
        }

        $id = $request->session()->getId();
        $request->session()->put('reservation_session_id', $id);

        return $id;
    }

    public function index(Request $request): View|RedirectResponse
    {
        if (auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isVendor())) {
            return redirect()->route('dashboard');
        }

        $sessionId = $this->getSessionId($request);
        $reservations = StockReservation::where('session_id', $sessionId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with([
                'variant.product.category',
                'variant.product.vendor.vendorProfile',
                'variant.product.images',
                'variant.stones.stoneType',
            ])
            ->orderBy('expires_at', 'asc')
            ->get();

        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_variant_id' => ['required', 'exists:product_variants,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'idempotency_key' => ['required', 'string', 'max:255'],
        ]);

        $variant = ProductVariant::findOrFail($request->product_variant_id);
        $sessionId = $this->getSessionId($request);

        try {
            $reservation = $this->reservationService->reserve(
                $variant,
                (int) $request->quantity,
                $sessionId,
                $request->idempotency_key
            );

            return back()->with('success', 'Stock reserved for 15 minutes! Check the Active Holds in the top bar.');
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, StockReservation $reservation): RedirectResponse
    {
        $sessionId = $this->getSessionId($request);
        if ($reservation->session_id !== $sessionId) {
            return back()->with('error', 'Unauthorized reservation access.');
        }

        $released = $this->reservationService->releaseReservation($reservation);

        if ($released) {
            return back()->with('success', 'Stock hold released successfully.');
        }

        return back()->with('error', 'Reservation expired or already released.');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        $sessionId = $this->getSessionId($request);
        $activeReservations = StockReservation::where('session_id', $sessionId)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->get();

        $count = 0;
        foreach ($activeReservations as $res) {
            if ($this->reservationService->releaseReservation($res)) {
                $count++;
            }
        }

        return redirect()->route('products.index')
            ->with('success', "Released {$count} active stock reservation(s).");
    }
}
