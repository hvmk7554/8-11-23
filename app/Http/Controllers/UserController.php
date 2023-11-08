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
        // $users = DB::table('users')
        //    ->join('role_tbl','users.idRole','=','role_tbl.id')
        //    ->select('users.*','role_tbl.name-as-rolename')
        //    ->get();
  //   dd($users);
return view('users.users',compact('roles'));
        
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
           ' name.required' => 'missing name',
           ' email.required' => 'no email',
           ' email.email' => 'invalid email',
           ' email.unique' => 'existed email',
           ' idRole.required' => 'invalid idRole',
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
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
