<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function getUser()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $user = User::where('id', $user_id)->get()->first();
            return response()->json([
                'result' => $user
            ]);
        }
    }
    public function updateUser(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $currentUser = Auth::user();
            $data = $request->validate([
                'name' => 'min:8|regex:/^[\pL\s\-]+$/u',
                'username' => 'min:8|unique:users,username,' . $user_id,
                'password' => 'required',
                'image_path' => 'mimes:jpg,png,jpeg',
                'new_password' => 'min:8|nullable'
            ]);
            $path = storage_path('app/public/public_images');

            $verifyUser = password_verify($data['password'], $currentUser->password);
            if ($verifyUser) {
                if ($request->has('image_path')) {
                    if (Storage::exists($path)) {
                        Storage::makeDirectory($path);
                    } else {
                        $image = time() . '_' . $data['username'] . '.' . $data['image_path']->extension();
                        $data['image_path']->move($path, $image);
                    }
                }
                if ($data['new_password'] != null) {
                    $result = User::where('id', $user_id)->update([
                        'name' => $data['name'],
                        'username' => $data['username'],
                        'image_path' => $image,
                        'password' => bcrypt($data['new_password'])
                    ]);
                    return response()->json([
                        'result' => 'User updated'
                    ]);
                } else {
                    $result = User::where('id', $user_id)->update([
                        'name' => $data['name'],
                        'image_path' => $image,
                        'username' => $data['username']
                    ]);
                    return response()->json([
                        'result' => 'User updated'
                    ]);
                }
            } else {
                return response()->json([
                    'result' => 'Please verify the password'
                ]);
            }
        }
    }

}
