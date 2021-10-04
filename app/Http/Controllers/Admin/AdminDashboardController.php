<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use DB;
use Session;
use App\Models\User;
use App\PasswordReset;
use Illuminate\Support\Str;
use App\UserDetails;

class AdminDashboardController extends Controller
{
    public function __construct()
    {

    }

    /*
    Method Name:    index
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To display dashboard for admin after login
    Params:         []
    */
    public function index()
    {
        $user_count = User::count();
        return view('admin.home', compact('user_count'));
    }
    /* End Method index */

    /*
    Method Name:    login_check
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Admin login credentials check
    Params:         [email, password]
    */
    public function login_check(Request $request)
    {
        $input = $request->all();

        $this->validate($request, ['email' => 'required|email', 'password' => 'required']);

        $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if (auth()
            ->attempt(array(
            $fieldType => $input['email'],
            'password' => $input['password']
        )))
        {
            return redirect()->route('admindashboard');
        }
        else
        {
            return redirect()
                ->route('home')
                ->with('status', 'Error')
                ->with('message', Config::get('constants.ERROR.WRONG_CREDENTIAL'));
        }
    }
    /* End Method login_check */

    /*
    Method Name:    logout
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Logout user
    Params:         
    */
    public function logout(){
		Auth::logout();
        return redirect()->to('/');
    }
    /* End Method logout */

    /*
    Method Name:    password_reset
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Form for forgot password
    Params:         
    */
    public function password_reset()
    {
        return view('admin.passwordreset');
    }
    /* End Method password_reset */

    /*
    Method Name:    password_reset_link
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Send reset password link email if admin email exist
    Params:         [email]
    */
    public function password_reset_link(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $roles = ['User', 'Company'];
        $adminRoles = [];
        foreach ($this->getAdminRoles($roles) as $row)
        {
            $adminRoles[] = $row->name;
        }
        $user = User::role($adminRoles)->where('email', $request->email)
            ->first();
        $template = $this->get_template_by_name('FORGOT_PASSWORD');

        if (!$user) return redirect()->back()
            ->with('status', 'Error')
            ->with('message', Config::get('constants.ERROR.WRONG_CREDENTIAL'));
        $passwordReset = PasswordReset::updateOrCreate(['email' => $user->email], ['email' => $user->email, 'token' => Str::random(12) ]);

        $link = route('tokencheck', $passwordReset->token);
        $string_to_replace = array(
            '{{$name}}',
            '{{$token}}'
        );
        $string_replace_with = array(
            'Admin',
            $link
        );
        $newval = str_replace($string_to_replace, $string_replace_with, $template->template);

        $logId = $this->email_log_create($user->email, $template->id, 'FORGOT_PASSWORD');
        $result = $this->send_mail($user->email, $template->subject, $newval);
        if ($result)
        {

            $this->email_log_update($logId);
            return redirect()->route('resetpassword')
                ->with('status', 'Success')
                ->with('message', Config::get('constants.SUCCESS.RESET_LINK_MAIL'));
        }
        else {
            return redirect()
            ->route('resetpassword')
            ->with('status', 'Error')
            ->with('message', Config::get('constants.ERROR.OOPS_ERROR'));
        }
    }
    /* End Method password_reset_link */

    /*
    Method Name:    password_reset_token_check
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Checked reset access token
    Params:         [token]
    */
    public function password_reset_token_check($token)
    {
        $passwordReset = PasswordReset::where('token', $token)->first();
        if (!$passwordReset) return redirect()->route('resetpassword')
            ->with('status', 'Error')
            ->with('message', Config::get('constants.ERROR.TOKEN_INVALID'));

        if (Carbon::parse($passwordReset->updated_at)
            ->addMinutes(240)
            ->isPast())
        {
            $passwordReset->delete();
            return redirect()
                ->route('resetpassword')
                ->with('status', 'Error')
                ->with('message', Config::get('constants.ERROR.TOKEN_INVALID'));
        }
        Session::put('forgotemail', $passwordReset->email);
        return redirect()
            ->route('setnewpassword')
            ->with('status', 'Success')
            ->with('message', 'Set your new password');
    }
    /* End Method password_reset_token_check */

