<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title">Editar Pregunta</h4>
    </div>
    {{ Form::open(array('url' => URL::route('problems.update', $problem->id), 'method'=>"PUT")) }}
    <div class="modal-body">
    <label for="exampleInputEmail1">Pregunta</label>
          {{ Form::text('problem', $problem->problem, ['class' => 'form-control']) }}
        <div class="form-group">
            <label for="exampleInputEmail1">Respuesta</label>
            {{ Form::text('correct_answer', $problem->correct_answer, ['class' => 'form-control']) }}
        </div>

        <div class="form-group">
            <label for="exampleInputEmail1">Dificultad</label>
            {{Form::select('difficulty', $difficulty, $problem->difficulty, array('class'=>'form-control'))}}
        </div>

        <div class="form-group">
        <select data-placeholder="Seleccione Tags" style="width: 350px" multiple="" class="tagSelect" name="tags[]">
          @foreach ($tags as $tag)
            <option value="{{$tag->id}}">{{$tag->name}}</option>
          @endforeach
        </select>
      </div>


      </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      <input type="submit" class="btn btn-primary" name="submit" value="Guardar">
    </div>
    {{ Form::close() }}

    <script>
        $(".tagSelect").chosen({
          width: "100%"
        });
    </script>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->



