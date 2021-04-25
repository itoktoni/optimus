<x-date :array="['date']" />

<div class="form-group">

    <label class="col-md-2 control-label">Dari Tanggal</label>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" name="from" value="{{ old('from') ?? date('Y-m-d') }}" class="date">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
        </div>
    </div>

    <label class="col-md-2 control-label">Ke Tanggal</label>
    <div class="col-md-4">
        <div class="input-group">
            <input type="text" name="to" value="{{ old('to') ?? date('Y-m-d') }}" class="date">
            <span class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </span>
        </div>
    </div>

</div>

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

<div class="form-group">

    {!! Form::label('name', __('Product'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_product_id') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_product_id', $product, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_product_id', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Status'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_rent') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_rent', $rental, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_rent', '<p class="help-block">:message</p>') !!}
    </div>

</div>

<div class="form-group">

    {!! Form::label('name', __('Register By'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_created_by') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_created_by', $user, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_created_by', '<p class="help-block">:message</p>') !!}
    </div>

    {!! Form::label('name', __('Updated By'), ['class' => 'col-md-2 control-label']) !!}
    <div class="col-md-4 {{ $errors->has('item_linen_updated_by') ? 'has-error' : ''}}">
        {{ Form::select('item_linen_updated_by', $user, null, ['class'=> 'form-control ']) }}
        {!! $errors->first('item_linen_updated_by', '<p class="help-block">:message</p>') !!}
    </div>

</div>

@isset($preview)

<hr>

<table id="transaction" class="table table-responsive table-no-more table-bordered table-striped">
    <thead>
        <tr>
            <th class="text-left col-md-2">__('No. Seri RFID')</th>
            <th class="text-left col-md-3">__('Product Name')</th>
            <th class="text-left col-md-2">__('Company')</th>
            <th class="text-left col-md-1">__('Location')</th>
            <th class="text-left col-md-2">__('Register') BY</th>
            <th class="text-left col-md-2">__('Register') Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($preview as $linen)
        <tr>
            <td>{{ $linen->item_linen_rfid }}</td>
            <td>{{ $linen->item_product_name }}</td>
            <td>{{ $linen->company_name }}</td>
            <td>{{ $linen->location_name }}</td>
            <td>{{ $linen->name }}</td>
            <td>{{ $linen->item_linen_created_at }}</td>
        </tr>
        @endforeach
    </tbody>
    <tbody class="markup">
        
    </tbody>
</table>

@endif