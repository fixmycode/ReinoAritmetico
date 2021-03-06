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
  		$db = DB::select(DB::raw('select tags.name as name, ROUND(avg(time_elapsed), 3) as data from problems
          join game_player_problem on problems.id = game_player_problem.problem_id
          join problem_tag on problem_tag.problem_id = problems.id
          join tags on tags.id = problem_tag.tag_id
          group by tags.id'));
  	}
  	else{
  		$db = DB::select(DB::raw("select tags.name as name, ROUND(avg(time_elapsed), 3) as data from problems
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
      ROUND(SUM(answer = correct_answer)/COUNT(answer)*100, 3) as success_rate,
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

  public static function correctAnswers($player_id){

    $db = DB::select(DB::raw('SELECT
    SUM(answer = correct_answer) as correct,
    SUM(answer != correct_answer) as wrong
    from game_player_problem
    join game_player on game_player.id = game_player_problem.game_player_id
    join problems on game_player_problem.problem_id = problems.id
    where game_player.player_id ='.$player_id));
    return $db;

  }

  public static function getPlayersByGameUid($gameId){
    $db = DB::select(DB::raw(
      "select players.id, players.name from players
      join game_player on game_player.player_id = players.id
      join games on games.id = game_player.game_id = games.id
      where game_player.game_id = ".$gameId
    ));
    return $db;
  }

  public static function getTagsByStudents($gameId){
    $db = DB::select(DB::raw(
      "select  tags.name, players.name,count(*) from games
  join game_player on game_player.game_id = games.id
  join game_player_problem on game_player.id = game_player_problem.game_player_id
  join problems on problems.id = game_player_problem.problem_id
  join problem_tag on problem_tag.problem_id = problems.id
  join tags on tags.id = problem_tag.tag_id
  join players on players.id = game_player.id
  where games.id = ".$gameId."
  group by tags.name, game_player_problem.game_player_id"
      ));

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