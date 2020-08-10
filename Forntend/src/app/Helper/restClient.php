<?php

use Illuminate\Http\Request;
use App\Helper\ApiConfig;
use Illuminate\Support\Facades\Http;

function login(Request $request)
{
    $api = new ApiConfig('127.0.0.1', '8000', 'v1');

    $body = [
        'email' => $request->email,
        'password' => $request->password,
    ];

    $url = $api->getPrefixUrl() . 'login';
    $response = Http::withHeaders([
        'Accept' => 'application/json'
    ])->post($url, $body);

    return userReturn($response,200);
}

function register(Request $request){
    $api = new ApiConfig('127.0.0.1', '8000', 'v1');
    $body = [
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => $request->password,
        'password_confirmation' => $request['password_confirmation'],
    ];
    $url = $api->getPrefixUrl() . 'register';

    $response = Http::withHeaders([
        'Accept' => 'application/json'
    ])->post($url, $body);

   return userReturn($response,201);
}

function logout(){
    $api = new ApiConfig('127.0.0.1', '8000', 'v1');
    $url = $api->getPrefixUrl() . 'logout';
    $token_type = session()->get('token_type');


    $access_token = session()->get('access_token');

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => $token_type . ' ' . $access_token
    ])->post($url, []);

    session()->forget('token_type');
    session()->forget('access_token');
    session()->forget('role');

    if (isset($response['isLogin']) && $response['isLogin'] == false && $response->status() == 200) {
        return($response);
    }

    return([
        'errors' => $response['errors']
    ]);
}

function getUserInfo()
{
    $api = new ApiConfig('127.0.0.1', '8000', 'v1');
    $url = $api->getPrefixUrl() . 'user';

    $token_type = session()->get('token_type');
    $access_token = session()->get('access_token');

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => $token_type . ' ' . $access_token
    ])->post($url, []);

    if (isset($response['isLogin']) && $response['isLogin'] == true && $response->status() == 200) {
        session()->put('role',$response['user']['role']);
        return([
            'token_type' => $token_type,
            'access_token' => $access_token,
            'user'=>$response['user'],
            'isLogin' => true,
        ]);
    }

    session()->forget('token_type');
    session()->forget('access_token');
    session()->forget('role');

    return([
        'isLogin' => false,
        'errors' => $response['message']
    ]);

}

function userReturn($response,$status){
    if (isset($response['isLogin']) && $response['isLogin'] == true && $response->status() == $status) {
        session()->put('access_token', $response['access_token']);
        session()->put('token_type', $response['token_type']);
        session()->put('role', $response['user']['role']);
        return [
            'user' => $response['user'],
            'access_token' => $response['access_token'],
            'token_type' => $response['token_type'],
            'role' => $response['user']['role'],
            'isLogin' => true
        ];
    } else
        return [
            'isLogin' => false,
            'errors' => $response['errors']
        ];
}

function getAllcitiesForAdmin(){
    $api = new ApiConfig('127.0.0.1', '8000', 'v1');
    $token_type = session()->get('token_type');
    $access_token = session()->get('access_token');

    $url = $api->getPrefixUrl() . 'admin/cities';

    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Authorization' => $token_type . ' ' . $access_token
    ])->get($url);

    if ($response->status()==200){
        return $response;
    }
    return([
        'errors' => $response['message']
    ]);
}
