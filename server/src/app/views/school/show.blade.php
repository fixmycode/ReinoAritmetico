
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
<a href="{{ URL::to('school/' . $school->id).'/edit' }}">Editar</a>
     <h3>Colegio {{$school->name}}</h3>

<hr>

Nombre: {{$school->name}}
@stop