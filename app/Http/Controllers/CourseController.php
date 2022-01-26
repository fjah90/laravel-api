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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$courses = Course::all();
        return response()->json(["status" => "success", "error" => false, "data" => $courses], 200);
	}

}
