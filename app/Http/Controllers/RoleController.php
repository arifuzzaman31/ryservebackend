<?php

namespace App\Http\Controllers;

use App\Http\AllStatic;
use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::with('role_permission')->where('status',AllStatic::$active)->get();
        return view('pages.auth.role',['roles' => $role,'permission' => $this->getPermissionData()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getPermissionData()
    {
        $permission = Permission::where('status',AllStatic::$active)->get()->groupBy('group_name');
        return $permission;
    }

    public function getRole()
    {
        return view('pages.auth.add_role',['permissions' => $this->getPermissionData()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'role_name' => 'required',
            'role_permissions' => 'required|array|min:1'
        ]);
        try {
            $role = new Role();
            $role->role_name = $request->role_name;
            $role->save();

            if(($request->role_name != '') && !empty($request->role_permissions)){
                $role->role_permission()->attach($request->role_permissions);
            }
            Cache::forget('admin_permission');
            Cache::flush();
            return $this->successMessage('Role Added Successfully!');
        } catch (\Throwable $th) {
            return $th;
            return $this->errorMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'role_name' => 'required',
            'role_permissions' => 'required|array|min:1'
        ]);
        try {
            $role->role_name = $request->role_name;
            $role->update();

            if(($request->role_name != '') && !empty($request->role_permissions)){
                $role->role_permission()->sync($request->role_permissions);
            }
            Cache::forget('admin_permission');
            Cache::flush();
            return $this->successMessage('Role Updated Successfully!');
        } catch (\Throwable $th) {
            return $th;
            return $this->errorMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        try {
            $user = Admin::where('role_id',$role->id)->first();
            if($user){
                return response()->json(['status' => 'error','message' => 'Employee Has this Role!']);
            }
            $role->role_permission()->detach();
            $role->delete();
            Cache::forget('admin_permission');
            Cache::flush();
            return $this->successMessage('Role Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }
}
