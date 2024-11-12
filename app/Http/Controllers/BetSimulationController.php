<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BetSimulationController extends Controller
{
    public function simulate(Request $request)
        {
            $startingAmount = $request->query('starting_amount') ?? 10000;
            $baseBet = $request->query('base_bet') ?? 15;
            $goalAmount = $request->query('goal_amount') ?? 500000;
            
            if (is_null($startingAmount) || is_null($baseBet) || is_null($goalAmount)) {
                return Response::json(['error' => 'Missing parameters'], 400);
            }
        
            $startingAmount = floatval($startingAmount);
            $baseBet = floatval($baseBet);
            $goalAmount = floatval($goalAmount);
        
            $results = $this->runSimulation($startingAmount, $baseBet, $goalAmount);
        
            return view('simulation_result', ['results' => $results['results']]);
        }

    private function runSimulation($startingAmount, $baseBet, $goalAmount)
    {
        $iterations = 100;
        $allResults = [];
        $stats = ['wins' => 0, 'losses' => 0];

        for ($i = 0; $i < $iterations; $i++) {
            $balance = $startingAmount;
            $bet = $baseBet;
            $balanceHistory = [];

            while ($balance > 0 && $balance < $goalAmount) {
                $balanceHistory[] = $balance;
                $spinResult = $this->spinRoulette();

                if ($spinResult) {
                    $balance += $bet;
                    $bet = $baseBet;
                } else {
                    $balance -= $bet;
                    $bet *= 2;
                }
            }

            if ($balance >= $goalAmount) {
                $stats['wins']++;
            } else {
                $stats['losses']++;
            }

            $allResults[] = $balanceHistory;
        }

        return [
            'results' => $allResults,
            'stats' => $stats
        ];
    }

    private function spinRoulette()
    {
        // Simulate a roulette spin (red wins 48.65% of the time, for simplicity we use 50%)
        return rand(0, 1) === 1;
    }
}
