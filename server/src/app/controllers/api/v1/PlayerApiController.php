<?php

class PlayerApiController extends \BaseController {
  /**
   * GET /identify?id=<android_uid-del-estudiante>
   *
   * response: json
   * {
   *  name: "nombre del estudiante",
   *  classroom: "1ro Basico",
   *  school: "Ruben Dario"
   * }
   */
  public function getIdentify()
   {

    $android_id = Input::get('id', 0);

    $player = DB::table('players')->where('android_id', $android_id)
                ->join('classrooms', 'players.classroom_id', '=', 'classrooms.id')
                ->join('clients', 'classrooms.client_id', '=', 'clients.id')
                ->join('character_type', 'players.character_type_id', '=', 'character_type.id')
                ->select(DB::raw('players.name, classrooms.name as classroom, clients.name as school, character_type.uid as character_type, players.credits'))
                ->first();

    if ( is_null($player) )
      App::abort(404);

    return json_encode($player);
  }

  public function getByid()
  {

    $player_id = Input::get('player_id');

    $player = Player::find($player_id);
    if(is_null($player))
      App::abort(404);
    return Response::json($player);
  }

  /**
   * POST /register
   * data:
   * {
   *    name: "player name",
   *    android_id: <android_uid>
   *    school: <school_id>,
   *    classroom: <classroom_id>,
   *    character_type: <character_type_id>
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
    $player->credits = 50;
    $player->save();

    $armor  = Item::find(1);
    $weapon = Item::find(2);
    $player->buy($armor);
    $player->buy($weapon);
    $player = Player::find($player->id);
    $player->equip($armor);
    $player->equip($weapon);

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


  public function missingMethod($parameters = array()) {
    App::abort(404);
  }

  public function postChangeType(){
    $android_id = Input::get('android_id', null);
    $type_id = Input::get('type_id',null);
    $number_of_paramters = count(Input::all());
    $player = Player::where('android_id','=',$android_id)->first();

    if( ($android_id == null || $type_id == null) || $number_of_paramters != 2 || $player == null)
      App::abort(400, "BAD REQUEST");

    $character_type = CharacterType::whereUid($type_id)->first();
    if($character_type == null)
      App::abort(404, "NOT FOUND");

    if( ($player->credits - 500) < 0 )
      App::abort(403, "FORBIDDEN");

    if($player->character_type->uid == $type_id)
      return Response::json(array('msg' => 'error: tipo igual al anterior'), 404);

    $player->armor_id = 1;
    $player->weapon_id = 2;
    $player->credits = $player->credits - 500;
    $character_type->players()->save($player);

    return Response::json(Player::with('characterType')->find($player->id)->toArray(), 200);
  }
}

