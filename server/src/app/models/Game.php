<?php

class Game extends \Eloquent {

  public $timestamps = false;

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = [];

  public static function generateShortUid()
  {
    return trim(strtolower(str_replace(range(0,9), 'x', str_random(3))));
  }

}