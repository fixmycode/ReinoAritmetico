
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
    <h3>Crear Colegio</h3>
    <hr>


    <form class="form-horizontal" action="/school" method="POST">
    <label for="nombre">Nombre Colegio</label>
      {{Form::text('name')}}
      <input type="submit" name="submit" value="Ingresar">
      
    </form>
@stop



