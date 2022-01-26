<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Requests\CourseAndModuleRequest;

use Illuminate\Http\Response;

//Models
use App\Models\Course;

class CourseController extends Controller {

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

}
