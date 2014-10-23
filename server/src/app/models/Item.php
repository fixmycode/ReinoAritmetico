<?php

class Item extends \Eloquent {

  public $timestamps = false;
  public $equipped = false;



  // Add your validation rules here
  public static $rules = [
    // 'title' => 'required'
  ];

  // Don't forget to fill this array
  protected $fillable = ['name'];

  /**
   * 
   */

  public function characterType(){
    return $this->belongsTo('CharacterType');
  }

  public function itemType(){
    return $this->belongsTo('ItemType');
  }

  public function players(){
    return $this->belongsToMany('Player');
  }
  

}