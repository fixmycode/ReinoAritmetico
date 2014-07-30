

Actualizar Colegio
<hr>

{{ Form::open(array('url' => URL::route('clientss.update', $client->id), 'method'=>"PUT")) }}

<label for="nombre">Nombre Colegio</label>
  {{Form::text('name', $client->name)}}
  <input type="submit" name="submit" value="Guardar">
  
</form>

{{ Form::close() }}