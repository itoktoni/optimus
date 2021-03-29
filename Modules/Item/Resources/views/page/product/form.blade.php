<div class="form-group">

    {!! Form::label('name', 'Name', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_product_name') ? 'has-error' : ''}}">
        {!! Form::text('item_product_name', null, ['class' => 'form-control']) !!}
        {!! $errors->first('item_product_name', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', 'SKU', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_product_sku') ? 'has-error' : ''}}">
        {!! Form::text('item_product_sku', null, ['class' => 'form-control']) !!}
        {!! $errors->first('item_product_sku', '<p class="help-block">:message</p>') !!}
    </div>



</div>

<div class="form-group">

    {!! Form::label('name', 'Unit', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_product_unit_code') ? 'has-error' : ''}}">
        {{ Form::select('item_product_unit_code', $unit, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_product_unit_code', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', 'Category', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_product_category_id') ? 'has-error' : ''}}">
        {{ Form::select('item_product_category_id', $category, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_product_category_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', 'Berat', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_product_weight') ? 'has-error' : ''}}">
        {!! Form::number('item_product_weight', null, ['class' => 'form-control']) !!}
        {!! $errors->first('item_product_weight', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', 'Active', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_product_status') ? 'has-error' : ''}}">
        {{ Form::select('item_product_status', ['0' => 'No', '1' => 'Yes'], null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_product_status', '<p class="help-block">:message</p>') !!}
    </div>


</div>

<div class="form-group">

    {!! Form::label('name', 'Logo', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-3" {{ $errors->has('item_product_image') ? 'has-error' : ''}}">
        <input type="hidden" value="{{ $model->item_product_image ?? null }}" name="item_product_image">
        <input type="file" name="file"
            class="{{ $errors->has('item_product_image') ? 'has-error' : ''}} btn btn-default btn-sm btn-block">
        {!! $errors->first('item_product_image', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-md-1">
        <img class="img-responsive img-fluid mt-xs"
            src="{{ isset($model) ? Helper::files('product/'.$model->item_product_image) : '' }}" alt="">
    </div>

    {!! Form::label('name', 'Description', ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4">
        {!! Form::textarea('item_product_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>

</div>