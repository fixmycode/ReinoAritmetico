<?php

Route::get('dashboard', ['as' => 'dashboard', function(){
  return View::make('dashboard')
          ->with('breadcrumbs', Breadcrumbs::render('dashboard'))
          ->with('title', 'Dashboard');
}]);

Route::resource('clientss', 'ClientsController');
Route::resource('clientss.classrooms', 'ClientsClassroomsController');
Route::resource('problems', 'ProblemController');

Route::group(['prefix' => 'api/v1'], function(){
  Route::controller('client', 'ProblemApiController');
  Route::controller('game', 'GameApiController');
  Route::controller('player', 'PlayerApiController');
  Route::controller('problem', 'ProblemApiController');
  Route::controller('server', 'ServerApiController');

});


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
 */