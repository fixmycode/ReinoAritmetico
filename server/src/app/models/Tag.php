<?php

class Tag extends \Eloquent {

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

  public function problems(){
    return $this->belongsToMany('Problem');

  }

  public static function getNombres(){
    return DB::select(DB::raw('select tags.name from tags'));
  }
  

}