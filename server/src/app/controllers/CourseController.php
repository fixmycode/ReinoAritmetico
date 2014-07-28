<?php

class CourseController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$courses = Course::all();
		
		return View::make('course/index')->with("courses", $courses);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return View::make('course/create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $name = Input::get('name');
    $course = new Course;
    $course->name = $name;
    $course->save();
		return Redirect::to('course');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$course = Course::find($id);
		return View::make("course/show")->with('course',$course);
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$course = Course::find($id);
		return View::make("course/edit")->with('course',$course);

	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$name = Input::get('name');
    $course = Course::find($id);
    $course->name = $name;
    $course->save();

    return Redirect::to('course');

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$course = Course::find($id);
		
		$course->delete();
	}

}
