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
                ->select(DB::raw('players.name, classrooms.name as classroom, clients.name as school, character_type.uid as character_type'))
                ->first();
    
    if ( is_null($player) )
      App::abort(404);
    
    return json_encode($player);
  }

  public function getById()
  {
    dd('asdf');
    $player_id = Input::get('player_id');
    $player = Player::find($id);
    if(is_null($player))
      App::abort(404);
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
    $player->save();

    return Response::json(['err' => false, 'msg' => 'Estudiante creado exitosamente']);
  }

  


  public function missingMethod($parameters = array()) {
    App::abort(404);
  }
}