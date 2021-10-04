<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\{LoginRequest, User};
Use Illuminate\Support\Str;
use View;
use App\Traits\SendMailTrait;

class RequestController extends Controller
{
    use SendMailTrait;
    /*
    Method Name:    getList
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To get list of all requests
    Params:         
    */
    public function getList(Request $request){

        $data = LoginRequest::when($request->search_keyword, function($q) use($request){
            $q->where('first_name', 'like', '%'.$request->search_keyword.'%')
            ->orWhere('last_name', 'like', '%'.$request->search_keyword.'%')
            ->orWhere('email', 'like', '%'.$request->search_keyword.'%');
        })->with('user')->where('status', 0)->paginate(15);
        return view('admin.request.list', compact('data'));
    }
    /* End Method getList */
    
    /*
    Method Name:    change_status
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To change the status of request[approved] and send request to user
    Params:         
    */
    public function updateRequest(Request $request){
        try{
            $validator = Validator::make($request->all() , ['expiry' => 'required|numeric' ]);
            if ($validator->fails())
            {
                if ($request->ajax())
                {
                    return response()
                        ->json(["success" => false, "errors" => $validator->getMessageBag()
                        ->toArray() ], 200);
                }
            }
            $loginRequest = LoginRequest::find($request->id);
            $uniqueId = strtoupper(Str::random(16));
            $token = 'http://localhost/shineDezign/portal/public/login?uid='.$loginRequest->user_id.'&token='.$uniqueId;
            $data = [
                'name' => $loginRequest->user->name,
                'token' => $token 
            ];
            $template = (string)View::make('emails.request', compact('data'));
            $result = $this->send_mail($loginRequest->user->email, 'You have got approval for login', $template);
            if($result){
                $loginRequest->status = 1;
                $loginRequest->save();
                $user = User::find($loginRequest->user_id);
                $user->api_token = $uniqueId;
                $user->expiry = $request->expiry;
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'Request status has been approved successfully']);
        }catch(Exception $ex){
            return response()->json(['success' => false, 'message' => 'Oops! something went wrong']);
        }
    }
    /* End Method change_status */
    
    /*
    Method Name:    change_status
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To change the status of request[reject]
    Params:         [id, status]
    */
    public function changeStatus(Request $request){
        $getData = $request->all();
        $user = LoginRequest::find($getData['id']);
        $user->status = $getData['status'];
        $user->save();
        return redirect()->back()->with('status', 'success')->with('message', 'Request status has been updated successfully');
    }
    /* End Method change_status */
}
