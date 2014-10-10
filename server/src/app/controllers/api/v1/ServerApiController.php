<?php

class ServerApiController extends \BaseController {

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