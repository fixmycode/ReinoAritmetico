<?php

class ReportController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$numberOfGames = count(Game::all());
		$averageProblemArray = GamePlayer::averageProblemTime();
		$averageArray = array();
		

		array_map(function($n){
		  $n->data = [$n->data];
		}, $averageProblemArray);

		$categories = array();
		foreach(ProblemType::getNombres() as $item){
			array_push($categories, $item->type);
		}
		return View::make('reports.index')
					->with("averageData", json_encode($averageProblemArray))
					->with("averageCategories", json_encode($categories));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
