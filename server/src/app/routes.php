<?php
Route::get('users', function()
{
    return 'Users!';
});


Route::resource('school', 'SchoolController');
Route::resource('course', 'CourseController');



Route::controller('/', 'APIController');




