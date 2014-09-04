<?php

class ProblemType extends \Eloquent {

  public $timestamps = false;
  protected $table = 'problem_type';

  // Add your validation rules here
  public static $rules = [
    // 'title' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['name'];

  /**
   * 
   */
  public function classrooms()
  {
    return $this->hasMany('Classroom');
  }

}