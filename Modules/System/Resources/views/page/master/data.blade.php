@extends(Views::backend())

@component('components.responsive', ['array' => $fields])
@endcomponent
@component('components.datatables',['array' => $fields])@endcomponent

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

                        {!! Form::label($search_code, __('Searching'), ['class' => 'col-md-1 control-label']) !!}
                        <div class="col-md-5">
                            <div class="input-group">
                                <input autofocus name="search" class="form-control" placeholder="{{ __('Advance Search') }}"
                                    type="text">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary">{{ __('Search') }}</button>
                                </span>
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