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
        return $this->belongsTo("Item","armor_id");
    }

    public function weapon()
    {
        return $this->belongsTo("Item", "weapon_id");
    }

    public function characterType(){
      return $this->belongsTo('CharacterType');
    }

    public function games()
    {
        return $this->belongsToMany('Game')->withPivot('id');
    }

    public function GamePlayer()
    {
        return $this->hasMany('GamePlayer');
    }

    public function buy(Item $item)
    {
        if($this->hasInInventory($item->id)) // the this already owns it
          return Response::json(array('err' => true, 'msg' => 'El jugador ya posee este item'), 405);

        if( $this->credits < $item->price )
          return Response::json(array('err' => true, 'msg' => 'El jugador no posee creditos suficientes'), 403);

        $this->credits -= $item->price;
        $this->items()->save($item);
        $this->save();
        return Response::json($this, 200);
    }

    public function hasInInventory($item_id){
        $found = false;
        $items = $this->items;

        if($items != null){
            foreach($items as $item){
                if($item->id == $item_id)
                    return true;
            }
        }

        return false;
    }

    public function hasEquipped($item_id){
        $armorItem = $this->armor;
        $weaponItem = $this->weapon;

        if( ($armorItem != null && $armorItem->id == $item_id) || ($weaponItem != null && $weaponItem->id == $item_id))
            return true;
        else
            return false;
    }

    public function unEquip($item_id){
        $armorItem = $this->armor;
        $weaponItem = $this->weapon;

        if($armorItem != null && $armorItem->id == $item_id){
            $this->armor_id = 1;
        }
        if($weaponItem != null && $weaponItem->id == $item_id){
            $this->weapon_id = 2;
        }
        $this->save();
    }

    public function equip(Item $item) {
      echo "#{$this->id} #{$item->id}";

      if( ! $this->hasInInventory($item->id))
        return Response::json(array('err' => true, 'msg' => 'El jugador no posee este item'), 403);

      if($this->hasEquipped($item->id)) {
        $this->unEquip($item->id);
        return Response::json(array('err' => false, 'msg' => 'El item ha sido desequipado.'));
      }

      if ( $item->itemType->isWeapon() ) $this->weapon()->associate($item);
      if ( $item->itemType->isArmor() ) $this->armor()->associate($item);

      $this->save();

      return Response::json(array('err' => false, 'msg' => 'El item ha sido equipado.'));
    }

    public function addCredits($reward = 0) {
        $this->credits += $reward;
        $this->save();
    }

    public static function getPlayers($players = [])
    {
        $result = [];
        foreach ($players as $p) {
          if( ! ($player = Player::with('armor', 'weapon')->whereAndroidId($p)->first()) )
            return Response::json(['err' => true, 'msg' => 'Error de jugadores, alguien no existe'], 404);

            if ( ! $player->armor_id || ! $player->weapon_id) {
              $armor = Item::find(1); // Casco por default
              $weapon = Item::find(2); // Arma por default
              $result['players'][$p] = [
                'head' => [
                  'resource' => url('/').'/'.$armor->image_path,
                  'center' => [
                    'x' => $armor->headX,
                    'y' => $armor->headY,
                  ]
                ],
                'hand' => [
                  'resource' => url('/').'/'.$weapon->image_path,
                  'center' => [
                    'x' => $weapon->handX,
                    'y' => $weapon->handY,
                  ]
                ]
              ];
            }else {
              $result['players'][$p] = [
                'head' => [
                  'resource' => url('/').'/'.$player->armor->image_path,
                  'center' => [
                    'x' => $player->armor->headX,
                    'y' => $player->armor->headY,
                  ]
                ],
                'hand' => [
                  'resource' => url('/').'/'.$player->weapon->image_path,
                  'center' => [
                    'x' => $player->weapon->handX,
                    'y' => $player->weapon->handY,
                  ]
                ]
              ];
            }
        }
        return $result;
    }

}