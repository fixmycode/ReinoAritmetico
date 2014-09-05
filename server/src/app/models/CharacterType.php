<?php

class CharacterType extends \Eloquent {

  public $timestamps = false;
  protected $table = 'character_type';

  // Add your validation rules here
  public static $rules = [
    // 'title' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['name'];

  /**
   * 
   */
  

}