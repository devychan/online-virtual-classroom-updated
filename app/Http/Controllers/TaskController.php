<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use App\Models\Comment;
use App\Models\Enrolls;
use App\Models\Result;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isEmpty;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        if (Auth::check()) {
            $typeOf = $request->input('type');
            $user_id = Auth::id();
            if ($typeOf === 'announce') {
                $data = $request->validate([
                    'title' => 'required|min:1',
                    'description' => 'required|min:1',
                    'type' => 'required',
                    'status' => 'nullable'
                ]);
                $announce = Task::create([
                    'user_id' => $user_id,
                    'classroom_id' => $request->input('classroom_id'),
                    'title' => $data['title'],
                    'description' =>  $data['description'],
                    'type' =>  $data['type'],
                ]);
                return response()->json([
                    'result' => 'Task created.'
                ]);
            } else if ($typeOf === 'quiz') {

                $data = $request->validate([
                    'title' => 'required|min:1',
                    'type' => 'required',
                    'items' => 'array',
                    'tag' => '',
                    'status' => 'boolean',
                    'total' => 'integer'
                ]);
                $quiz = Task::create([
                    'user_id' => $user_id,
                    'classroom_id' => $request->input('classroom_id'),
                    'title' => $data['title'],
                    'tag' => $data['tag'],
                    'type' =>  $data['type'],
                    'data' => collect($data['items']),
                    'total' => count($request->input('items')),
                    'status' => $data['status']
                ]);
                return response()->json([
                    'result' => 'Task created.'
                ]);
            } else if ($typeOf === 'bulletin') {
                $data = $request->validate([
                    'title' => 'required|min:1',
                    'description' => 'required|min:1',
                    'type' => 'required',
                    'items' => 'array',
                    'status' => 'nullable'
                ]);
                $bulletin = Task::create([
                    'user_id' => $user_id,
                    'classroom_id' => $request->input('classroom_id'),
                    'title' => $data['title'],
                    'description' =>  $data['description'],
                    'type' =>  $data['type'],
                    'data' => collect($data['items']),
                ]);
                return response()->json([
                    'result' => 'Task created.'
                ]);
            }
        }
    }
    public function getTask($task_id, $classroom_code)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $classroom_id = Classroom::where('class_code', $classroom_code)->get();
            if ($classroom_id) {
                $tasks = Classroom::where('class_code', $classroom_code)->with(['tasks' => function ($q) use ($task_id) {
                    $q->where('id', $task_id);
                }])->with(['results' => function ($q) use ($task_id) {
                    $q->where('task_id', $task_id);
                }])->get()->first();
                return response()->json([
                    'result' => $tasks
                ]);
            } else {
                return response()->json([
                    'result' => collect([])
                ]);
            }
        }
    }
    public function submitQuiz(Request $request, $task_id)
    {
        if (Auth::check()) {
            $user_id = Auth::id();

            $checkClassRoom = Classroom::with(['tasks' => function ($q) use ($task_id) {
                $q->where('id', $task_id);
            }])->get();

            $checkStatus = Result::where('user_id', $user_id)->where('task_id', $task_id)->get()->first();
            if ($checkStatus) {
                return response()->json([
                    'result' => $checkStatus
                ]);
            } else {
                if ($checkClassRoom) {
                    $checkIfEnrolled = Enrolls::where('user_id', $user_id)->pluck('classroom_id')->first();
                    if ($checkIfEnrolled) {
                        if ($request->has('answer')) {
                            $tasks = Task::where('id', $task_id)->pluck('data');
                            $userAnswer = $request->input('answer');

                            foreach ($tasks as $task) {
                                $answers = json_decode($task);
                            }
                            $data = [];
                            foreach ($answers as $answer) {
                                array_push($data, $answer->{'answer'});
                            }
                            $result = array_intersect_assoc($data, $userAnswer);

                            $sendAnswer = Result::create([
                                'user_id' => $user_id,
                                'task_id' => $task_id,
                                'answer' => collect($userAnswer),
                                'classroom_id' => $checkIfEnrolled,
                                'score' => count($result),
                                'status' => 1
                            ]);

                            return response()->json([
                                'result' => $sendAnswer,
                            ]);
                        }
                    } else {
                        return response()->json([
                            'result' => 'Unauthorized actions.'
                        ]);
                    }
                }
            }
        }
    }
    public function getResult($task_id)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $user = Task::with(['results' => function ($q) use ($task_id) {
                $q->where('task_id', $task_id);
            }])->get()->pluck('user_id');
            $result = Result::where([['user_id', $user_id], ['task_id', $task_id]])->get()->first();
            if ($user[0] == $user_id) {
                return response()->json([
                    'result' => []
                ]);
            } else {
                if ($result === null) {
                    return response()->json([
                        'result' => []
                    ]);
                } else {
                    return response()->json([
                        'result' => $result
                    ]);
                }
            }
        }
    }
    public function postComment(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $validateField = $request->validate([
                'task_id' => 'required',
                'classroom_id' => 'required',
                'comment' => 'required',
                'user' => 'required'
            ]);
            $results = Comment::create([
                'user_id' => $user_id,
                'task_id' => $validateField['task_id'],
                'classroom_id' => $validateField['classroom_id'],
                'comment' => $validateField['comment'],
                'user' => $validateField['user']
            ]);

            return response()->json([
                'result' => $results
            ]);
        }
    }
    public function getComment(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $classroom = Classroom::where('user_id', $user_id)->get()->first();

            if ($classroom) {
                $validateField = $request->validate([
                    'task_id' => 'required',
                    'class_code' => 'required',
                ]);
                $task_id = $validateField['task_id'];
                $classroom_id = Classroom::where('class_code', $validateField['class_code'])->pluck('id');
                $validator = [
                    'task_id' => $task_id,
                ];
                $results = Classroom::where('id', $classroom_id)->with(['comments' => function ($q) use ($validator) {
                    $q->where('task_id', $validator['task_id']);
                }])->get()->first();
                return response()->json([
                    'result' => $results
                ]);
            } else {
                $validateField = $request->validate([
                    'task_id' => 'required',
                    'class_code' => 'required',
                ]);
                $task_id = $validateField['task_id'];
                $classroom_id = Classroom::where('class_code', $validateField['class_code'])->pluck('id');
                $validator = [
                    'task_id' => $task_id,
                    'user_id' => $user_id
                ];
                $results = Classroom::where('id', $classroom_id)->with(['comments' => function ($q) use ($validator) {
                    $q->where('task_id', $validator['task_id'])->where('user_id', $validator['user_id']);
                }])->get()->first();
                return response()->json([
                    'result' => $results
                ]);
            }
        }
    }
    public function roomComments(Request $request)
    {
        if (Auth::check()) {
            $validateField = $request->validate([
                'class_code' => 'required',
                'page' => 'integer'
            ]);
            $class_code = $validateField['class_code'];
            $classroom_id = Classroom::where('class_code', $class_code)->pluck('id');
            $now = Carbon::today();
            $data = [
                'now' => $now,
                'page' => $validateField['page']
            ];
            $results = Classroom::where('id', $classroom_id)->with(['comments' => function ($q) use ($data) {
                $q->whereDate('created_at', $data['now'])->whereNot('user_id', Auth::id())->paginate(10, ['*'], 'page', $data['page']);
            }])->first();

            if ($results) {
                $pagination = Result::whereDate('created_at', $data['now'])->whereNot('user_id', Auth::id())->paginate(8, ['*'], 'page', $data['page']);
            }

            return response()->json([
                'result' => $results->comments,
                'length' => count($results->comments),
                'pages' => $pagination
            ]);
        }
    }
    public function getCompleted()
    {
        if (Auth::check()) {
            $user_id = Auth::id();

            $done = Classroom::with(['results' => function ($q) use ($user_id) {
                $q->where([['user_id', $user_id], ['status', 1]]);
            }])->get()->first();

            $assigned = Classroom::with(['results' => function ($q) use ($user_id) {
                $q->where([['user_id', $user_id], ['status', 0]]);
            }])->get()->first();
            if ($done) {
                return response()->json([
                    'done' => count($done->results),
                    'missing' => 0,
                    'assigned' => count($assigned->results),
                ]);
            }
        }
    }
    public function setStatus(Request $request)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $status = $request->input('status');
            $class_code = $request->input('class_code');

            $class_owner = Classroom::where('class_code', $class_code)->pluck('user_id')->first();

            if ($user_id === $class_owner) {
                $result = Task::where('user_id', $user_id)->update([
                    'status' => $status
                ]);
                return response()->json([
                    'result' => 'Status update'
                ]);
            } else {
                return response()->json([
                    'result' => 'Unauthorized!'
                ]);
            }
        }
    }
}
