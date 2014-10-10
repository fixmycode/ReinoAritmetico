<?php

class ClientApiController extends \BaseController {
   /**
   * GET /clients
   *
   * response: json
   * [{
   *   'id': 1,
   *   'name': 'Colegio Rubén Darío',
   *   'classrooms': [{
   *      'id': 1,
   *      'name': '1ro Básico',
   *   }, {
   *     'id': 2,
   *     'name': 2do Básico',
   *   }, {
   *    ... 
   *   }],
   * }, {
   *   ...
   * }]
   */
  public function getClients() {

    return Client::with('classrooms')->get();
  }

  public function missingMethod($parameters = array()) {
    App::abort(404);
  }
}