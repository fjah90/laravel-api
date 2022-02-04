<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CourseAndModuleRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Response;
use\Illuminate\Database\QueryException;

//Models
use App\Models\Course;
use App\Models\User;

class TeacherController extends Controller
{
    //
    public function dictateCourse($courseId, $teacherId = 0)
	{

        $teacherId = $teacherId ? $teacherId : Auth::user()->id;
        $teacher = User::findOrFail($teacherId);

        $hasTeacher =  TeacherController::hasTeacherInCourse($courseId, $teacherId);

        if(!$hasTeacher && $teacherId > 0){
            try {
                if($teacher->courses()->attach($courseId) === null) {
                    return response()->json(["status" => "success", "error" => false, "message" => "Success attach a teacher."], 200);
                }else{
                    return response()->json(["status" => "failed", "error" => true, "message" => "Failed to attach a teacher."], 404);
                }
            }
            catch(QueryException $e) {
                return response()->json(["status" => "failed", "message" => "Failed attach a teacher. " . $e->getMessage()], 404);
            }
        }else{
            return response()->json(["status" => "failed", "error" => true, "message" => "Failed to attach already has a teacher."], 404);
        }
	}
    public function destroyDictateCourse($courseId)
	{
        $teacherId = Auth::user()->id;
		$teacher = User::findOrFail($teacherId);

        try {
            if($teacher->courses()->detach($courseId) === 1) {
                return response()->json(["status" => "success", "error" => false, "message" => "Success detach Dictate."], 200);
            }else{
                return response()->json(["status" => "failed", "error" => true, "message" => "Failed detach Dictate."], 404);
            }
        }
        catch(QueryException $e) {
            return response()->json(["status" => "failed", "message" => $e->getMessage()], 404);
        }
	}

    public function showCourses()
	{
        $teacherId = Auth::user()->id;
		$teacher = User::findOrFail($teacherId);

		$courses = $teacher->courses()->get();

        return response()->json(["status" => "success", "error" => false, "data" => $courses], 200);
	}

    public function showCoursesByCourseId($courseId)
	{
		$teacherId = Auth::user()->id;
        $teacher = User::findOrFail($teacherId);

		$course = Course::findOrFail($courseId);

        $data =[
                    [
						'teacher'	=>	$teacher->name,
						'course' 	=> 	$course
                    ]
                ];
        return response()->json(["status" => "success", "error" => false, "data" => $data], 200);

	}

    protected function hasTeacherInCourse($courseId, $teacherId)
	{
        return DB::table('teachers_dictate')
            ->whereUserId($teacherId)
            ->whereCourseId($courseId)
            ->count() > 0;
	}
}
