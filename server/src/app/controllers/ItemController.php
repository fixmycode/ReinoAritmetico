<?php

class ItemController extends \BaseController {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {

    $items = Item::all();
    $characterTypes = CharacterType::all();
    //$itemType = DB::table('item_type')->select('id', 'type')->get();
    $itemTypes = ItemType::all();
    $selectItemTypes = null;
    $selectCharacterTypes = null;

    foreach ($itemTypes as $itemType) {
      $selectItemTypes[$itemType->id]=$itemType->nombre;
    }
    foreach ($characterTypes as $characterType) {
      $selectCharacterTypes[$characterType->id]=$characterType->name;
    }
    $selectCharacterTypes['null'] = 'Ninguno';


    return View::make('items.index')
                  ->with('newItem', new Item())
                  ->with('item_type', $selectItemTypes)
                  ->with('character_type', $selectCharacterTypes)
                  ->with('items', $items);

  }


  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {


  }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store()
  {
    $file = Input::file('image');
    $name = $file->getClientOriginalName();
    $armor_type_id = ItemType::getArmorId();
    $weapon_type_id = ItemType::getWeaponId();

    $path = '';
    $relative_path = '';

    if(Input::get('item_type') == $weapon_type_id){
      $path = public_path() . '/upload/items/armas';
      $relative_path = "upload/items/armas/".$name;

    }
    if(Input::get('item_type') == $armor_type_id){
      $path = public_path() . '/upload/items/armaduras';
      $relative_path = "upload/items/armaduras/".$name;
    }



    $upload_success = Input::file('image')->move($path, $name);
    if($upload_success){
      $item = new Item();
      $chartype = Input::get('character_type');
      if ($chartype != 'null') {
          $item->character_type_id = $chartype;
      }

      $item->nombre = Input::get('nombre');
      $item->description = Input::get('description');
      $item->headX = Input::get('headX', 0);
      $item->headY = Input::get('headY', 0);
      $item->handX = Input::get('handX', 0);
      $item->handY = Input::get('handY', 0);
      $item->item_type_id = Input::get('item_type');
      $item->price = Input::get('price');

      $item->image_path = $relative_path;
      $item->save();
    }

    return Redirect::route('items.index');
  }


  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    //
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $item = Item::findOrFail($id);

    $selectItemTypes = ItemType::lists("nombre","id");

    $selectCharacterTypes = CharacterType::lists("name","id");
    $selectCharacterTypes['null'] = 'Ninguno';


    return View::make('items.partials.edit')
                  ->with('item_type', $selectItemTypes)
                  ->with('character_type', $selectCharacterTypes)
                  ->with('item', $item);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    $file = Input::file('image');
    $item = Item::findOrFail($id);

    if ($file){
      $name = $file->getClientOriginalName();

      $armor_type_id = ItemType::getArmorId();
      $weapon_type_id = ItemType::getWeaponId();

      $path = '';
      $relative_path = '';

      if(Input::get('item_type') == $weapon_type_id){
        $path = public_path() . '/upload/items/armas';
        $relative_path = "upload/items/armas/".$name;
      }
      if(Input::get('item_type') == $armor_type_id){
        $path = public_path() . '/upload/items/armaduras';
        $relative_path = "upload/items/armaduras/".$name;
      }
      $upload_success = Input::file('image')->move($path, $name);
      $item->image_path = $relative_path;
    }

    $item->nombre = Input::get('nombre');
    $item->description = Input::get('description');
    $item->item_type_id = Input::get('item_type');
    $item->headX = Input::get('headX', 0);
    $item->headY = Input::get('headY', 0);
    $item->handX = Input::get('handX', 0);
    $item->handY = Input::get('handY', 0);
    $item->price = Input::get('price');
    $chartype = Input::get('character_type');
    if ($chartype != 'null') {
        $item->character_type_id = $chartype;
    }

    $item->save();

    return Redirect::route('items.index');

  }


  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {

    $item = Item::findOrFail($id);

    $item->delete();

  }


}
