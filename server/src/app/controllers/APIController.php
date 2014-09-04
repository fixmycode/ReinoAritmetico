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
	public function getIdentify()
	 {

		$android_id = Input::get('id', 0);

	  $player = DB::table('players')->where('android_id', $android_id)
	              ->join('classrooms', 'players.classroom_id', '=', 'classrooms.id')
	              ->join('clients', 'classrooms.client_id', '=', 'clients.id')
	              ->join('character_type', 'players.character_type_id', '=', 'character_type.id')
	              ->select(DB::raw('players.name, classrooms.name as classroom, clients.name as school, character_type.uid as character_type'))
	              ->first();
	              
	  if ( is_null($player) )
	    App::abort(404);

		return json_encode($player);
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
	 * 		name: "player name",
	 * 		android_id: <android_uid>
	 * 		school: <school_id>,
	 * 		classroom: <classroom_id>,
	 *		character_type: <character_type_id>
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
	  $character_type = Input::get('character_type', 0);
	  $player      = new Player(Input::only('name', 'android_id'));
	  
	  $client = Client::findOrFail($client_id);
	  $classroom = $client->classrooms()->findOrFail($classroom_id);
	  $character_type = CharacterType::whereUid($character_type)->first();
	  
	  $player->client_id = $client->id;
	  $player->classroom_id = $classroom->id;
	  $player->character_type_id = $character_type->id;
	  $player->save();

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

	  $player = Player::whereAndroidId($android_id)->first();

	  if ( empty($player) )
			return Response::json(['err' => true, 'msg' => 'El estudiante no existe'], 404);	  	

	  $player->delete();

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
	  $game->uid = 'Done';

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
