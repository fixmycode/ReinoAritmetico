<?php

class Player extends \Eloquent {

  public $timestamps = false;

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['name', 'android_id'];

  /**
   * 
   */
  public function classroom()
  {
    return $this->belongsTo('Classroom');
  }

  public function items(){
    return $this->belongsToMany('Item');
  }

  public function characterType(){
    return $this->belongsTo('CharacterType');
  }

}