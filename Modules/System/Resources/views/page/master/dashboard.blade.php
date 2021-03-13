@extends(Views::backend())
@component('components.charts', ['array' => 'Chartjs'])
@endcomponent
@section('content')
<div class="row">

    <div class="panel-body">

        <div class="panel panel-default">
            <header class="panel-heading">
                <h2 class="panel-title">Dashboard</h2>
            </header>

            <div class="panel-body line">
                {{-- {!! $chart->container() !!} --}}

                {{-- <script src="{{ $chart->cdn() }}"></script> --}}
            </div>

        </div>
    </div>

</div>
@endsection

{{-- {{ $chart->script() }} --}}
@component('components.chart')
@slot('container')
{!! $chart->container() !!}
@endslot
@slot('script')
{!! $chart->script() !!}
@endslot
@endcomponent