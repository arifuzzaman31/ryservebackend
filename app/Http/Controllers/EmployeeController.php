<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class EmployeeController extends Controller
{
    public function index()
    {
        $role = Role::get();
        return view('pages.employee.employee',['role' => $role]);
    }

    public function create(Request $request)
    {
        $admin = Admin::with('role')->latest();
        if($request->keyword != '')
        {
            $admin = $admin->where('name','like','%'.$request->keyword.'%');
            $admin = $admin->orWhere('email','like','%'.$request->keyword.'%');
        }
        return $admin->paginate(10);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);
        try {
            $emp = new Admin();
            $emp->name = $request->name;
            $emp->email = $request->email;
            $emp->password = Hash::make($request->password);
            $emp->role_id = $request->role;
            $emp->save();
            Cache::forget('admin_permission');
            Cache::flush();
            return $this->successMessage('Employee Created Successfully!');
        } catch (\Throwable $th) {
            return $th;
            return $this->errorMessage();
        }
    }

    public function update(Request $request,$id)
    {
        $this->validate($request,[
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);

        try {
            $emp = Admin::find($id);
            $emp->name = $request->name;
            $emp->email = $request->email;
            $emp->role_id = $request->role;
            $emp->update();
            Cache::forget('admin_permission');
            Cache::flush();
            return $this->successMessage('Employee Updated Successfully!');
        } catch (\Throwable $th) {
            return $th;
            return $this->errorMessage();
        }

    }

    public function destroy($id)
    {
        try {
            $emp = Admin::find($id);
            $emp->delete();
            Cache::forget('admin_permission');
            Cache::flush();
            return $this->successMessage('Employee Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }
}
