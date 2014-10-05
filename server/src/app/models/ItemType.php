<?php

class ItemType extends \Eloquent {

  public $timestamps = false;
  protected $table = 'item_type';

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