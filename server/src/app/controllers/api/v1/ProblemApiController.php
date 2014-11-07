<?php


class ProblemApiController extends \BaseController {
  public function missingMethod($parameters = array()) {
    App::abort(404);
  }

  public function getProblemTypeList()
  {
    $problemType = ProblemType::all();
    if($problemType == null)
      App::abort(404);
    return Response::json($problemType);

  }
}