<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\PricePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the subscriptions.
     */
    public function index()
    {
        $subscriptions = Subscription::with('pricePlan')->paginate(15);
        return response()->json($subscriptions);
    }

    /**
     * Store a newly created subscription in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'price_plan_id' => 'required|exists:price_plans,id',
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'amount_paid' => 'required|numeric',
            'payment_reference' => 'nullable|string',
            'parameters' => 'nullable|json',
        ]);

        $subscription = Subscription::create($validated);
        return response()->json($subscription, 201);
    }

    /**
     * Display the specified subscription.
     */
    public function show(Subscription $subscription)
    {
        $subscription->load('pricePlan');
        return response()->json($subscription);
    }

    /**
     * Update the specified subscription in storage.
     */
    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'price_plan_id' => 'sometimes|exists:price_plans,id',
            'entity_type' => 'sometimes|string',
            'entity_id' => 'sometimes|integer',
            'starts_at' => 'sometimes|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'amount_paid' => 'sometimes|numeric',
            'payment_reference' => 'nullable|string',
            'parameters' => 'nullable|json',
            'is_cancelled' => 'sometimes|boolean',
            'is_paused' => 'sometimes|boolean',
            'cancellation_reason' => 'nullable|string',
        ]);

        $subscription->update($validated);
        return response()->json($subscription);
    }

    /**
     * Remove the specified subscription from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response()->json(null, 204);
    }

    /**
     * Cancel the specified subscription.
     */
    public function cancel(Request $request, Subscription $subscription)
    {
        $reason = $request->input('reason');
        $subscription->cancel($reason);
        return response()->json($subscription);
    }

    /**
     * Pause the specified subscription.
     */
    public function pause(Subscription $subscription)
    {
        $subscription->pause();
        return response()->json($subscription);
    }

    /**
     * Resume the specified subscription.
     */
    public function resume(Subscription $subscription)
    {
        $subscription->resume();
        return response()->json($subscription);
    }

    /**
     * Renew the specified subscription.
     */
    public function renew(Request $request, Subscription $subscription)
    {
        $days = $request->validate(['days' => 'required|integer'])['days'];
        $subscription->renew($days);
        return response()->json($subscription);
    }

    /**
     * Get active subscriptions for a specific entity.
     */
    public function getActiveForEntity(Request $request)
    {
        $validated = $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
        ]);

        $subscriptions = Subscription::where($validated)
            ->active()
            ->with('pricePlan')
            ->get();

        return response()->json($subscriptions);
    }

    /**
     * Create a new subscription for an entity.
     */
    public function createForEntity(Request $request)
    {
        $validated = $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|integer',
            'price_plan_id' => 'required|exists:price_plans,id',
            'payment_reference' => 'required|string',
            'parameters' => 'nullable|json',
        ]);

        $pricePlan = PricePlan::findOrFail($validated['price_plan_id']);

        return DB::transaction(function () use ($validated, $pricePlan) {
            $subscription = Subscription::create([
                'entity_type' => $validated['entity_type'],
                'entity_id' => $validated['entity_id'],
                'price_plan_id' => $pricePlan->id,
                'starts_at' => now(),
                'ends_at' => now()->addDays($pricePlan->validity_days),
                'amount_paid' => $pricePlan->price,
                'payment_reference' => $validated['payment_reference'],
                'parameters' => $validated['parameters'] ?? null,
            ]);

            $pricePlan->incrementUses();

            return response()->json($subscription, 201);
        });
    }
}