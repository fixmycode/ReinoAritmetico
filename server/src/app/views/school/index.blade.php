
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
    <h3>Ver Colegios</h3>  
    <hr>
    <a href="school/create">Crear Colegio</a>
    <ul>
      @forelse ($schools as $school)
      
      <li><a href="{{ URL::to('school/' . $school->id) }}">{{$school->name}}</a>  <button class="destroy" id="{{$school->id}}">x</button> </li>
      
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
           url: "school/"+$id,
           type: "DELETE",
           success: function(response){
             console.log(response);


           }
       });
       $(".destroy").parent().remove();
     });
 });
  
@stop

