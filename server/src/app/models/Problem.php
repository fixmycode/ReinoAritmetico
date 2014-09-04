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
  

}