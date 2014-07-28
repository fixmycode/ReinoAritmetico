
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
<a href="{{ URL::to('course/' . $course->id).'/edit' }}">Editar</a>
     <h3>Curso {{$course->name}}</h3>

<hr>

Nombre: {{$course->name}}
@stop