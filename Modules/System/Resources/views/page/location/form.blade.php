<div class="form-group">
    {!! Form::label('location_name', 'Name', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('location_name') ? 'has-error' : ''}}">
        {!! Form::text('location_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('location_name', '<p class="help-block">:message</p>') !!}
    </div>
    <label class="col-md-2 control-label">Location</label>
    <div class="col-md-4 {{ $errors->has('location_company_id') ? 'has-error' : ''}}">
        {{ Form::select('location_company_id', $company, null, ['class'=> 'form-control']) }}
        {!! $errors->first('location_company_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', 'Description', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::textarea('location_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
        {!! $errors->first('location_description', '<p class="help-block">:message</p>') !!}
    </div>
</div>