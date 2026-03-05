

<div class="form-group">
    {{ Form::label($name, null, ['class' => 'control-label']) }}
    {{ Form::text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}

    <!-- {{Form::label($name)}}
    {{Form::text($name, $value, $attributes)}} -->
    <!-- <label for="email">Email address:</label>
    
    <input type="email" class="form-control" placeholder="Enter email" id="email"> -->
  </div>