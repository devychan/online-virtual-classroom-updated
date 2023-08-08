<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Enrolls;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassesController extends Controller
{
    public function create(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            // $id = User::where('id', $user_id)->get();

            if ($user_id) {
                $class_code = Str::random(9);
                $data = $request->validate([
                    'class_name' => 'required',
                    'class_code' => 'string|unique:classrooms, class_code',
                    'class_section' => 'required',
                    'class_subject' => 'required',
                    'class_room' => 'required',
                ]);
                $result = Classroom::create([
                    'user_id' => $user_id,
                    'class_name' => $data['class_name'],
                    'class_code' => $class_code,
                    'class_section' => $data['class_section'],
                    'class_subject' => $data['class_subject'],
                    'class_room' => $data['class_room'],
                ]);
                return response([
                    'result' => 'Classroom created'
                ]);
            }
        }
    }
    public function show()
    {
        if (Auth::check()) {
            try {
                $user_id = Auth::id();
                $checkClass = Classroom::where('user_id', $user_id);
                $checkUserClass = Enrolls::where('user_id', $user_id);
                $results = Classroom::whereHas('enrolls', fn ($query) => $query->where('user_id', $user_id))->get();
                if ($checkClass->count()) {
                    if ($checkUserClass->count()) {
                        if ($results->count()) {
                            return response([
                                'result' => $results->merge($checkClass->get())
                            ]);
                        }
                    } else {
                        return response([
                            'result' => $checkClass->get()
                        ]);
                    }
                } else {
                    return response([
                        'result' => $results
                    ]);
                }
            } catch (Exception $e) {
                return response([
                    'result' => []
                ]);
            }
        }
    }
    public function joinroom($id, $class_code)
    {
        if (Auth::check()) {
            try {
                $user_id = Auth::id();
                $check_code = Classroom::select('id')->where('class_code', $class_code)->get();
                $class_id = Classroom::select('id')->where('class_code', '=', $class_code)->pluck('id');
                // $user_id = User::select('id')->where('id', '=', $id)->pluck('id');

                // foreach ($user_id as $u_ID)
                foreach ($class_id as $c_ID)

                    $ifExist = Enrolls::where([['user_id', $user_id], ['classroom_id', $c_ID]]);
                $ifOwnClass = Classroom::select('user_id')->where('class_code', $class_code)->pluck('user_id');

                foreach ($ifOwnClass as $own);

                if ($check_code->count()) {
                    if ($class_id->count() && $user_id) {
                        if (!$ifExist->count()) {
                            if ($own != $user_id) {
                                Enrolls::create([
                                    'user_id' => $user_id,
                                    'classroom_id' => $c_ID
                                ]);
                                return response([
                                    'result' => 'You\'ve been enrolled!',
                                ]);
                            } else {
                                return response([
                                    'result' => 'You cannot enroll to your own classroom',
                                ]);
                            }
                        } else {
                            return response([
                                'result' => 'You are already enrolled in this classroom!',
                            ]);
                        }
                    }
                } else {
                    return response([
                        'result' => 'Code coudn\'t found!'
                    ]);
                }
            } catch (Exception $e) {
                return response([
                    'result' => 'Something wen\'t wrong in the server.'
                ]);
            }
        }
    }
    public function viewroom($class_code)
    {
        if (Auth::check()) {
            try {
                $class_id = Classroom::select('id')->where('class_code', $class_code)->pluck('id');
                $class_info = Classroom::where('class_code', $class_code)->get();
                $results = Classroom::with('tasks')->whereHas('tasks', fn ($q) => $q->where('classroom_id', $class_id))->get();
                if ($class_id->count()) {
                    if ($results->count()) {
                        return response([
                            'result' => $results->first()
                        ]);
                    } else {
                        return response()->json([
                            'result' => $class_info->first()
                        ]);
                    }
                } else {
                    return response([
                        'result' => "No task"
                    ]);
                }
            } catch (Exception $e) {
                return response([
                    'result' => "Classroom not found!"
                ]);
            }
        }
    }
    public function unenroll($classroom_id)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $classID = Classroom::select('user_id')->where('id', $classroom_id)->pluck('user_id')->first();
            if ($user_id == $classID) {
                Classroom::where('id', $classroom_id)->delete();
                return response()->json([
                    'result' => "You've been remove your classroom"
                ]);
            } else {
                $enrollID = Enrolls::select('user_id')->where('classroom_id', $classroom_id)->pluck('user_id')->first();
                if ($user_id == $enrollID) {
                    Enrolls::where('classroom_id', $classroom_id)->delete();
                    return response()->json([
                        'result' => "You've been un-enroll in this class"
                    ]);
                }
            }
        }
    }
}
