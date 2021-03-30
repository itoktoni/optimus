@extends(Views::backend())

@section('content')

<div class="row">
    <div class="panel-body">
        {!! Form::open(['route' => $route_save, 'class' => 'form-horizontal', 'files' => true]) !!}
        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">{{ __('Report') }} {{ __('Linen') }}</h2>
            </header>

            <div class="panel-body line">
                @includeIf(Views::include($template, $folder))
            </div>

            <div class="navbar-fixed-bottom" id="menu_action">
                <div class="text-right action-wrapper">
                    <button type="submit" value="pdf" name="action" class="btn btn-danger">{{ __('PDF') }}</button>
                    <button type="submit" value="excel" name="action" class="btn btn-success">{{ __('Excel') }}</button>
                </div>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
</div>

@endsection
