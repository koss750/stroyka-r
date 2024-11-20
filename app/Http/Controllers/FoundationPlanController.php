<?php

namespace App\Http\Controllers;

use App\Models\FoundationPlan;
use Illuminate\Http\Request;

class FoundationPlanController extends Controller
{
    public function index()
    {
        $foundationTypes = [
            'fLenta' => 'Ленточный',
            'fVinta' => 'Винтовой / сваи',
            'fMono' => 'Монолитный',
        ];
        
        $scales = [
            '1:50' => '1:50',
            '1:100' => '1:100',
            '1:200' => '1:200',
        ];
        
        $widths = [
            300 => '300мм',
            400 => '400мм',
            500 => '500мм',
            600 => '600мм',
        ];

        $userPlans = FoundationPlan::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();
        
        return view('foundation.plan', compact('foundationTypes', 'scales', 'widths', 'userPlans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'scale' => 'required|string',
            'width' => 'required|numeric',
            'drawing_data' => 'required|json',
            'total_area' => 'required|numeric',
            'perimeter' => 'required|numeric',
            'angles' => 'required|integer',
            't_junctions' => 'required|integer',
            'x_crossings' => 'required|integer',
        ]);

        try {
            $foundationPlan = FoundationPlan::create([
                'user_id' => auth()->id(),
                ...$validated
            ]);

            return response()->json([
                'success' => true,
                'message' => 'План фундамента успешно сохранен',
                'plan_id' => $foundationPlan->id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving foundation plan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при сохранении плана'
            ], 500);
        }
    }

    public function show(FoundationPlan $foundationPlan)
    {
        if ($foundationPlan->user_id !== auth()->id()) {
            abort(403);
        }

        return response()->json($foundationPlan);
    }

    public function destroy(FoundationPlan $foundationPlan)
    {
        if ($foundationPlan->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $foundationPlan->delete();
            return response()->json([
                'success' => true,
                'message' => 'План фундамента успешно удален'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error deleting foundation plan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при удалении плана'
            ], 500);
        }
    }
}