<?php

class GameApiController extends \BaseController {
  /**
   * URL: POST /start 
   * data:
   * {
   *    address: 10.0.0.1:8000,
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
    $reward = Input::get('reward', 0);
    $players = Input::get('players', array());

    // Update players credits
    foreach ($players as $player_uid) {
      $player = Player::whereAndroidId($player_uid)->first();
      if ( ! $player) continue;
      $player->credits += $reward;
      $player->save();
    }

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

  public function missingMethod($parameters = array()) {
    App::abort(404);
  }
}