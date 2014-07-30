
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
<a href="{{ URL::route('clientss.classrooms.edit', [$classroom->client->id, $classroom->id]) }}">Editar</a>
     <h3>Curso {{$classroom->name}}</h3>

<hr>

Nombre: {{$classroom->name}}
@stop