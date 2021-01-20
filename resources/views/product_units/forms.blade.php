@if (Request::get('action') == 'create')
@can('create', new App\Models\ProductUnit)
    {{ Form::open(['route' => 'product_units.store']) }}
    {!! FormField::text('title', ['required' => true, 'label' => __('product_unit.title')]) !!}
    {!! FormField::textarea('description', ['label' => __('product_unit.description')]) !!}
    {{ Form::submit(__('product_unit.create'), ['class' => 'btn btn-success']) }}
    {{ link_to_route('product_units.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
    {{ Form::close() }}
@endcan
@endif
@if (Request::get('action') == 'edit' && $editableProductUnit)
@can('update', $editableProductUnit)
    {{ Form::model($editableProductUnit, ['route' => ['product_units.update', $editableProductUnit], 'method' => 'patch']) }}
    {!! FormField::text('title', ['required' => true, 'label' => __('product_unit.title')]) !!}
    {!! FormField::textarea('description', ['label' => __('product_unit.description')]) !!}
    @if (request('q'))
        {{ Form::hidden('q', request('q')) }}
    @endif
    @if (request('page'))
        {{ Form::hidden('page', request('page')) }}
    @endif
    {{ Form::submit(__('product_unit.update'), ['class' => 'btn btn-success']) }}
    {{ link_to_route('product_units.index', __('app.cancel'), Request::only('page', 'q'), ['class' => 'btn btn-link']) }}
    @can('delete', $editableProductUnit)
        {{ link_to_route(
            'product_units.index',
            __('app.delete'),
            ['action' => 'delete', 'id' => $editableProductUnit->id] + Request::only('page', 'q'),
            ['id' => 'del-product_unit-'.$editableProductUnit->id, 'class' => 'btn btn-danger float-right']
        ) }}
    @endcan
    {{ Form::close() }}
@endcan
@endif
@if (Request::get('action') == 'delete' && $editableProductUnit)
@can('delete', $editableProductUnit)
    <div class="card">
        <div class="card-header">{{ __('product_unit.delete') }}</div>
        <div class="card-body">
            <label class="control-label text-primary">{{ __('product_unit.title') }}</label>
            <p>{{ $editableProductUnit->title }}</p>
            <label class="control-label text-primary">{{ __('product_unit.description') }}</label>
            <p>{{ $editableProductUnit->description }}</p>
            {!! $errors->first('product_unit_id', '<span class="form-error small">:message</span>') !!}
        </div>
        <hr style="margin:0">
        <div class="card-body text-danger">{{ __('product_unit.delete_confirm') }}</div>
        <div class="card-footer">
            {!! FormField::delete(
                ['route' => ['product_units.destroy', $editableProductUnit]],
                __('app.delete_confirm_button'),
                ['class' => 'btn btn-danger'],
                [
                    'product_unit_id' => $editableProductUnit->id,
                    'page' => request('page'),
                    'q' => request('q'),
                ]
            ) !!}
            {{ link_to_route('product_units.index', __('app.cancel'), Request::only('page', 'q'), ['class' => 'btn btn-link']) }}
        </div>
    </div>
@endcan
@endif
