<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hash,DB;
use App\Models\Admin;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function index()
    {
        $products = Product::count();
        $orders = Order::count();
        $customers = User::count();
        $data = [
            ['title' => 'Total Product', 'qty' => $products],
            ['title' => 'Total Order', 'qty' => $orders],
            ['title' => 'Total Customer', 'qty' => $customers]
        ];
        return view('pages.dashboard',['infos' => $data]);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:admins',
            'password'  => 'required',
        ]);

        try {
            $creadential = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            // dd($creadential);
            if (Auth::guard('admin')->attempt($creadential, $request->get('remember'))) {
                return redirect('admin/dashboard');
            }
            // if unsuccessful, then redirect back to the login with the form data
            return redirect()->back()
            ->with('error', 'Wrong Credentials or you are deactivated !')
            ->withInput($request->only('email', 'remember'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function getUser()
    {
        try {
            if (Auth::guard('admin')->check()) {
                return Auth::guard('admin')->user();
            }
            return "No User Found";
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect('login');
        }
        return "No User Found";
    }

    public function getForgotPassword()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect('login');
        }
        return "No User Found";
    }

    public function enterPassword(Request $request)
    {
        return view('admin.enter_password');
    }

    public function resetMail(Request $request)
    {
        $request->validate([
            'email'     => 'required|email|exists:admins'
        ]);
        // return $request->email;
        try {
            DB::beginTransaction();
            $token = \Str::random(60);
            $details = [
                'token' => $token,
                'email' => $request->email
            ];
            \DB::table('password_resets')->insert($details);
            \Mail::to($request->email)->send(new \App\Mail\ResetPasswordMail($details));
            DB::commit();
            return back()->with('message','Reset Link send to your mail.');

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->with('error','Something went wrong!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);
        // return $request->all();
        try
        {
            DB::beginTransaction();
            $userToken = \DB::table('password_resets')->where('token', $request->token)->first();

            if (!$userToken) {
                return back()->with('error', 'Invalid Token');
            }

            $admin           = Admin::where('email',$request->email)->first();
            $admin->password = Hash::make($request->password);
            $admin->update();

            \DB::table('password_resets')->where('token', $request->token)->delete();

            DB::commit();
            return redirect('login')->with('success', 'Please login with your new password');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        if (!Hash::check($request->old_password, Auth::guard('admin')->user()->password)) {
            return back()->with("error", "Old Password Doesn't match!");
        }

        Admin::whereId(Auth::guard('admin')->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("status", "Password changed successfully!");
    }
}
