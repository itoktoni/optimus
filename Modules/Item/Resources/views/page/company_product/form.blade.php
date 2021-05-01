<div class="form-group">

    {!! Form::label('name', __('Company'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    @if(isset($master['company_id']))
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_id') ? 'has-error' : ''}}">
        {{ Form::select('', $company, $master['company_id'] ?? null, ['class'=> 'form-control', 'disabled']) }}
        <input type="hidden" {{ $master['company_id'] }} name="company_id">
        {!! $errors->first('company_id', '<p class="help-block">:message</p>') !!}
    </div>
    @else
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_id') ? 'has-error' : ''}}">
        {{ Form::select('company_id', $company, null, ['class'=> 'form-control']) }}
        {!! $errors->first('company_id', '<p class="help-block">:message</p>') !!}
    </div>
    @endif

    {!! Form::label('name', __('Product'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    @if(isset($master['item_product_id']))
    <div class="col-md-4 col-sm-4 {{ $errors->has('item_product_id') ? 'has-error' : ''}}">
        {{ Form::select('', $product, $master['item_product_id'] ?? null, ['class'=> 'form-control', 'disabled']) }}
        <input type="hidden" {{ $master['item_product_id'] }} name="item_product_id">
        {!! $errors->first('item_product_id', '<p class="help-block">:message</p>') !!}
    </div>
    @else
    <div class="col-md-4 col-sm-4 {{ $errors->has('item_product_id') ? 'has-error' : ''}}">
        {{ Form::select('item_product_id', $product, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_product_id', '<p class="help-block">:message</p>') !!}
    </div>
    @endif

    

</div>

<div class="form-group">

    {!! Form::label('name', __('Size'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_item_size_id') ? 'has-error' : ''}}">
        {{ Form::select('company_item_size_id', $size, null, ['class'=> 'form-control', ]) }}
        {!! $errors->first('company_item_size_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Unit'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_item_unit_id') ? 'has-error' : ''}}">
        {{ Form::select('company_item_unit_id', $unit, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('company_item_unit_id', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Minimal'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_item_minimal') ? 'has-error' : ''}}">
        {!! Form::text('company_item_minimal', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company_item_minimal', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Maximal'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_item_maximal') ? 'has-error' : ''}}">
        {!! Form::text('company_item_maximal', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company_item_maximal', '<p class="help-block">:message</p>') !!}
    </div>

</div>


<div class="form-group">

    {!! Form::label('name', __('Target'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_item_target') ? 'has-error' : ''}}">
        {!! Form::text('company_item_target', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company_item_target', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Weight'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-4 col-sm-4 {{ $errors->has('company_item_weight') ? 'has-error' : ''}}">
        {!! Form::text('company_item_weight', null, ['class' => 'form-control']) !!}
        {!! $errors->first('company_item_weight', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">
    {!! Form::label('name', __('Description'), ['class' => 'col-md-2 col-sm-2 control-label']) !!}
    <div class="col-md-10 col-sm-10">
        {!! Form::textarea('company_item_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
    </div>
</div>