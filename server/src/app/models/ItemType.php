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

    public function isWeapon(){
        return $this->id == 1;
    }

    public function isArmor(){
        return $this->id == 2;
    }

  

}