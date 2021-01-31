@extends('layouts.app')

@section('title', __('product.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('product.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('product.name') }}</td><td>{{ $product->name }}</td></tr>
                        <tr><td>{{ __('product.description') }}</td><td>{{ $product->description }}</td></tr>
                        <tr><td>{{ __('product.current_stock') }}</td><td>{{ $product->getCurrentStock() }} {{ $product->unit->title }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $product)
                    <a href="{{ route('products.edit', $product) }}" id="edit-product-{{ $product->id }}" class="btn btn-warning">{{ __('product.edit') }}</a>
                @endcan
                <a href="{{ route('products.index') }}" class="btn btn-link">{{ __('product.back_to_index') }}</a>
            </div>
        </div>
    </div>
    <div class="col-md-6"></div>
</div>
<hr>
<div class="card">
    <div class="card-header">
        {{ __('product.stock_histories') }} <br>
    </div>
    @can('update', $product)
    <div class="card-body">
        <form action="{{ route('products.stocks.store', $product) }}" method="post" class="form-inline">
            @csrf
            {!! FormField::text('amount', ['type' => 'number', 'min' => '0', 'class' => 'mr-2']) !!}
            {!! FormField::select('transaction_type_id', config('product_stock.transaction_types'), ['label' => false, 'class' => 'mr-2']) !!}
            {!! FormField::select('partner_id', $partners, ['label' => false, 'class' => 'mr-2']) !!}
            {!! FormField::text('date', ['type' => 'date', 'value' => old('date', now()->format('Y-m-d')), 'class' => 'mr-2']) !!}
            {!! FormField::text('time', ['type' => 'time', 'value' => old('time', now()->format('H:i')), 'class' => 'mr-2']) !!}
            <div class="form-group">
                {!! Form::submit(__('product.add_stock'), ['class' => 'btn btn-success mr-2', 'name' => 'add_stock']) !!}
                {!! Form::submit(__('product.subtract_stock'), ['class' => 'btn btn-danger', 'name' => 'subtract_stock']) !!}
            </div>
        </form>
    </div>
    @endcan
    <div class="card-body">
        <table class="table table-sm table-responsive-sm table-hover">
            <thead>
                <tr>
                    <th>{{ __('partner.partner') }}</th>
                    <th class="text-center">{{ __('product_stock.transaction_type') }}</th>
                    <th>{{ __('app.date_time') }}</th>
                    <th class="text-right">{{ __('product.amount') }} ({{ $product->unit->title }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockHistories as $stockHistory)
                    <tr>
                        <td>{{ $stockHistory->partner->name }}</td>
                        <td class="text-center">{{ $stockHistory->transaction_type }}</td>
                        <td>{{ $stockHistory->created_at }}</td>
                        <td class="text-right">{{ $stockHistory->amount }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">&nbsp;</th>
                    <th class="text-right">{{ __('product.current_stock') }}</th>
                    <th class="text-right">{{ $product->stockHistories->sum('amount') }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
