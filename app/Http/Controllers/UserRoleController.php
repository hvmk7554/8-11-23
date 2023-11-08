<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\UserRoleM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles=UserRoleM::all();
        return view('users.roles',compact('roles'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {      
        $validator = Validator::make($request->all(), [
            'rolename' => 'required|unique:role_tbl,name',
        ],[
           ' rolename.required' => 'missing role',
           ' rolename.existed' => 'existing role'
        ]);
        if ($validator->fails()) {
            return response()->json(['check'=>false,'msg'=>$validator->errors()]);
        }
        UserRoleM::create(['name'=>$request->rolename]);
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
    public function show(UserRoleM $userRoleM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserRoleM $userRoleM)
    {
        //
    }
//
    public function switchRole(Request $request)
    {
    $validator=Validator::make($request->all(), [ 
        'id'=>'required|exists:role_tbl,id',
      'status'=>'required|numeric|min:0|max:1',
    ],[
     'id.required'=>'Thiếu mã loại tài khoản', 
     'id.exists'=>'Mã loại tài khoản không hợp lệ',
     'status.required'=>'Thiếu status', 
    'status.numeric'=>'status invalid',
    'status.min'=>'status invalid',
    'status.max'=>'status invalid',
    ]);
    if ($validator->fails()){   
       return response()->json(['check' => false, 'msg' => $validator->errors()]);
    }
      UserRoleM::where('id', $request->id)->update(['status' => $request->status]);
    return response()->json(['check' => true]);

}
//
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRoleM $userRoleM)
    {
    $validator = Validator::make($request->all(), [ 
        'id' => 'required|exists:role_tbl,id',
        'rolename' => 'required|unique:role_tbl,name',
    ],[
    'rolename.required'=>'Thiếu tên loại tài khoản', 
    'rolename.unique'=>'Loại tài khoản đã tồn tại',
     'id.required'=>'Thiếu mã loại tài khoản', 
     'id.exists'=>'Mã loại tài khoản không hợp lệ',
    ]);
    if ($validator->fails()){   
       return response()->json(['check' => false, 'msg' => $validator->errors()]);
    }
      UserRoleM::where('id', $request->id)->update(['name' => $request->rolename]);
    return response()->json(['check' => true]);

}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, UserRoleM $userRoleM)
    {
        $validator = Validator::make($request->all(), [
            'id'=>'required|exists:role_tbl,id',
        ],[
           'id.required' => 'missing role',
           'rolename.exists' => 'existing role'
        ]);
        if ($validator->fails()) {
            return response()->json(['check'=>false,'msg'=>$validator->errors()]);
        }
        $check=User::where('idRole', $request->id)->count('id');
         if($check!=0){
           return response()->json(['check'=>false,'msg'=>'Có tài khoản tồn tại trong loại này']);
         }
        UserRoleM::where('id',$request->id)->delete();
        return response()->json(['check'=>true]);

    }
}
