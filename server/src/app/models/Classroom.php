<?php

class Classroom extends \Eloquent {

  public $timestamps = false;

   protected $hidden = array('client_id');

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['name'];

  /**
   * 
   */
  public function client()
  {
    return $this->belongsTo('Client');
  }

  /**
   * 
   */
  public function players()
  {
    return $this->hasMany('Player');
  }

}