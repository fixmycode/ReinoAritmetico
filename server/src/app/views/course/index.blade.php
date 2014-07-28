
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
    <h3>Ver Cursos</h3>  
    <hr>
    <a href="course/create">Crear Curso</a>
    <ul>
      @forelse ($courses as $course)
      
      <li><a href="{{ URL::to('course/' . $course->id) }}">{{$course->name}}</a>  <button class="destroy" id="{{$course->id}}">x</button> </li>
      
    @empty
      {{-- empty expr --}}
    @endforelse  
    </ul>
@stop



@section("scripts")


 $(document).ready(function() {
     console.log( "ready!" );
     $(".destroy").on("click",function(){
       var $id = $(this).attr("id");
       $.ajax({
           url: "course/"+$id,
           type: "DELETE",
           success: function(response){
             console.log(response);


           }
       });
       $(".destroy").parent().remove();
     });
 });
  
@stop

