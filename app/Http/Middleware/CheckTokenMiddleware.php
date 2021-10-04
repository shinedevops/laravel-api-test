<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(session()->has('userDetails')){
            $userSession = Session::get('userDetails');
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://localhost/shineDezign/portal-app/public/api/v1/login-check?uid='.$userSession->uid.'&token='.$userSession->api_token,
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
                return $next($request);
            } 
            else{
                Auth::logout();
                return redirect()->route('home');
            }
        }
        else {
            Auth::logout();
            return redirect()->route('home');
        }
    }
}
