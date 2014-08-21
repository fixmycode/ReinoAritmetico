<?php

class ClientsClassroomsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($client_id)
	{

		$client = Client::with('classrooms')->findOrFail($client_id);
		
		return View::make('clients.classrooms.index')
								->with("client", $client)
								->with('title', 'Cursos')
								->with('subtitle', $client->name)
								->with('breadcrumbs', Breadcrumbs::render('clientss.classrooms.index', $client));
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
	public function store($client_id)
	{
		$client = Client::findOrFail($client_id);

    $validator = Validator::make($data = Input::all(), Classroom::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$classroom = new Classroom(Input::only('name'));

		$client->classrooms()->save($classroom);

		return Redirect::route('clientss.classrooms.index', $client->id);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($client_id, $id)
	{
		$classroom = Client::findOrFail($client_id)->classrooms()->with('client')->findOrFail($id);

		return View::make("clients.classrooms.show")->with('classroom', $classroom);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($client_id, $id)
	{
		$classroom = Client::findOrFail($client_id)->classrooms()->with('client')->findOrFail($id);

		return View::make("clients.classrooms.partials.edit")
							->with('classroom', $classroom)
							->with('client', $classroom->client);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($client_id, $id)
	{
    $classroom = Client::findOrFail($client_id)->classrooms()->findOrFail($id);

    $validator = Validator::make($data = Input::all(), Classroom::$rules);

    if ($validator->fails())
    {
    	return Redirect::back()->withErrors($validator)->withInput();
    }

    $classroom->update($data);

    return Redirect::back();
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($client_id, $classroom_id)
	{
		$classroom = Client::findOrFail($client_id)->classrooms()->find($classroom_id);

		$classroom->delete();
	}
}
