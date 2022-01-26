<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CourseAndModuleRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Response;
use\Illuminate\Database\QueryException;

//Models
use App\Models\Course;
use App\Models\User;

class StudentController extends Controller
{

    public function coursesShow()
	{
		$studentId = Auth::user()->id;
		$student = User::findOrFail($studentId);

        $courses = $student->enrolling()->get();

        $data =[
                    [
                        'student'	=>	$student->name,
                        'courses'	=>	$courses
                    ]
                ];

        return response()->json(["status" => "success", "error" => false, "data" => $data], 200);
	}

    public function courseShowById($courseId)
	{
		$student = Auth::user()->id;

		$course = Course::findOrFail($courseId);

        $data = [
                    [
                        'student'	=>	$student,
                        'course'	=>	$course
                    ]
                ];
        return response()->json(["status" => "success", "error" => false, "data" => $data], 200);
	}

	public function enrolling($courseId)
	{
		$studentId = Auth::user()->id;
		$student = User::findOrFail($studentId);

        try {
            if($student->enrolling()->attach($courseId) === null) {
                return response()->json(["status" => "success", "error" => false, "message" => "Success enrolling."], 200);
            }else{
                return response()->json(["status" => "failed", "error" => true, "message" => "Failed enrolling."], 404);
            }
        }
        catch(QueryException $e) {
            return response()->json(["status" => "failed", "message" => $e->getMessage()], 404);
        }
	}

	public function unsubscribe($courseId)
	{
		$studentId = Auth::user()->id;
		$student = User::findOrFail($studentId);

        try {
            if($student->enrolling()->detach($courseId) === 1) {
                return response()->json(["status" => "success", "error" => false, "message" => "Success unsubscribe."], 200);
            }
            else{
                return response()->json(["status" => "failed", "error" => true, "message" => "Failed unsubscribe."], 404);
            }
        }
        catch(QueryException $e) {
            return response()->json(["status" => "failed", "message" => $e->getMessage()], 404);
        }
	}
}
