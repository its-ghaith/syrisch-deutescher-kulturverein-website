<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

include_once(app_path() . '/Helper/restClient.php');

class PageController extends Controller
{
    function dashboard(Request $request)
    {
        return view('layouts.admin.dashboard');
    }

    function showAllCities(Request $request)
    {
        $response= getAllcitiesForAdmin();
        return view('layouts.admin.time.showAllCities',compact('response'));
    }
}
