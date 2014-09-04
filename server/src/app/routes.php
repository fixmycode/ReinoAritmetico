<?php

Route::get('dashboard', ['as' => 'dashboard', function(){
  return View::make('dashboard')
          ->with('breadcrumbs', Breadcrumbs::render('dashboard'))
          ->with('title', 'Dashboard');
}]);

Route::resource('clientss', 'ClientsController');
Route::resource('clientss.classrooms', 'ClientsClassroomsController');
Route::resource('questions', 'ProblemController');

Route::get("problems/{number}", function($number){
  $problems = DB::table('problems')->take($number)->get();
  return $problems;


});


Route::controller('/', 'APIController');

