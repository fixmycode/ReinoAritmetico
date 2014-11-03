<?php

class GamePlayer extends \Eloquent {
	protected $fillable = [];
  protected $table = "game_player";

  public function problems()
  {
    return $this->belongsToMany('Problem')->withPivot('time_elapsed', 'answer');
  }


  public static function averageProblemTime($player_id)
  {
  	$db = null;
  	if($player_id == null){
  		$db = DB::select(DB::raw('select problem_type.type as name, avg(time_elapsed) as data from problems
		join game_player_problem on problems.id = game_player_problem.problem_id
		join problem_type on problem_type.id = problems.problem_type_id
		group by problem_type_id'));
  	}
  	else{
  		$db = DB::select(DB::raw('select problem_type.type as name, avg(time_elapsed) as data from problems
		join game_player_problem on problems.id = game_player_problem.problem_id
		join problem_type on problem_type.id = problems.problem_type_id
		join game_player on game_player_problem.game_player_id = game_player.id
		join players on game_player.player_id = players.id
		where players.id = '.$player_id.'
		group by problem_type_id'));

  	}


  	array_map(function($n){
		  $n->data = [(float)$n->data];
		}, $db);

  	return $db;

  }

  public static function successRate(){
  	$db = DB::select(DB::raw('SELECT
							SUM(answer = correct_answer)/COUNT(answer)*100 as success_rate,
							SUM(answer = correct_answer) as correct,
							SUM(answer != correct_answer) as wrong,
							problem_type.`type`
							FROM game_player
							JOIN game_player_problem ON game_player.id = game_player_problem.game_player_id
							JOIN problems ON game_player_problem.`problem_id` = problems.`id`
							JOIN problem_type ON problem_type.`id` = problems.`problem_type_id`
							GROUP BY problem_type.id'));
  	return $db;
  }

  public function player()
  {
    return $this->belongsTo('Player');
  }

  public function game()
  {
    return $this->belongsTo('Game');
  }

  public function scopeFindBoth($query, $gameId, $playerId)
  {
    return $query->with('problems')->whereGameId($gameId)->wherePlayerId($playerId)->first();
  }
}