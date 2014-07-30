

Actualizar Curso
<hr>

{{ Form::open(array('url' => '/course/'.$course->id, 'method'=>"PUT")) }}

<label for="nombre">Nombre Curso</label>
  {{Form::text('name', "$course->name")}}
  <input type="submit" name="submit" value="Ingresar">
  
</form>

{{ Form::close() }}