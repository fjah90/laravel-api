<?php

namespace App\Http\Controllers;

use Exception;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

// Models
use App\Models\Course;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * Display all Courses.
     *
     *
     * @return \Illuminate\Http\Response
     */

	public function showCourses()
	{
		$courses = Course::all();
        return response()->json(["status" => "success", "error" => false, "data" => $courses], 200);
	}

    /**
     * Display Course by Id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

	public function showCourseById($id)
	{
		$course = Course::find($id);
        if($course){
            return response()->json(["status" => "success", "error" => false, "data" => $course], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no found."], 404);
	}

    /**
     * Store Course.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerAsAdmin(Request $request) {
        $userId = Auth::user()->id;
        $userRole = User::find($userId)->role;
        if($userRole === 'admin'){
            $validator = Validator::make($request->all(), [
                "name" => "required|min:4",
                "email" => "required|email|unique:users,email",
                "password" => "required|min:3",
                "role" => "required|min:4",
            ]);

            if($validator->fails()) {
                return $this->validationErrors($validator->errors());
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            return response()->json(["status" => "success", "error" => false, "message" => "Success! User {$request->role} registered."], 201);
        }
            return response()->json(["status" => "success", "error" => false, "message" => "Failed! you are {$userRole} can't registed user."], 201);


    }
	public function storeCourse(Request $request)
	{

        $validator = Validator::make($request->all(), [
            "name" => "required|min:4",
            "start_date" =>  "required|date|date_format:Y-m-d",
            "end_date" =>  "required|date|date_format:Y-m-d",
        ]);

        if($validator->fails()) {
            return $this->validationErrors($validator->errors());
        }

        try {
            $course = Course::create([
                "name" => $request->name,
                "description" => $request->description,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date
            ]);
            return response()->json(["status" => "success", "error" => false, "message" => "Success! created."], 201);
        }
        catch(Exception $exception) {
            return response()->json(["status" => "failed", "error" => $exception->getMessage()], 404);
        }
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCourseById(Request $request, $id)
    {
        $course = Course::find($id);

        if($course) {
            $validator = Validator::make($request->all(), [
                "name" => "min:4",
                "start_date" =>  "date|date_format:Y-m-d",
                "end_date" =>  "date|date_format:Y-m-d",
            ]);

            if($validator->fails()) {
                return $this->validationErrors($validator->errors());
            }

            $result = $course->update($request->only('name', 'description', 'start_date', 'end_date'));


            if($result === true)
                return response()->json(["status" => "success", "error" => false, "message" => "Success! todo updated."], 201);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no found."], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCourseById($id)
    {

        $course = Course::find($id);
        if($course) {
            Course::destroy($id);
            return response()->json(["status" => "success", "error" => false, "message" => "Success! todo deleted."], 200);
        }
        return response()->json(["status" => "failed", "error" => true, "message" => "Failed no found."], 404);
    }

    public function showTeachers()
	{
		$teachers = User::where('role', '=', 'teacher')->paginate(5);
        return response()->json(["status" => "success", "error" => false, "data" => $teachers], 200);
	}

}
