<?php

namespace App\Http\Controllers;

use Exception;
use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

	public function getCourses()
	{
		$courses = Course::all();
        $data = Auth::user()->$courses;
        return response()->json(["status" => "success", "error" => false, "data" => $courses], 200);
	}

}
