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
    //$itemType = DB::table('item_type')->select('id', 'type')->get();
    $itemTypes = ItemType::all();
    
    
    return View::make('items.index')
                  ->with('newItem', new Item())
                  ->with('item_type', $itemTypes)
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

    $item = new Problem();
    $item->item = Input::get('item');
    $item->correct_answer = Input::get('correct_answer');
    $item->item_type_id = 1; //Input::get('item_type_id');
    $item->difficulty = Input::get('difficulty');
    $item->save();
    
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
    $itemType = ProblemType::lists("type","id");
    $difficulty = array("1"=>"Fácil", 2 => 'Medio', 3 => 'Difícil');

    return View::make('items.partials.edit')
                  ->with('item_type',$itemType )
                  ->with('difficulty', $difficulty)
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
    $item = Item::find($id);
    $item->item = Input::get('item');
    $item->correct_answer = Input::get('correct_answer');
    $item->item_type_id = 1;//Input::get('item_type_id');
    $item->difficulty = Input::get('difficulty');
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
