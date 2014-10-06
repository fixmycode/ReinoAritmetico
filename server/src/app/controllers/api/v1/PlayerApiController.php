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
    dd($player);
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
    $player->save();

    return Response::json(['err' => false, 'msg' => 'Estudiante creado exitosamente']);
  }

  


  public function missingMethod($parameters = array()) {
    App::abort(404);
  }

  public function postChangeType(){
    $android_id = Input::get('android_id', null);
    $type_id = Input::get('type_id',null);
    $number_of_paramters = count(Input::all());
    $player = Player::where('android_id','=',$android_id)->first();

    if( ($android_id == null || $type_id == null) || $number_of_paramters != 2 || $player == null)  {
      App::abort(400, "BAD REQUEST");
    }
    else{
      $character_type = CharacterType::find($type_id);
      if($character_type == null)
        App::abort(404, "NOT FOUND");
      else{
        
        if( ($player->credits - 100) < 0 ){
          
          App::abort(403, "FORBIDDEN");
        }
        else{
          if($player->character_type_id == $type_id)
            return Response::json("error: caracter igual al anterior", 404);
          else{
            
            $player->credits = $player->credits - 100;
            $player->character_type_id = $character_type->id;
            $player->save();
            
            return Response::json($player, 200);
          }
        }
      }
    }


  }
}

