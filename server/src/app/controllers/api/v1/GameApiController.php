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
    $game_id     = Input::get('id', 0);
    $reward  = Input::json('reward', 0);
    $players = Input::json('players', []);
    $answers = Input::json('answers', []);

    if ( ! $game = Game::find($game_id)->first() )
      return Response::json(['err' => true, 'msg' => 'Partida no encontrada'], 404);

    // if ( ! is_null($game->ended) )
    //   return Response::json(['err' => true, 'msg' => 'La partida ya finalizo'], 400);

    $game->ended = new DateTime();
    $game->uid = 'Done';
    $game->save();


    // Update players credits
    $player_ids = [];
    foreach ($players as $android_id) {
      if ( $p = Player::whereAndroidId($android_id)->first() ){
        $p->addCredits($reward);
        $player_ids[$android_id] = $p->id;
      }
    }

    $game->players()->attach(array_values($player_ids));




    foreach ($player_ids as $android_id => $id) {
      if ( $gpp = GamePlayer::wherePlayerId($id)->whereGameId($game->id)->first() ){
        foreach ($answers[$android_id] as $answer) {
          $gpp->problems()->attach($answer['id'], ['answer' => $answer['answer'], 'time_elapsed' => $answer['elapsed_time']]);
        }
      }
    }
    return Response::json(['err' => false, 'msg' => 'Partida finalizada correctamente']);
  }

  public function missingMethod($parameters = array()) {
    App::abort(404);
  }
}