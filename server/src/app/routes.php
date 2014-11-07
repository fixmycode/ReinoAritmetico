<?php

Route::any('/', function(){
  return View::make('hello');
});

Route::get('demo',function(){

});

Route::get('dashboard', ['as' => 'dashboard', function(){
  return View::make('dashboard')
          ->with('breadcrumbs', Breadcrumbs::render('dashboard'))
          ->with('title', 'Dashboard');
}]);

Route::resource('clientss', 'ClientsController');
Route::resource('clientss.classrooms', 'ClientsClassroomsController');
Route::resource('problems', 'ProblemController');
Route::resource('items', 'ItemController');
Route::resource('tags', 'TagController');
Route::controller('reports', 'ReportController');


/**
 * La nueva forma de hacer llamados a la API deben conformarse al siguiente
 * estandar
 *
 * URL: www.example.com/api/v1/{scope}/{method}
 *
 * donde
 * {scope}: es el ambito del cual la llamada pertence, ej: server, player, game etc
 * {method}: metodo del server, ej: identify
 *
 * Ej de URL a la nueva
 * URL: Identify
 * Antigua: GET localhost:8000/identify?id=<android_uid-del-estudiante>
 * Nueva:   GET localhost:8000/api/v1/player/identify?id=<android_uid-del-estudiante>
 *
 * Los nuevos scopes por ahora son,
 * 1) client
 * 2) game
 * 3) player
 * 4) problem
 * 5) server
 *
 * Por favor notar que estan en minisculas
 */
Route::group(['prefix' => 'api/v1'], function(){
  Route::controller('client', 'ClientApiController');
  Route::controller('game', 'GameApiController');
  Route::controller('player', 'PlayerApiController');
  Route::controller('problem', 'ProblemApiController');
  Route::controller('server', 'ServerApiController');
  Route::controller('item', 'ItemApiController');
});


