<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExchangeRateController extends Controller
{
    private $rate = 0;
    private $btc_rate_supplier = "Bittylicous";
    private $rub_rate_supplier = "Binance";
    
    public function index()
    {
        $this->rate = new ExchangeRate;
        $this->rate->btc_rate_supplier = $this->btc_rate_supplier;
        $this->rate->rub_rate_supplier = $this->rub_rate_supplier;
        
        try {
            $this->fetchRates();
            $this->rate->save();
        } catch (\Exception $e) {
            return null;
        }
        
    }
    
    public function markSuspiciousEntries()
{
    $yesterday = Carbon::now()->subDays(1);
    $dayBeforeYesterday = Carbon::now()->subDays(2);

    // Get all entries from between 12 and 24 hours ago
    $entries = ExchangeRate::whereBetween('created_at', [$dayBeforeYesterday, $yesterday])->get();

    // Calculate the mean btc_rate
    $mean = $entries->avg('spread');

    // Calculate the standard deviation of btc_rate
    $stdDev = sqrt($entries->sum(function ($entry) use ($mean) {
        return pow($entry->spread - $mean, 2);
    }) / $entries->count());

    // Mark as suspicious any entries where btc_rate > mean + stdDev
    $entries->filter(function ($entry) use ($mean, $stdDev) {
        return $entry->spread > $mean + $stdDev;
    })->each(function ($entry) {
        $entry->suspicious=1;
        $entry->save();
    });
    $entries->filter(function ($entry) use ($mean, $stdDev) {
        return $entry->spread < $mean - $stdDev;
    })->each(function ($entry) {
        $entry->suspicious=1;
        $entry->save();
    });

    // ...the rest of your controller method...
}
    
    public function crawlRubToBtc() {
        
    $html = file_get_contents('https://finex24.io/exchange-tinkoff-rub-to-btc');
    $dom = new \DOMDocument();
    @$dom->loadHTML($html); // Suppress warnings if any
    $value = "0";
    $xpath = new \DOMXPath($dom);
    $elements = $xpath->query("//span[contains(@class, 'js_course_html')]");
    if ($elements->length > 0) {
        $element = $elements->item(0);
        $value = $element->nodeValue;
        preg_match('/\d+/', $value, $matches);
        $value = (int) $matches[0];
    }
    // The div element with the specified ID was not found
    return $value;
    }
    
    public function crawlBtcToRub() {
        
    $html = file_get_contents('https://finex24.io/exchange-btc-to-tinkoff');
    $dom = new \DOMDocument();
    @$dom->loadHTML($html); // Suppress warnings if any
    $value = "0";
    $xpath = new \DOMXPath($dom);
    $elements = $xpath->query("//span[contains(@class, 'js_course_html')]");
    if ($elements->length > 0) {
        $element = $elements->item(0);
        $value = $element->nodeValue;
        $value = str_replace("1 BTC", "", $value);
        preg_match('/\d+/', $value, $matches);
        $value = (int) $matches[0];
    }
    // The div element with the specified ID was not found
    return $value;
    }
    
    public function fetchRates()
    {
        // Get supplier1 rates
        $toGBP = Http::get('https://bittylicious.com/api/v1/quote/BTC/GB/GBP/BANK/1/SELL?routes[]=ENUMIS&routes[]=&routes[]=CLEARJUNCTION');
        $toBTC = Http::get('https://bittylicious.com/api/v1/quote/BTC/GB/GBP/BANK/1/BUY?routes[]=ENUMIS&routes[]=&routes[]=CLEARJUNCTION');
        $toGBP = ($toGBP->json())["totalPrice"];
        $toBTC = ($toBTC->json())["totalPrice"];
        
        // Get supplier 2 rates
        $binane = Http::get('https://api.binance.com/api/v3/ticker/price?symbol=BTCRUB');
        $binanceRate = $binane->json()["price"];
        
        //Try crawling
        try {
            $attemptMarket = $this->crawlRubToBtc();
            $attemptMarket2 = $this->crawlBtcToRub();
            
            if(($attemptMarket>0)&&($attemptMarket2>0)) {
                $toBTC2 = $attemptMarket;
                $toRUB = $attemptMarket2;
                $this->rate->rub_rate_supplier = "finex";
            }
            else {
                $toRUB = $binanceRate;
                $toBTC2 = $binanceRate;
            }
        } catch (\Exception $e) {
            $toRUB = $binanceRate;
            $toBTC2 = $binanceRate;
        }
        
        //setting VARS
        
        $this->rate->rub_buy_rate = $toRUB;
        $this->rate->rub_sell_rate = $toBTC2;
        $this->rate->btc_buy_rate = $toBTC;
        $this->rate->btc_sell_rate = $toGBP;
        
        
        //from RUS to UK
        
        $this->rate->gbp_to_rub_rate = round($toRUB/$toBTC,2);
        $dTRus = $this->rate->gbp_to_rub_rate;
        
        //from UK to RUS
        
        $this->rate->rub_to_gbp_rate = round ($toBTC2/$toGBP,2);
        $dTUK = $this->rate->rub_to_gbp_rate;
        
        //spread
        
        $this->rate->spread = round (($this->rate->rub_to_gbp_rate-$this->rate->gbp_to_rub_rate)*100/$this->rate->rub_to_gbp_rate,2);
        $dSpread = $this->rate->spread;
        $tk = round(10000/$dTUK,2);
	$tk2 = round(10000/$dTRus,2);
	$tk3 = round(22040/$dTRus,2);
        $tkRtn = round($dTUK*100,2);
        $tkRtnk = round(100000/$dTUK);
        $pennyValue = round((round($dTUK,2)/100),2) - 0.01;
        $realRate = round(($dTUK+$dTRus)/2,2);
        $loss = $dTUK-$realRate;
        $lossPer10k = -(round((10000/$dTUK)-(10000/$realRate),0));
        //basic output to satisfy curiosity
        
        echo "<h2> $dTRus  : :   $dTUK</h2>";
        echo "<h3>Exchange Rates for sending money between UK and RUS by crypto";
        echo "<br>";
        echo "<p>To get 10,000 rub you'd have to send <br>> £$tk2</p>";
	echo "<p>To get 22,040 rub you'd have to send <br> £$tk3</p>";
	echo "<p>Opposite rate is $dTUK</p>";
        echo "<p>If you wanted to top up your British account with £100 you'd have to send $tkRtn rub.</p>";
        echo "<p>100,000 rubles can get you £$tkRtnk. If you wanted to top up your British account with £100 you'd have to send $tkRtn rub.</p>";
        echo "<br>";
        echo "<h2>This implies $dSpread% spread and a loss of £$lossPer10k for every 10,000 rub converted</h2>";
        echo "<h3>Fun Fact. $pennyValue rub is so little that even 1p coin you find on the floor is worth more than that";
    }
  
}
