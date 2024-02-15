<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AddressBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            \Mail::to($request->email)->send(new \App\Mail\RegisterMail(['email' => $request->email,'name' => $request->name]));

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'user'  => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            // return response()->json($request->all());
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Email or Password.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user'  => $user,
                'token' => $user->createToken($user->email)->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function socialLoginUser(Request $request)
    {
        $user = User::firstOrCreate(
            [
                'email' => $request->email
            ],
            [
                'name' => $request->name,
            ]
        );
        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user'  => $user,
            'token' => $user->createToken($user->email)->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => true,
                'message' => 'User Logout Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function redirectToProvider($provider)
    {
        // return $provider;
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) return $validated;

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(Request $request,$provider)
    {
        $userapp = Socialite::driver($provider)->stateless()->user();

        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) return $validated;
        
        $user = User::firstOrCreate(
            [
                'email' => $userapp->getEmail()
            ],
            [
                'name' => $userapp->getName(),
            ]
        );
        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user'  => $user,
            'token' => $user->createToken($user->email)->plainTextToken
        ], 200);
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['google','facebook'])) {
            return response()->json(["message" => 'You can login via google or facebook account'], 400);
        }
    }

    public function storeNewPassword(Request $request)
    {
        $request->validate([
            'oldpassword' => 'required',
            'password'    => 'required|confirmed|min:6',
        ]);

        $hasPassword = Auth::user()->password;

        if (Hash::check($request->oldpassword, $hasPassword)) {
            $user           = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->update();
            return response()->json(['status' => 'success','massage' => 'Password Updated']);
        } else {
            return $this->errorMessage();
        }
    }

    public function sendEmailLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            DB::beginTransaction();
            $token                  = \Hash::make(uniqueString());
    
            $details = [
                'token' => $token,
                'email' => $request->email,
                'backUri' => $request->backUri
            ];
    
            \DB::table('password_resets')->insert(
                [
                    'token' => $token,
                    'email' => $request->email
                ]
            );
            \Mail::to($request->email)->send(new \App\Mail\ResetPasswordMail($details));
            DB::commit();
            return response()->json(['status' => 'success','massage' => 'Token send to Email','reset_token' => $token]);
            
        } catch (\Throwable $th) {
            DB::rollBack();
            //  return $th;
             return $this->errorMessage();
        }
    }

    public function storeResetPassword(Request $request)
    {
        try {
            $userToken = DB::table('password_resets')->where('token', $request->token)->first();
    
            if (!$userToken) {
                return response()->json(['status' => 'error','massage' => 'Invalid Token']);
            }
    
            $user           = User::where('email',$userToken->email)->first();
            $user->password = Hash::make($request->password);
            $user->update();
    
            DB::table('password_resets')->where('token', $request->token)->delete();
 
            return $this->successMessage('Password Changed Successfully!');

        } catch (\Throwable $th) {
            // return $th;
            return $this->errorMessage();
        }
    }

    public function profileUpdate(Request $request)
    {
        try {
            $user           = User::find(Auth::id());
            $user->first_name = $request->full_name;
            //$user->last_name = $request->last_name;
            //$user->name = $request->full_name;
           // $user->email = $request->email;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->date_of_birth = $request->date_of_birth;
            $user->gender = $request->gender;
            $user->occupation = $request->occupation;
            $user->update();
	        return response()->json([
                'status' => 'success',
                'message' => 'Profile Changed Successfully!',
                'user'  => $user
            ], 200);

        } catch (\Throwable $th) {
		    return $th;
            return $this->errorMessage();
        }
        
    }

    public function getUserAddress()
    {
        try {
            $data = AddressBook::where('user_id',Auth::user()->id)->latest()->get();
            return response()->json($data);

        } catch (\Throwable $th) {
            return $this->errorMessage();
        }
    }

    public function storeUserAddress(Request $request)
    {
        try {
            $address = AddressBook::updateOrCreate([
                'id'    =>  $request->id
            ],[
                'user_id'       => Auth::user()->id,
                'first_name'    => $request->first_name,
                'last_name'     => $request->last_name,
                'country'       => $request->country,
                'city'          => $request->city,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'post_code'     => $request->post_code,
                'apartment'     => $request->apartment,
                'street_address' => $request->street_address
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'New Address Added!',
                'last_address'  => $address
            ], 200);

        } catch (\Throwable $th) {
            return $th;
            return $this->errorMessage();
        }
        
    }
}