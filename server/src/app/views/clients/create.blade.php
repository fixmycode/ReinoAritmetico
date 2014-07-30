
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
    <h3>Crear Colegio</h3>
    <hr>


    {{ Form::model($client, array('url' => URL::route('clientss.store'), 'method' => 'POST')) }}
    <label for="nombre">Nombre Colegio</label>
      {{ Form::text('name', $client->name) }}
      <input type="submit" name="submit" value="Ingresar">
      
    {{ Form::close() }}
@stop



