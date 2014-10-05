<?php

class ItemApiController extends \BaseController {

	
	/**
	 * lista todos los items habidos y por haber en el sistema y posee una serie de filtros que se implementarán como parámetros GET:
	 * **/
	public function getList()
	{
		$type = Input::get("type", null);//character_type
		$kind = Input::get("kind", null);//item_type
		$player_uid = Input::get("player", null);
		
		$items = null;
		if($type != null && $kind != null && $player_uid != null){
			$items = Item::join('item_type','items.item_type_id','=','items.id')
				->join('character_type', 'items.character_type_id','=','character_type.id')
				->where('item_type.id','=',$kind)
				->where('character_type.id','=',$type)
				->get();
		}

		return Response::json($items);
		
	}

	public function getImage(){
		$item_id = Input::get('id', null);
		if($item_id == null)
			App::abort(404);
		else{
			$item = Item::find($item_id);
			if($item->image_path != null){
				Response::download($item->image_path);
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