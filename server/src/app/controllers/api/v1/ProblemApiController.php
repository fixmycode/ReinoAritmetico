<?php


class ProblemApiController extends \BaseController {
  public function getQuestions()
  {
    $quantity   = Input::get('quantity');
    $difficulty = Input::get('difficulty');
    
    if($quantity != null && $difficulty != null){
      $problems = DB::table('problems')->where('difficulty','=',$difficulty)->orderByRaw("RAND()")->take($quantity)->get();  
      return Response::json($problems);
    }
    
    return null;
  }

  public function missingMethod($parameters = array()) {
    App::abort(404);
  }
}