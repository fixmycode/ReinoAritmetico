<?php

class ItemApiController extends \BaseController {

	public function getList()	{
		$type = Input::get("type", null);//character_type
		$kind = Input::get("kind", null);//item_type
		$player_uid = DB::connection()->getPdo()->quote(Input::get("player"));

		// SELECT items.id, items.nombre, items.description, items.image_path, items.price, items.item_type_id, character_type.uid, (CASE WHEN (players.android_id = 'asdf') THEN 1 ELSE 0 END) as comprado from items
		// LEFT JOIN item_player ON items.id = item_player.`item_id`
		// LEFT JOIN players ON players.id = item_player.`player_id`
		// JOIN item_type ON items.item_type_id = item_type.id
		// JOIN character_type ON items.`character_type_id` = character_type.id;

		$items = Item::leftJoin('item_player', 'items.id', '=', 'item_player.item_id')
								 ->leftJoin('players', 'players.id', '=', 'item_player.player_id')
								 ->join('character_type', 'character_type.id', '=', 'items.character_type_id')
				->select('items.id',
				         'items.nombre',
				         'items.description',
				         'items.image_path',
				         'items.price',
				         'items.item_type_id',
				         'character_type.uid as character_type_id',
                 DB::raw('(CASE WHEN (players.armor_id = items.id OR players.weapon_id = items.id) THEN true ELSE false END) as equipped'),
				         DB::raw('(CASE WHEN (players.android_id = '.$player_uid.') THEN true ELSE false END) as comprado'));

		if ( ! is_null($type) ) {
			$items = $items->where('character_type.uid', '=', $type);
		}
		if ( ! is_null($kind) ) {
			$items = $items->where('items.item_type_id', '=', $kind);
		}
    if ( ! is_null(Input::get('player', null))) {
      $items = $items->where('players.android_id', '=', Input::get('player'));
    }

		return Response::json($items->get());
	}

	public function getImage(){
		$item_id = Input::get('id', 0);

		$item = Item::find($item_id);
		if ( ! $item)
      return Response::json(array('err' => true, 'msg' => 'Item no encontrados'), 404);

		if($item->image_path != null){
			$response = Response::make(File::get(public_path($item->image_path)));
			$response->header('Content-Type', 'image/png');
			return $response;
		}
	}

	public function postBuy(){
		$android_id = Input::get('android_id',null);
		$item_id = Input::get('item_id',null);
		$number_of_paramters = count(Input::all());

		if( ($android_id == null || $item_id == null && $number_of_paramters != 2))
      return Response::json(array('err' => true, 'msg' => 'Android ID e item ID son obligatorios'), 400);

		$player = Player::where('android_id','=',$android_id)->first();
		$item = Item::find($item_id);

		if($player == null || $item == null)
      return Response::json(array('err' => true, 'msg' => 'Jugador o item no encontrados'), 404);

		if($player->hasInInventory($item->id)) // the player already owns it
			return Response::json(array('err' => true, 'msg' => 'El jugador ya posee este item'), 405);

		if( $player->credits < $item->price )
      return Response::json(array('err' => true, 'msg' => 'El jugador no posee creditos suficientes'), 403);

    $player->credits -= $item->price;
    $player->save();
    $player->items()->save($item);
    return Response::json($player, 200);
	}

  public function postEquip(){
    $android_id  = Input::get("android_id", 0);
    $item_id  = Input::get("item_id", 0);

    $player = Player::whereAndroidId($android_id)->first();
    $item = Item::find($item_id);

    if($player == null || $item == null)
      return Response::json(array('err' => true, 'msg' => 'Jugador o item no encontrados'), 404);

    if( ! $player->hasInInventory($item_id))
      return Response::json(array('err' => true, 'msg' => 'El jugador no posee este item'), 403);

  	if($player->hasEquipped($item_id)){
  		$player->unEquip($item_id);
      return Response::json(array('err' => false, 'msg' => 'El item ha sido desequipado.'));
  	}else{
  		if($item->itemType->first()->isWeapon()){
		    $player->weapon_id = $item->id;
		    $player->save();
  		}
  		else if ( $item->itemType->first()->isArmor()){
		    $player->armor_id = $item->id;
		    $player->save();
  		}
      return Response::json(array('err' => false, 'msg' => 'El item ha sido equipado.'));
  	}
  }
}