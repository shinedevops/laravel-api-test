<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, Session;

class SiteController extends Controller
{
    public function login(Request $request) {        
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'http://localhost/shineDezign/portal-app/public/api/v1/login-check?uid='.$request->uid.'&token='.$request->token,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response);
        if(property_exists($response, 'api_response') && $response->api_response == 'success'){
            $user = $response->data;
            Session::put(['userDetails' => $user]);
            return redirect()->route('profile.update')->with('status', 'success')->with('message', 'Welcome Back '.ucfirst($user->name));
        } 
        else{
            return redirect()->route('home')->with('status', 'error')->with('message', 'Sorry! Session timeout error');
        }
    }

    public function profileUpdate(Request $request) {  
        $user = Session::get('userDetails');

        return view('update-profile', compact('user'));
    }
}
