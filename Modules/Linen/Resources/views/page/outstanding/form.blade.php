<div class="form-group">

    {!! Form::label('name', __('Company'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('linen_outstanding_scan_company_id') ? 'has-error' : ''}}">
        {{ Form::select('linen_outstanding_scan_company_id', $company, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('linen_outstanding_scan_company_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Location'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('linen_outstanding_scan_location_id') ? 'has-error' : ''}}">
        {{ Form::select('linen_outstanding_scan_location_id', $location, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('linen_outstanding_scan_location_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<hr>

<div class="form-group">

    {!! Form::label('name', __('No. Seri RFID'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('linen_outstanding_rfid') ? 'has-error' : ''}}">
        {!! Form::text('linen_outstanding_rfid', null, ['class' => 'form-control']) !!}
        {!! $errors->first('linen_outstanding_rfid', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('linen_outstanding_status') ? 'has-error' : ''}}">
        {{ Form::select('linen_outstanding_status', $status, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('linen_outstanding_status', '<p class="help-block">:message</p>') !!}
    </div>

</div>

@if ($action_function == 'edit')
<hr>

<div class="form-group">

    {!! Form::label('name', __('Product'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('linen_outstanding_product_id') ? 'has-error' : ''}}">
        {{ Form::select('linen_outstanding_product_id', $product, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('linen_outstanding_product_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('linen_outstanding_description') ? 'has-error' : ''}}">
        {{ Form::select('linen_outstanding_description', $description, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('linen_outstanding_description', '<p class="help-block">:message</p>') !!}
    </div>

</div>

@endif