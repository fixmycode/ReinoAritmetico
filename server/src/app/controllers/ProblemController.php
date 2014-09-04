<?php

class ProblemController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{

		$problems = Problem::all();
		//$problemType = DB::table('problem_type')->select('id', 'type')->get();
		$problemType = ProblemType::lists("type","id");
		$dificultad = array("1"=>"1");
		
		return View::make('problems.index')
									->with('problems', $problems)
									->with('title', 'Preguntas')
									->with('problem_type',$problemType )
									->with('dificultad', $dificultad);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{


	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$problem = new Problem();
		$problem->question = Input::get('question');
		$problem->answer = Input::get('answer');
		$problem->problem_type_id = Input::get('problem_type_id');
		$problem->difficulty = Input::get('dificultad');
		$problem->save();
		
		return Redirect::route('problems.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
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
		$problem = Problem::findOrFail($id);
		$problemType = ProblemType::lists("type","id");
		$dificultad = array("1"=>"1");

		return View::make('problems.partials.edit')
									->with('problem_type',$problemType )
									->with('dificultad', $dificultad)
									->with('problem', $problem);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$problem = Problem::find($id);
		$problem->question = Input::get('question');
		$problem->answer = Input::get('answer');
		$problem->problem_type_id = Input::get('problem_type_id');
		$problem->difficulty = Input::get('dificultad');
		$problem->save();
		return Redirect::route('problems.index');

	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{

		$problem = Problem::findOrFail($id);

		$problem->delete();
		
	}


}
