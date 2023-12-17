<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MarketController extends Controller
{
    // Hardcoded sample prices
    private $prices = [
        ['symbol' => 'EURUSD', 'bid' => 1.07821, 'ask' => 1.07851],
        ['symbol' => 'AUDUSD', 'bid' => 0.66037, 'ask' => 0.66067],
        ['symbol' => 'EURGBP', 'bid' => 0.85704, 'ask' => 0.85734],
        ['symbol' => 'GBPUSD', 'bid' => 1.25785, 'ask' => 1.25815],
    ];

    public function lastUpdate()
    {
        return response()->json([
            'last_update' => Carbon::now()->toTimeString(),
            'prices' => $this->generateRandomPrices(),
        ]);
    }

    private function generateRandomPrices()
    {
        // Simulate price changes
        return collect($this->prices)->map(function ($price) {
            // Ensure that there are exactly 5 symbols after the decimal
            $price['bid'] = number_format($price['bid'] + (rand(-5, 5) / 10000), 5, '.', '');
            $price['ask'] = number_format($price['ask'] + (rand(-5, 5) / 10000), 5, '.', '');

            // Ensure bid and ask are not negative
            $price['bid'] = max(0, $price['bid']);
            $price['ask'] = max(0, $price['ask']);

            return $price;
        });
    }
}
