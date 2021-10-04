<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator, Session ;
use App\Models\{User, LoginRequest}; 

class UserController extends Controller
{
     /*
    API Method Name: loginRequest
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        return that email is authorized or not and update request
    Remarks:        
    */  
    public function loginRequest(Request $request){ 
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            return response()->json(['api_response' => 'error', 'status' => 422, 'message' => 'Validation message', 'data' => $validator->errors()->all()]);
        }
        try {
            $user = User::where(['email' => $request->email, 'role' => 2])->first();  
            if($user && 1 != $user->status) {
                return response()->json(['api_response' => 'error', 'status' => 200, 'message' => 'Your account has been suspended. Contact to administrator', 'data' => null]);
            }
            elseif($user && 1 == $user->status) {
                LoginRequest::create(['user_id' => $user->id]);
                return response()->json(['api_response' => 'success', 'status' => 200, 'message' => 'Request has been updated successfully. Wait for administrator approval', 'data' => null]);
            }
            return response()->json(['api_response' => 'error', 'status' => 200, 'message' => 'Sorry! email not found', 'data' => 'Oops! something went wrong. try again']);
        } catch ( \Exception $e ) {
            return response()->json(['api_response' => 'error', 'status' => 404, 'message' => 'Error message', 'data' => 'Oops! something went wrong. try again'], 400);
        }
    }  
    /* End Method login */
     /*
    API Method Name: loginCheck
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        chekc that uid and token is authorized or not and send response accordingly
    Remarks:        
    */  
    public function loginCheck(Request $request){ 
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['api_response' => 'error', 'status' => 422, 'message' => 'Validation message', 'data' => $validator->errors()->all()]);
        }
        try {
            $user = User::where(['uid' => $request->uid, 'api_token' => $request->token, 'role' => 2])->first();
            $start = strtotime($user->updated_at);
            $end = strtotime(date('Y-m-d H:s:i'));
            $mins = ($end - $start) / 60;
            if($user && 1 != $user->status) {
                return response()->json(['api_response' => 'error', 'status' => 200, 'message' => 'Your account has been suspended. Contact to administrator', 'data' => null]);
            }
            elseif($user && 1 == $user->status &&  $user->expiry > $mins) {
                return response()->json(['api_response' => 'success', 'status' => 200, 'message' => 'User fetched', 'data' => $user]);
            }
            return response()->json(['api_response' => 'error', 'status' => 200, 'message' => 'Oops! something went wrong. try again', 'data' => 'Oops! something went wrong. try again']);
        } catch ( \Exception $e ) {
            return response()->json(['api_response' => 'error', 'status' => 404, 'message' => 'Oops! something went wrong. try again', 'data' => 'Oops! something went wrong. try again'], 400);
        }
    }  
    /* End Method loginCheck */
     /*
    API Method Name: updateDetails
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Update email of authorized user
    Remarks:        
    */  
    public function updateDetails(Request $request){ 
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,'.$request->id,
            'uid' => 'required',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['api_response' => 'error', 'status' => 422, 'message' => 'Validation message', 'data' => $validator->errors()->all()]);
        }
        try {
            $user = User::find($request->id);
            $start = strtotime($user->updated_at);
            $end = strtotime(date('Y-m-d H:s:i'));
            $mins = ($end - $start) / 60;
            if($request->uid == $user->uid && $request->token == $user->api_token &&  $user->expiry > $mins) {
                $user->email = $request->email;
                $user->save();
                return response()->json(['api_response' => 'success', 'status' => 200, 'message' => 'User Details updated', 'data' => $user]);
            }
            return response()->json(['api_response' => 'error', 'status' => 200, 'message' => 'Oops! something went wrong. try again', 'data' => 'Oops! something went wrong. try again']);
        } catch ( \Exception $e ) {
            return response()->json(['api_response' => 'error', 'status' => 404, 'message' => 'Oops! something went wrong. try again', 'data' => 'Oops! something went wrong. try again'], 400);
        }
    }  
    /* End Method updateDetails */
}
