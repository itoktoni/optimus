<div class="form-group">

    {!! Form::label('name', __('Company'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_company_id') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_company_id', $company, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_company_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Location'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_location_id') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_location_id', $location, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_location_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<hr>

<div class="form-group">

    {!! Form::label('name', __('Product'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_product_id') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_product_id', $product, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_product_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('No. Seri RFID'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_rfid') ? 'has-error' : ''}}">
        {!! Form::text('item_linen_rfid', null, ['class' => 'form-control']) !!}
        {!! $errors->first('item_linen_rfid', '<p class="help-block">:message</p>') !!}
    </div>

</div>


<div class="form-group">

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_status') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_status', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_status', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Sewa'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_rent') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_rent', $rent, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_rent', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-10">
        {!! Form::textarea('item_linen_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
</div>