<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            if (Session::has('token')) {  
                $session_id = session()->get('session_id');
                Auth::loginUsingId($session_id);
                $user = session()->get('user');
                return response()->json([
                    'token' => session()->get('token'),
                    'session_id' => hash('sha256', $session_id) ,
                    'auth' => Auth::check(),
                    'user' => $user
                ]);
            }else{
                session()->flush();
                return response()->json([
                    'result' => '',
                    'auth' => Auth::check(),
                    'message' => "Unauthorized"
                ]);
            }
            
        } else {
            return response()->json([
                'result' => '',
                'auth' => Auth::check(),
                'message' => "Unauthorized"
            ]);
        }
    }
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|min:8|regex:/^[\pL\s\-]+$/u',
            'username' => 'required|unique:users,username|min:8',
            'image_path' => '',
            'password' => 'required|min:8'
        ]);
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => bcrypt($data['password'])
        ]);
        return response([
            'message' => "Registration successful" 
        ], 201);
    }
    public function login(Request $request)
    {
        Auth::check() ?? session()->flush();
        $user = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($user)) {
            $token = Auth::user()->createToken('token')->plainTextToken;
            session()->put('token', $token);
            session()->put('session_id', Auth::id());
            return response()->json([
                'result' => Auth::user(),
                'token' => $token
            ], 200);
        }
        throw ValidationException::withMessages([
            'username' => ["Invalid username or password"]
        ]);
    }
    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->tokens()->where('id', Auth::id())->delete();
            auth()->user()->tokens->each(function ($token) {
                $token->delete();
            });
            session()->flush();
            return response()->json([
                'result' => 'Logout successfully',
            ], 200);
        }
    }
}
