<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

include_once(app_path() . '/Helper/restClient.php');

class UserController extends Controller
{
    function login(Request $request)
    {
        $response = login($request);

        if ($response['isLogin']) {
            $role = session()->get('role');
            $ulr = '';
            if ($role == 'admin') {
                $ulr = 'admin/dashboard';
            }
            return redirect('/'.$ulr)->with('success', $response);
        }
        return redirect('/login')->withErrors($response['errors']);
    }

    function register(Request $request)
    {
        $response = register($request);
        if ($response['isLogin']) {
            return redirect('/')->with('success', $response);
        }
        return redirect('/register')->withErrors($response['errors']);
    }

    function logout(Request $request)
    {
        $response = logout();
        if (!$response['isLogin']) {
            return redirect('/')->with('success', $response['successfully_message']);
        }
        return back()->withErrors($response['errors']);
    }

    function getUserInfo(Request $request)
    {
        $response = getUserInfo();
        if ($response['isLogin']) {
            return redirect('/')->with('success', $response);
        }
        return redirect('/login');
    }
}
