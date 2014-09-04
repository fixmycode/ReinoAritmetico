<?php

Route::get('dashboard', ['as' => 'dashboard', function(){
  return View::make('dashboard')
          ->with('breadcrumbs', Breadcrumbs::render('dashboard'))
          ->with('title', 'Dashboard');
}]);

Route::resource('clientss', 'ClientsController');
Route::resource('clientss.classrooms', 'ClientsClassroomsController');
Route::resource('problems', 'ProblemController');

Route::get("api/problems", function(){
  $quantity = Input::get('quantity');
  $difficulty = Input::get('difficulty');
  
  if($quantity != null && $difficulty != null){
    $problems = DB::table('problems')->where('difficulty','=',$difficulty)->take($quantity)->get();  
    return Response::json($problems);
  }
  
  return null;
});




Route::controller('/', 'APIController');

