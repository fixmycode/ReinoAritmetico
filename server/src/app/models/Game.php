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
    $size = rand(8,10);
    return trim(strtolower(str_replace(range(0,9),'', str_random($size))));
  }

}