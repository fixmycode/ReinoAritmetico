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
    return Response::json(['uid' => $game->uid, 'id' => $game->id]);
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
  public function postEnd() {
    $id     = Input::get('id', 0);
    $reward  = Input::json('reward', 0);
    $players = Input::json('players', array());
    $answers = Input::json('answers', array());

    if ( ! $game = Game::find($id)->first() )
      return Response::json(['err' => true, 'msg' => 'Partida no encontrada'], 404);

    // if ( ! is_null($game->ended) )
    //   return Response::json(['err' => true, 'msg' => 'La partida ya finalizo'], 400);

    $game->ended = new DateTime();
    $game->uid = 'Done';

    // Update players credits
    $player_ids = [];
    foreach ($players as $player) {
      if ( $p = Player::whereAndroidId($player['android_id'])->first() ){
        $p->addCredits($reward);
        $player_ids[] = $p->id;
      }
    }

    $game->players()->attach($player_ids);

    $game->save();

    // @TODO: save answers by players
    foreach ($players as $p) {
      // if ( $gpp = GamePlayer::wherePlayerId($p->id)->whereGameId($game->id)->first()) {
      //   $gpp->
      // }
    }
    return Response::json(['err' => false, 'msg' => 'Partida finalizada correctamente']);
  }

  public function missingMethod($parameters = array()) {
    App::abort(404);
  }
}