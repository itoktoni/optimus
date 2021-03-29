<div class="form-group">

    {!! Form::label('name', 'Code', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_unit_code') ? 'has-error' : ''}}">
        {!! Form::text('item_unit_code', null, ['class' => 'form-control']) !!}
        {!! $errors->first('item_unit_code', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', 'Name', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_unit_name') ? 'has-error' : ''}}">
        {!! Form::text('item_unit_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('item_unit_name', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('name', 'Description', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::textarea('item_unit_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
</div>