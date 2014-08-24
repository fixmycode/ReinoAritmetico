<?php

class APIController extends \BaseController {

	public function getIndex()
	{
		return View::make('hello');
	}

	/**
	 * GET /identify?id=<android_uid-del-estudiante>
	 *
	 * response: json
	 * {
	 * 	name: "nombre del estudiante",
	 * 	classroom: "1ro Basico",
	 * 	school: "Ruben Dario"
	 * }
	 */
	public function getIdentify() {
		$android_id = Input::get('id', 0);

	  $student = DB::table('students')->where('android_id', $android_id)
	              ->join('classrooms', 'students.classroom_id', '=', 'classrooms.id')
	              ->join('clients', 'classrooms.client_id', '=', 'clients.id')
	              ->select(DB::raw('students.name, classrooms.name as classroom, clients.name as school'))
	              ->first();

	  if ( is_null($student) )
	    App::abort(404);

		return json_encode($student);
	}

	/**
	 * GET /clients
	 *
	 * response: json
	 * [{
   *   'id': 1,
   *   'name': 'Colegio Rubén Darío',
   *   'classrooms': [{
   *      'id': 1,
   *      'name': '1ro Básico',
   *   }, {
   *     'id': 2,
   *     'name': 2do Básico',
   *   }, {
   *    ... 
   *   }],
   * }, {
   *   ...
   * }]
	 */
	public function getClients() {

  	return Client::with('classrooms')->get();
	}

	/**
	 * POST /register
	 * data:
	 * {
	 * 		name: "student name",
	 * 		android_id: <android_uid>
	 * 		school: <school_id>,
	 * 		classroom: <classroom_id>,
	 * }
	 * 
	 * response: json
	 * {
	 *   err: false,
	 *   msg: 'Estudiante creado exitosamente'
	 * }
	 * o algun error > 400
	 */
	public function postRegister() { 
	  $client_id    = Input::get('school', 0);
	  $classroom_id = Input::get('classroom', 0);
	  $student      = new Student(Input::only('name', 'android_id'));

	  Client::findOrFail($client_id)->classrooms()->findOrFail($classroom_id)->students()->save($student);

	  return Response::json(['err' => false, 'msg' => 'Estudiante creado exitosamente']);
	}

	/**
	 * GET /delete?id=<android-uid-del-estudiante>
	 *
	 * response: json
	 * {
	 *   err: false,
	 *   msg: 'Estudiante eliminado exitosamente'
	 * }
	 * o algun error > 400
	 */
	public function getDelete() {
	  $android_id = Input::get('id', 0);

	  $student = Student::whereAndroidId($android_id)->first();

	  if ( empty($student) )
			return Response::json(['err' => true, 'msg' => 'El estudiante no existe'], 404);	  	

	  $student->delete();

	  return Response::json(['err' => false, 'msg' => 'Estudiante eliminado exitosamente']);
	}

	/**
	 * URL: POST /start 
	 * data:
	 * {
	 * 		address: 10.0.0.1:8000,
	 * }
	 *
	 * response: json
	 * {
	 *   'uid': 'fadsfqw'
	 * }
	 */
	public function postStart() {
		$game = new Game();

	  $game->address = Input::get('address');
	  $game->uid     = Game::generateShortUid();
	  $game->started = new DateTime();

	  $game->save();
	  return Response::json(['uid' => $game->uid]);
	}

	/**
	 * GET /end?uid=<shortuid-partida>
	 *
	 * response: json
	 * {
	 *   err: false,
	 *   msg: 'Partida finalizada correctamente'
	 * }
	 */
	public function getEnd() {
		$uid = Input::get('uid', 0);

	  $game = Game::whereUid($uid)->first();

	  if (is_null($game))
	    App::abort(404);

	  if ( ! is_null($game->ended) )
	  	return Response::json(['err' => true, 'msg' => 'La partida ya finalizo'], 400);

	  $game->ended = new DateTime();

	  $game->save();

	  return Response::json(['err' => false, 'msg' => 'Partida finalizada correctamente']);
	}

	/**
	 * GET /server?uid=<shortuid-partida>
	 *
	 * response: json
	 * {
	 *   address: "10.0.0.100:8000"
	 * }
	 */
	public function getServer() {
	  $uid = Input::get('uid', 0);

	  $game = Game::whereUid($uid)->first();

	  if (is_null($game))
	    App::abort(404);


	  return Response::json(['address' => 'http://'.$game->address]);
	}

	public function missingMethod($parameters = array()) {
		App::abort(404);
	}
}