    /*
    Method Name:    new_password_set
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Form to set new password after reset password
    Params:         
    */
    public function new_password_set()
    {
        if (Session::has('forgotemail')) return view('admin.setnewpassword');
        else return redirect()
            ->route('resetpassword')
            ->with('status', 'Error')
            ->with('message', Config::get('constants.ERROR.OOPS_ERROR'));
    }
    /* End Method new_password_set */

    /*
    Method Name:    update_new_password
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To update new password after reset pasword
    Params:         [password]
    */
    public function update_new_password(Request $request)
    {

        if (!Session::has('forgotemail')) return redirect()->route('resetpassword')
            ->with('status', 'Error')
            ->with('message', Config::get('constants.ERROR.OOPS_ERROR'));
        $email = Session::get('forgotemail');
        $request->validate(['password' => 'required_with:password_confirmation|string|confirmed', ], ['password.required' => 'Password is required', 'password.confirmed' => 'Confirmed Password not matched with password']);
        try
        {
            $data = array(
                'password' => bcrypt($request->password) ,
                'updated_at' => date('Y-m-d H:i:s')
            );
            $record = User::where('email', $email)->update($data);
            PasswordReset::where('email', $email)->delete();
            Session::forget('forgotemail');
            return redirect()
                ->route('admin')
                ->with('status', 'success')
                ->with('message', 'Your password ' . Config::get('constants.SUCCESS.UPDATE_DONE'));

        }
        catch(\Exception $e)
        {
            return redirect()->back()
                ->with('status', 'error')
                ->with('message', $e->getMessage());
        }

    }
    /* End Method update_new_password */

    /*
    Method Name:    update_record
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To update admin details
    Params:         [adminemail, first_name, last_name, profile_pic]
    */
    public function update_record(Request $request)
    {
        $validator = Validator::make($request->all() , ['adminemail' => 'required|email|unique:users,email,' . Auth::user()->id, 'name' => 'required' ], ['name.required' => 'Name cannot be empty', ]);
        if ($validator->fails())
        {
            if ($request->ajax())
            {
                return response()
                    ->json(["success" => false, "errors" => $validator->getMessageBag()
                    ->toArray() ], 200);
            }
        }
        try
        {
            $postData = $request->all();
            $data = array(
                'email' => $postData['adminemail'],
                'name' => $postData['name'],
                'updated_at' => date('Y-m-d H:i:s')
            );
            $record = User::where('id', Auth::user()->id)
                ->update($data);
            if ($record > 0)
            {
                return response()->json(["success" => true, "msg" => "User details has been updated successfully" ], 200);
            }
            return response()
                    ->json(["success" => false, "msg" => 'Oops! something went wrong' ], 200);
        }
        catch(\Exception $e)
        {
            throw $e;
            return response()->json(["success" => false, "msg" => $e], 200);
        }
    }
    /* End Method update_record */

    /*
    Method Name:    update_password
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To update admin password
    Params:         [oldpassword, newpassword]
    */
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all() , ['oldpassword' => 'required', 'newpassword' => 'required', ], ['oldpassword.required' => 'Old Password cannot be empty', 'newpassword.required' => 'New Password cannot be empty', ]);
        if ($validator->fails())
        {
            if ($request->ajax())
            {
                return response()
                    ->json(["success" => false, "errors" => $validator->getMessageBag()
                    ->toArray() ], 200);
            }
        }
        $hashedPassword = Auth::user()->password;
        if (\Hash::check($request->oldpassword, $hashedPassword))
        {
            if (!\Hash::check($request->newpassword, $hashedPassword))
            {
                $users = User::find(Auth::user()->id);
                $users->password = bcrypt($request->newpassword);
                $users->save();
                return response()
                    ->json(["success" => true, "msg" => "User password updated Successfully"], 200);
            }
            else
            {
                return response()
                    ->json(["success" => false, "msg" => "new password can not be the old password!"], 200);
            }
        }
        else
        {
            return response()
                ->json(["success" => false, "msg" => 'old password doesnt matched'], 200);
        }

    }
    /* End Method update_password */
}