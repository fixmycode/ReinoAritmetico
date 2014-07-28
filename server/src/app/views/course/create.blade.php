
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
    <h3>Crear Curso</h3>
    <hr>


    <form class="form-horizontal" action="/course" method="POST">
    <label for="nombre">Nombre Curso</label>
      {{Form::text('name')}}
      <input type="submit" name="submit" value="Ingresar">
      
    </form>
@stop



