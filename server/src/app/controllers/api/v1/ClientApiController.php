<?php

class ClientApiController extends \BaseController {
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
}