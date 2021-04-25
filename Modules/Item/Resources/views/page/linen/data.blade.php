@extends(Views::backend())

@component('components.responsive', ['array' => $fields])
@endcomponent
@push('js')
<script src="{{ Helper::backend('vendor/jquery-datatables/media/js/jquery.dataTables.min.js') }}"></script>
@endpush
@push('javascript')
{{-- for datatable and parse fields --}}
<script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var oTable = $('#datatable').DataTable({
            processing: true,
            orderCellsTop: true,
            fixedHeader: true,
            dom: '<<t>p><"pull-left"i>',
            serverSide: true,
            order: false,
            pageLength: {{ config('website.pagination') }},
            pagingType: 'first_last_numbers',
            ajax: {
            url: '{{ route($route_data) }}',
                method : 'POST',
                data: function(d) {
                    d.code = $('select[name=code]').val();
                    d.search = $('input[name=search]').val();
                    d.aggregate = $('select[name=aggregate]').val();
                    d.item_linen_rfid = $('input[name=item_linen_rfid]').val();
                    d.item_linen_company_id = $('select[name=item_linen_company_id]').val();
                    d.item_linen_location_id = $('select[name=item_linen_location_id]').val();
                    d.item_linen_product_id = $('select[name=item_linen_product_id]').val();
                    d.status = $('select[name=status]').val();
                    d.rent = $('select[name=rent]').val();
                    d.item_linen_created_by = $('select[name=item_linen_created_by]').val();
                    d.item_linen_created_at = $('input[name=item_linen_created_at]').val();
                },
                error: function (xhr, textStatus, errorThrown) {
                    new PNotify({
                        title: 'Datatable Error !',
                        text: {{ config('website.env') == 'local' ? 'xhr.responseJSON.message' : 'errorThrown' }},
                        type: 'error',
                        hide: false
                    });
                }
            },
            columns: 
            [
                @foreach($fields as $key => $value)
                {data: '{{ $key }}', name: '{{ $key }}', orderable: false, searchable: true},
                @endforeach
                {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
        });

        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
        });
    });

    
</script>
@endpush

<x-date :array="['date']" />

@section('content')

<div class="row">
    <div class="panel-body">

        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Data') }} {{ __($form_name) }}
                </h2>
            </header>

            <div class="panel-body line wrap">

                <div class="filter-data form-group">
                    {!! Form::open(['route' => $route_data, 'id' => 'search-form', 'files' => true]) !!}
                    <div class="form-horizontal">

                        <div class="row">
                            <div class="col-md-12">

                                {!! Form::label($search_code, __('No. Seri RFID'), ['class' => 'col-md-1
                                control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {!! Form::text('item_linen_rfid', null, ['class' => 'form-control', 'id' => 'item_linen_rfid']) !!}
                                </div>

                                {!! Form::label($search_code, __('Product Name'), ['class' => 'col-md-1 control-label'])
                                !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {{ Form::select('item_linen_product_id', $product, null, ['class'=> 'form-control ']) }}
                                </div>

                                {!! Form::label($search_code, __('Company'), ['class' => 'col-md-1 control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {{ Form::select('item_linen_company_id', $company, null, ['class'=> 'form-control ']) }}
                                </div>

                                {!! Form::label($search_code, __('Location Name'), ['class' => 'col-md-1
                                control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {{ Form::select('item_linen_location_id', $location, null, ['class'=> 'form-control ']) }}
                                </div>

                            </div>

                        </div>

                        <div class="row mt-sm">
                            <div class="col-md-12">
                                {!! Form::label($search_code, __('Created at'), ['class' => 'col-md-1 control-label'])
                                !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    <div class="input-group">
                                        <input type="text" name="item_linen_created_at" value="{{ old('item_linen_rfid') ?? null }}"
                                            class="date">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>

                                {!! Form::label($search_code, __('Register By'), ['class' => 'col-md-1 control-label'])
                                !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {{ Form::select('item_linen_created_by', $user, null, ['class'=> 'form-control ']) }}
                                </div>

                                {!! Form::label($search_code, __('Rental'), ['class' => 'col-md-1 control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {{ Form::select('rent', $rent, null, ['class'=> 'form-control ']) }}
                                </div>

                                {!! Form::label($search_code, __('Status'), ['class' => 'col-md-1 control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    {{ Form::select('status', $status, null, ['class'=> 'form-control ']) }}
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-horizontal mt-sm">

                                {!! Form::label($search_code, __('Criteria'), ['class' => 'col-md-1 control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    <select name="code" class="form-control">
                                        <option value="">{{ __('Select Data') }}</option>
                                        @foreach($fields as $item => $value)
                                        <option value="{{ $item }}">{{ __($value['name']) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {!! Form::label($search_code, __('Operator'), ['class' => 'col-md-1 control-label']) !!}
                                <div class="col-md-2 {{ $errors->has($search_code) ? 'has-error' : ''}}">
                                    <div class="">
                                        <select name="aggregate" class="form-control">
                                            <option value="">{{ __('Search With') }}</option>
                                            <option value="=">{{ __('Equal') }}</option>
                                            <option value="!=">{{ __('Not Equal') }}</option>
                                            <option value="like">{{ __('Contains') }}</option>
                                            <option value="not like">{{ __('Not Contains') }}</option>
                                            <option value=">">{{ __('More Than') }}</option>
                                            <option value="<">{{ __('Less Than') }}</option>
                                        </select>
                                    </div>
                                </div>

                                {!! Form::label($search_code, __('Searching'), ['class' => 'col-md-1 control-label'])
                                !!}
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input autofocus name="search" class="form-control"
                                            placeholder="{{ __('Advance Search') }}" type="text">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}

                </div>

                <div class="form-group">
                    {!! Form::open(['route' => $route_delete, 'class' => 'form-horizontal', 'files' => true]) !!}

                    <table id="datatable" class="responsive table-striped table-condensed table-bordered table-hover">
                        <thead>
                            <tr>
                                @foreach($fields as $item => $value)
                                <th class="{{ $value['class'] ?? '' }}">
                                    <strong>{{ __($value['name']) }}</strong>
                                </th>
                                @endforeach
                                <th width="9" class="center"><input id="checkAll" class="selectall"
                                        onclick="toggle(this)" type="checkbox"></th>

                                <th class="text-center" width=70>
                                    <strong>{{ __('Actions') }}</strong>
                                </th>
                            </tr>
                            
                        </thead>
                    </table>
                    @include($template_action)
                    {!! Form::close() !!}
                </div>

            </div>

        </div>

    </div>
</div>

@endsection