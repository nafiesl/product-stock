@extends('layouts.app')

@section('title', __('partner.create'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('partner.create') }}</div>
            {{ Form::open(['route' => 'partners.store']) }}
            <div class="card-body">
                {!! FormField::text('name', ['required' => true, 'label' => __('partner.name')]) !!}
                {!! FormField::radios('type_id', config('product_stock.partner_types'), ['required' => true, 'label' => __('partner.type')]) !!}
                {!! FormField::textarea('description', ['label' => __('partner.description')]) !!}
            </div>
            <div class="card-footer">
                {{ Form::submit(__('partner.create'), ['class' => 'btn btn-success']) }}
                {{ link_to_route('partners.index', __('app.cancel'), [], ['class' => 'btn btn-link']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
