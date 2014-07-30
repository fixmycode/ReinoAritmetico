
@extends('layouts.master')

@section('sidebar')
    @parent

    <p></p>
@stop

@section('content')
<a href="{{ URL::route('clientss.edit', $client->id) }}">Editar</a>
     <h3>Colegio {{$client->name}}</h3>

<hr>

Nombre: {{$client->name}}

<a href="{{ URL::route('clientss.index') }}">Atras</a>
@stop