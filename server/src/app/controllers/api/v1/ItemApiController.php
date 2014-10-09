<?php

class ItemApiController extends \BaseController {

	
	/**
	 * lista todos los items habidos y por haber en el sistema y posee una serie de filtros que se implementarán como parámetros GET:
	 * **/
	public function getList()
	{
		$type = Input::get("type", null);//character_type
		$kind = Input::get("kind", null);//item_type
		$player_uid = DB::connection()->getPdo()->quote(Input::get("player", ''));

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
				         DB::raw('(CASE WHEN (players.android_id = "'.$player_uid.'") THEN 1 ELSE 0 END) as comprado'));

		if ( ! is_null($type) ) {
			$items = $items->where('items.character_type_id','=', $type);
		}
		if ( ! is_null($kind) ) {
			$items = $items->where('items.item_type_id','=', $kind);
		}
		
		return Response::json($items->get());
	}

	public function getImage(){
		$item_id = Input::get('id', null);
		if($item_id == null)
			App::abort(404);
		else{
			$item = Item::find($item_id);
			if($item->image_path != null){
				return Response::download(public_path($item->image_path));
			}
		}

	}

	public function postBuy(){

		$android_id = Input::get('android_id',null);
		$item_id = Input::get('item_id',null);
		$number_of_paramters = count(Input::all());
		
		
		if( ($android_id == null || $item_id == null && $number_of_paramters != 2)){
			App::abort(400, "BAD REQUEST");
		}
		else{

			$player = Player::where('android_id','=',$android_id)->first();
			$item = Item::find($item_id);

			if($player != null && $item != null){	

					$player_has = false;
					$player_items = $player->items()->get();
					foreach ($player_items as $player_item) {
						if($player_item->id == $item->id){
							$player_has = true;
						}

							
					}

					if($player_has){
						App::abort(405, "Method NOT ALLOWED");
					}
					else{
						$player_credits = $player->credits;
						$item_price = $item->price;
						if( ($player_credits-$item_price) < 0 )
							App::abort(403, "FORBIDDEN");
						else{
						   $player->credits = $player_credits-$item_price;
						   $player->save();
						   $player->items()->save($item);
						   return Response::json($player, 200);

						}
					}
			}
			else
				App::abort(404, "NOT FOUND");
		}
	}

	

}