<?php

class SchoolController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$schools = School::all();
		
		return View::make('school/index')->with("schools", $schools);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{

		return View::make('school/create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
    $name = Input::get('name');
    $school = new School;
    $school->name = $name;
    $school->save();
		return Redirect::to('school');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$school = School::find($id);
		return View::make("school/show")->with('school',$school);
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
		$school = School::find($id);
		return View::make("school/edit")->with('school',$school);

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
    $school = School::find($id);
    $school->name = $name;
    $school->save();

    return Redirect::to('school');

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$school = School::find($id);
		
		$school->delete();
	}


}
