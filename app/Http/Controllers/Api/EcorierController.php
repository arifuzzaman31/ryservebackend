<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Xenon\MultiCourier\Provider\ECourier;
use Xenon\MultiCourier\Courier;
use Illuminate\Http\Request;

class EcorierController extends Controller
{
    protected $courier;
    public function __construct()
    {
        $cour = Courier::getInstance();
        $cour->setProvider(ECourier::class, 'local'); /* local/production */
        $cour->setConfig([
            'API-KEY' => env('ECOURIER_API_KEY'),
            'API-SECRET' => env('ECOURIER_API_SECRET'),
            'USER-ID' => env('ECOURIER_USER_ID')
        ]);
        return $this->courier = $cour;
    }

    public function getEcoPackage()
    {
        return response()->json($this->courier->getPackages());
    }
}
