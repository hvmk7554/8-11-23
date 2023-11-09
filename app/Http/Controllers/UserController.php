<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\UserRoleM;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMail;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles= UserRoleM::where('status',1)->select('id','name')->get();
         $users = DB::table('users')
           ->join('role_tbl','users.idRole','=','role_tbl.id')
           ->select('users.*','role_tbl.name as rolename')
           ->get();
  //   dd($users);
return view('users.users',compact('roles','users'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {      
       
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'idRole' => 'required|exists:role_tbl,id',
        ],[
           'name.required' => 'missing name',
           'email.required' => 'no email',
           'email.email' => 'invalid email',
           'email.unique' => 'existed email',
           'idRole.required' => 'invalid idRole x',
           'idRole.exists' => 'not existed idrole'
        ]);
        if ($validator->fails()) {
            return response()->json(['check'=>false,'msg'=>$validator->errors()]);
        }
        $password=random_int(10000,99999);
        $hash = Hash::make($password);
        User::create(['name' => $request->name, 'email' => $request->email, 'idRole'=>$request->idRole, 'password'=>$hash]);
        $mailData=[
            'name' => $request->name,
            'email' => $request->email,
            'password'=>$password
        ];
        Mail::to($request->email())->send(new UserMail($mailData));
        return response()->json(['check'=>true]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateUSRole(Request $request)
    {
    $validator = Validator::make($request->all(), [ 
        'id' => 'required|exists:users,id',
        'role' => 'required|exists:role_tbl,id',
    ],[
    'id.required'=>'Thiếu role id', 
    'id.exists'=>'role id đã tồn tại',
     'role.required'=>'Thiếu role', 
     'role.exists'=>'role không hợp lệ',
    ]);
    if ($validator->fails()){   
       return response()->json(['check' => false, 'msg' => $validator->errors()]);
    }
      User::where('id', $request->id)->update(['idRole' => $request->idRole,'updated_at'=>now()]);
    return response()->json(['check' => true]);

}

public function updateUSstatus(Request $request)
    {
    $validator=Validator::make($request->all(), [ 
        'id'=>'required|exists:users,id',
      'status'=>'required|numeric|min:0|max:1',
    ],[
     'id.required'=>'Thiếu id status', 
     'id.exists'=>'id status không hợp lệ',
     'status.required'=>'Thiếu status', 
    'status.numeric'=>'status invalid',
    'status.min'=>'status min invalid',
    'status.max'=>'status max invalid',
    ]);
    if ($validator->fails()){   
       return response()->json(['check' => false, 'msg' => $validator->errors()]);
    }
    User::where('id', $request->id)->update(['status'=>$request->status,'updated_at'=>now()]);
    return response()->json(['check' => true]); 
    }

    public function updateUSname(Request $request, UserRoleM $userRoleM)
    {
    $validator = Validator::make($request->all(), [ 
        'id' => 'required|exists:users,id',
        'name' => 'required',
    ],[
    'id.required'=>'Thiếu id name', 
    'id.exists'=>'name id tồn tại',
     'name.required'=>'Thiếu name', 
    ]);
    if ($validator->fails()){   
       return response()->json(['check' => false, 'msg' => $validator->errors()]);
    }
    User::where('id', $request->id)->update(['name'=>$request->name,'updated_at'=>now()]);
    return response()->json(['check' => true]); 

}

public function updateUSemail(Request $request)
    {
    $validator = Validator::make($request->all(), [ 
        'id' => 'required|exists:users,id',
        'email' => 'required|email|unique:users,email',
    ],[
    'id.required'=>'Thiếu id email', 
    'id.exists'=>'email id tồn tại',
     'email.required'=>'Thiếu email ', 
     'email.email'=>' email  sai dinh dang',
     'email.unique'=>'email existed',
    ]);
    if ($validator->fails()){   
       return response()->json(['check' => false, 'msg' => $validator->errors()]);
    }
      User::where('id', $request->id)->update(['email' => $request->email,'updated_at'=>now()]);
      $username = User::where('id', $request->id)->value('name');
      $mailData=[
        'name' => $username,
        'email' => $request->email,       
    ];
    Mail::to($request->email())->send(new UserMail($mailData));
    return response()->json(['check'=>true]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:users,id',
        ],[
           'id.required' => 'missing delete role',
           'name.exists' => 'existing delete name'
        ]);
        if ($validator->fails()) {
            return response()->json(['check'=>false,'msg'=>$validator->errors()]);
        }
        User::where('id',$request->id)->delete();
        return response()->json(['check'=>true]);

    }
}
