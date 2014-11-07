<?php

class Problem extends \Eloquent {

  public $timestamps = false;

  // Add your validation rules here
  public static $rules = [
    // 'title' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['name'];

  /**
   *
   */

  public function problem_type(){
    return $this->belongsTo('ProblemType');

  }

  public function tags()
  {
    return $this->belongsToMany("Tag");
  }

  public function game_player()
  {
    return $this->belongsToMany('GamePlayer')->withPivot('time_elapsed', 'answer');
  }


}