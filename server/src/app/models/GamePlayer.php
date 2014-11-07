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
  		$db = DB::select(DB::raw('select tags.name as name, avg(time_elapsed) as data from problems
          join game_player_problem on problems.id = game_player_problem.problem_id
          join problem_tag on problem_tag.problem_id = problems.id
          join tags on tags.id = problem_tag.tag_id
          group by tags.id'));
  	}
  	else{
  		$db = DB::select(DB::raw("select tags.name as name, avg(time_elapsed) as data from problems
          join game_player_problem on problems.id = game_player_problem.problem_id
          join problem_tag on problem_tag.problem_id = problems.id
          join tags on tags.id = problem_tag.tag_id
          join game_player on game_player.id = game_player_problem.game_player_id
          join players on players.id = game_player.player_id
          where players.id = ".$player_id."
          group by tags.id")
      );

      

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
      tags.name as tag
      FROM game_player
      JOIN game_player_problem ON game_player.id = game_player_problem.game_player_id
      JOIN problems ON game_player_problem.`problem_id` = problems.`id`
      join problem_tag on problem_tag.problem_id = problems.id
      join tags on tags.id = problem_tag.tag_id
      GROUP BY tags.id'));
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