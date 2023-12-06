<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        /*$location = new \Stevebauman\Location\Location();

$record = $location->get(request()->getClientIp());*/

            $userIp = request()->ip();
            $locationData = Location::get($userIp);
            
            dd($locationData->countryName);  
    }
}
