<?php

class Player extends \Eloquent {

  public $timestamps = false;

	// Add your validation rules here
	public static $rules = [
		// 'title' => 'required'
	];

	// Don't forget to fill this array
	protected $fillable = ['name', 'android_id'];


    public function classroom()
    {
      return $this->belongsTo('Classroom');
    }

    public function items(){
      return $this->belongsToMany('Item');
    }

    public function armor()
    {
        return $this->belongsTo("Item","armor_id")->first();
    }

    public function weapon()
    {
        return $this->belongsTo("Item", "weapon_id")->first();
    }

    public function characterType(){
      return $this->belongsTo('CharacterType');
    }

    public function hasInInventory($item_id){
        $found = false;
        $items = $this->items()->get();
        if($items != null){
            foreach($items as $item){
                if($item->id == $item_id)
                    $found=true;
            }
        }

        return $found;
    }

    public function hasEquipped($item_id){
        $armorItem = $this->armor();
        $weaponItem = $this->weapon();

        if( ($armorItem != null && $armorItem->id == $item_id) || ($weaponItem != null && $weaponItem->id == $item_id))
            return true;
        else
            return false;
    }

    public function unEquip($item_id){
        $armorItem = $this->armor();
        $weaponItem = $this->weapon();
        if($armorItem != null && $armorItem->id == $item_id){
            $this->armor_id = null;
        }
        if($weaponItem != null && $weaponItem->id == $item_id){
            $this->weapon_id = null;
        }
        $this->save();
    }


}