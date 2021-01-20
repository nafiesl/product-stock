@extends('layouts.app')

@section('title', __('product_unit.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\ProductUnit)
            {{ link_to_route('product_units.index', __('product_unit.create'), ['action' => 'create'], ['class' => 'btn btn-success']) }}
        @endcan
    </div>
    <h1 class="page-title">{{ __('product_unit.list') }} <small>{{ __('app.total') }} : {{ $productUnits->total() }} {{ __('product_unit.product_unit') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                {{ Form::open(['method' => 'get', 'class' => 'form-inline']) }}
                {!! FormField::text('q', ['label' => __('product_unit.search'), 'placeholder' => __('product_unit.search_text'), 'class' => 'mx-sm-2']) !!}
                {{ Form::submit(__('product_unit.search'), ['class' => 'btn btn-secondary']) }}
                {{ link_to_route('product_units.index', __('app.reset'), [], ['class' => 'btn btn-link']) }}
                {{ Form::close() }}
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('product_unit.title') }}</th>
                        <th>{{ __('product_unit.description') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productUnits as $key => $productUnit)
                    <tr>
                        <td class="text-center">{{ $productUnits->firstItem() + $key }}</td>
                        <td>{{ $productUnit->title }}</td>
                        <td>{{ $productUnit->description }}</td>
                        <td class="text-center">
                            @can('update', $productUnit)
                                {{ link_to_route(
                                    'product_units.index',
                                    __('app.edit'),
                                    ['action' => 'edit', 'id' => $productUnit->id] + Request::only('page', 'q'),
                                    ['id' => 'edit-product_unit-'.$productUnit->id]
                                ) }}
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $productUnits->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Request::has('action'))
        @include('product_units.forms')
        @endif
    </div>
</div>
@endsection
