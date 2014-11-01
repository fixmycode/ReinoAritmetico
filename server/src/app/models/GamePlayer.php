<?php

class GamePlayer extends \Eloquent {
	protected $fillable = [];
  protected $table = "game_player";

  public function problems()
  {
    return $this->belongsToMany('Problem')->withPivot('time_elapsed', 'answer');
  }


  public static function averageProblemTime()
  {
  	return DB::select(DB::raw('select problem_type.type as name, avg(time_elapsed) as data from problems 
		join game_player_problem on problems.id = game_player_problem.problem_id
		join problem_type on problem_type.id = problems.problem_type_id
		group by problem_type_id')
  	);
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