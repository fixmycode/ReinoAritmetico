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
		$difficulty = array("1"=>"Fácil", 2 => 'Normal', 3 => 'Dificil');
		
		return View::make('problems.index')
									->with('problems', $problems)
									->with('title', 'Preguntas')
									->with('newProblem', new Problem)
									->with('problem_type',$problemType )
									->with('difficulty', $difficulty);
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
		$problem->problem = Input::get('problem');
		$problem->correct_answer = Input::get('correct_answer');
		$problem->problem_type_id = 1; //Input::get('problem_type_id');
		$problem->difficulty = Input::get('difficulty');
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
		$difficulty = array("1"=>"Fácil", 2 => 'Normal', 3 => 'Dificil');

		return View::make('problems.partials.edit')
									->with('problem_type',$problemType )
									->with('difficulty', $difficulty)
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
		$problem->problem = Input::get('problem');
		$problem->correct_answer = Input::get('correct_answer');
		$problem->problem_type_id = 1;//Input::get('problem_type_id');
		$problem->difficulty = Input::get('difficulty');
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
