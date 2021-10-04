<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class UserManageController extends Controller
{
    function __construct()
    {
    
    }

    /*
    Method Name:    getList
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To get list of all users
    Params:         
    */
    public function getList(Request $request){

        $data = User::when($request->search_keyword, function($q) use($request){
            $q->where('name', 'like', '%'.$request->search_keyword.'%')
            ->orWhere('email', 'like', '%'.$request->search_keyword.'%');
        })->where('role', 2)->paginate(15);
        return view('admin.users.list', compact('data'));
    }
    /* End Method getList */
    
    /*
    Method Name:    add_form
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Form to add new user
    Params:         
    */
    public function add_form(){
        return view('admin.users.add');
    }
    /* End Method add_form */
    
    /*
    Method Name:    create_record
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Save data into database to add new user
    Params:         [name, last_name, role, email, mobile, status]
    */
    public function create_record(Request $request){
		$request->validate([
			'name' => 'required|string', 
			'email' => 'required|email|unique:users',
			'status' => 'required', 
        ]);
    	try {
             $postData = $request->all();
        	$data = array(
				'name' => $request->name,
				'email' => $request->email,
				'status' => $request->status,
            );
            $user = User::create($data);
            $routes = ($request->action == 'saveadd') ? 'user.add' : 'users.list';
        	return redirect()->route($routes)->with('status', 'success')->with('message', 'User has been created successfully');
        } catch ( \Exception $e ) {
            return redirect()->back()->with('status', 'error')->with('message', $e->getMessage());
        }
    }
    /* End Method create_record */
    
    /*
    Method Name:    edit_form
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        Edit form to update any user
    Params:         [id]
    */
    public function edit_form($id){
        $record = User::find($id);
    	return view('admin.users.edit', compact('record'));
    }
    /* End Method edit_form */
    
    /*
    Method Name:    update_record
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        update data into database of user
    Params:         [edit_record_id, first_name, last_name, role, email, mobile, status]
    */
    public function update_record(Request $request){
        $postData = $request->all();
		$id =$postData['edit_record_id'];
		$request->validate([
			'name' => 'required|string', 
			'email' => 'required|email|unique:users,email,'.$id,
            'status' => 'required'
        ]);
    	try {
            $users = User::findOrFail($id);
			$users->name = $postData['name'];
			$users->email = $postData['email'];
        	$users->status = $postData['status'];
            $users->push();   	
        	return redirect()->route('users.list')->with('status', 'success')->with('message', 'User details has been updated successfully');        	
        } catch ( \Exception $e ) {
            return redirect()->back()->with('status', 'error')->with('message', $e->getMessage());
        }
    }
    /* End Method update_record */
    
    /*
    Method Name:    change_password
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        form to update password
    Params:         [id]
    */	
    public function change_password($id){
    	return view('admin.users.password', compact('id'));
    }
    /* End Method change_password */
    
    /*
    Method Name:    update_password
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To update password for any user by id
    Params:         [edit_record_id, password]
    */	
    public function update_password(Request $request){
        $postData = $request->all();
		$id =$postData['edit_record_id'];
		$request->validate([
			'password' => 'required_with:password_confirmation|string|confirmed', 
        ], [
            'password.required' => 'Password is required',
            'password.confirmed' => 'Confirmed Password not matched with password'
        ]);
    	try {
        	$data = array(
        		'password' => bcrypt($postData['password']),
        		'updated_at' => date('Y-m-d H:i:s')
        	);        	
			$record = User::where('id', $id)->update($data);
        	return redirect()->route('users.list')->with('status', 'success')->with('message', 'User password has been updated successfully');
        	
        } catch ( \Exception $e ) {
            return redirect()->back()->with('status', 'error')->with('message', $e->getMessage());
        }
    }
    /* End Method update_password */
    
    /*
    Method Name:    del_record
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To delete any user by id
    Params:         [id]
    */	
    public function del_record($id){
        try {
            User::where('id',$id)->delete();
        	return redirect()->back()->with('status', 'success')->with('message', 'User details has been deleted successfully');
        }catch(Exception $ex){
            return redirect()->back()->with('status', 'error')->with('message', $ex->getMessage());
        }
    }
    /* End Method del_record */
    
    /*
    Method Name:    change_status
    Developer:      Shine Dezign
    Created Date:   2021-10-01 (yyyy-mm-dd)
    Purpose:        To change the status of user[active/inactive]
    Params:         [id, status]
    */
    public function change_status(Request $request){
        $getData = $request->all();
        $user = User::find($getData['id']);
        $user->status = $getData['status'];
        $user->save();
        return redirect()->back()->with('status', 'success')->with('message', 'User status has been updated successfully');
    }
    /* End Method change_status */
}