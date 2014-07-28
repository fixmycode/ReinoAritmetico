

Actualizar Colegio
<hr>

{{ Form::open(array('url' => '/school/'.$school->id, 'method'=>"PUT")) }}

<label for="nombre">Nombre Colegio</label>
  {{Form::text('name', "$school->name")}}
  <input type="submit" name="submit" value="Ingresar">
  
</form>

{{ Form::close() }}