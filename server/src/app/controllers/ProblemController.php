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
		$difficulty = array("1"=>"Fácil", 2 => 'Medio', 3 => 'Difícil');
		$tags = Tag::all();


		return View::make('problems.index')
									->with('problems', $problems)
									->with('title', 'Preguntas')
									->with('newProblem', new Problem)
									->with('tags', $tags)
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

		$tags = Input::get("tags");

		foreach ($tags as $index=>$tag_id) {
			$problem->tags()->attach($tag_id);
		}
		


		
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
		$difficulty = array("1"=>"Fácil", 2 => 'Medio', 3 => 'Difícil');
		$tags = Tag::all();


		return View::make('problems.partials.edit')
									->with('difficulty', $difficulty)
									->with('tags', $tags)
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
		$problem->difficulty = Input::get('difficulty');
		$problem->save();

		$tags = Input::get("tags");
		DB::table('problem_tag')->where('problem_id', '=', $id)->delete();

		foreach ($tags as $index=>$tag_id) {
			$problem->tags()->attach($tag_id);
		}

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
		DB::table('problem_tag')->where('problem_id', '=', $id)->delete();
		$problem->delete();
		
	}


}
