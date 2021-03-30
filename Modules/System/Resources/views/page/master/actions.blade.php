<div class="action text-center">
    @if (isset($actions['update']))
    <a id="linkMenu" href="{{ route($route_edit, ['code' => $model->{$model->getKeyName()}]) }}"
        class="btn btn-xs btn-primary">{{ __('Edit') }}</a>
    @endif
    @if (isset($actions['show']))
    <a id="linkMenu" href="{{ route($route_show,['code' => $model->{$model->getKeyName()}]) }}"
        class="btn btn-xs btn-success">{{ __('Show') }}</a>
    @endif
</div>