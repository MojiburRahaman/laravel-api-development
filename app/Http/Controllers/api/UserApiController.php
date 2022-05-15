<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserApiController extends Controller
{
    public function UserGet()
    {

        $user = User::get();
        return response()->json($user);
    }
    public function UserAdd(Request $request)
    {
        abort_if(!$request->isMethod('POST'), 404);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $users = User::where('email', $request->email)->first();
            $access_token = $users->createToken($request->email)->accessToken;
            $users->update(['access_token' => $access_token]);

            return response()->json(['success' => 'User Created SuccessFully', 'access_token' => $access_token]);
        }
        return response()->json(['warning' => 'Something Wrong',]);
    }
    public function UserUpdate(Request $request)
    {
        abort_if(!$request->isMethod('POST'), 404);

  return      $request->header('Authorization');

        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json('User Edited SuccessFully');
    }
    public function UserDelete(User $user, Request $request)
    {
        abort_if(!$request->isMethod('DELETE'), 404);
        $user->delete();
        return response()->json('User Deleted SuccessFully');
    }
    public function UserLoginView()
    {
        dd('please login');
    }
    function UserLogin(Request $request)
    {
        abort_if(!$request->isMethod('POST'), 404);

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $users = User::where('email', $request->email)->first();
            $access_token = $users->createToken(Str::random(10) . $request->email . Str::random(10))->accessToken;
            $users->access_token = $access_token;
            $users->save();

            return response()->json(['success' => 'User Login SuccessFully', 'access_token' => $access_token]);
        }
        return response()->json(['warning' => 'The Provided Credential are incorrect',]);
    }
    public function UserLogout(Request $request)
    {
        $user = auth()->user();
        $user->access_token = null;
        $user->save();
        return auth()->user();
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
