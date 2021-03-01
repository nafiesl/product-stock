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
    <div class="col-md-6">
        @can('update', $product)
        @if (request('action') == 'edit_stock_history' && $editableStockHistory)
            <div class="card">
                <div class="card-header">{{ __('product_stock.edit_history') }}</div>
                <div class="card-body">
                    {{ Form::model($editableStockHistory, ['route' => ['products.stocks.update', $product->id, $editableStockHistory->id], 'method' => 'patch']) }}
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                {!! FormField::text('amount', ['type' => 'number']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! FormField::select('partner_id', $partners) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! FormField::select('transaction_type_id', config('product_stock.transaction_types'), ['placeholder' => 'Select']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! FormField::text('date', ['type' => 'date', 'value' => old('date', $editableStockHistory->created_at->format('Y-m-d'))]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! FormField::text('time', ['type' => 'time', 'value' => old('time', $editableStockHistory->created_at->format('H:i'))]) !!}
                            </div>
                        </div>
                        {!! FormField::textarea('description') !!}
                        <div class="form-group">
                            {!! Form::submit(__('product.update_stock'), ['class' => 'btn btn-warning mr-2']) !!}
                            {{ link_to_route('products.show', __('app.cancel'), [$product], ['class' => 'btn btn-secondary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-header">{{ __('product_stock.add_history') }}</div>
                <div class="card-body">
                    <form action="{{ route('products.stocks.store', $product) }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                {!! FormField::text('amount', ['type' => 'number', 'min' => '0']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! FormField::select('partner_id', $partners) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                {!! FormField::select('transaction_type_id', config('product_stock.transaction_types'), ['placeholder' => 'Select']) !!}
                            </div>
                            <div class="col-md-4">
                                {!! FormField::text('date', ['type' => 'date', 'value' => old('date', now()->format('Y-m-d'))]) !!}
                            </div>
                            <div class="col-md-4">
                                {!! FormField::text('time', ['type' => 'time', 'value' => old('time', now()->format('H:i'))]) !!}
                            </div>
                        </div>
                        {!! FormField::textarea('description') !!}
                        <div class="form-group">
                            {!! Form::submit(__('product.add_stock'), ['class' => 'btn btn-success mr-2', 'name' => 'add_stock']) !!}
                            {!! Form::submit(__('product.subtract_stock'), ['class' => 'btn btn-danger', 'name' => 'subtract_stock']) !!}
                        </div>
                    </form>
                </div>
            </div>
        @endif
        @endcan
    </div>
</div>
<hr>
<div class="card">
    <div class="card-header">
        {{ __('product.stock_histories') }} <br>
    </div>
    <div class="card-body">
        <table class="table table-sm table-responsive-sm table-hover">
            <thead>
                <tr>
                    <th>{{ __('partner.partner') }}</th>
                    <th class="text-center">{{ __('product_stock.transaction_type') }}</th>
                    <th>{{ __('app.date_time') }}</th>
                    <th>{{ __('product_stock.description') }}</th>
                    <th class="text-right">{{ __('product.amount') }} ({{ $product->unit->title }})</th>
                    <th class="text-center">{{ __('app.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stockHistories as $stockHistory)
                    <tr>
                        <td>{{ $stockHistory->partner->name }}</td>
                        <td class="text-center">{{ $stockHistory->transaction_type }}</td>
                        <td>{{ $stockHistory->created_at }}</td>
                        <td>{{ $stockHistory->description }}</td>
                        <td class="text-right">{{ $stockHistory->amount }}</td>
                        <td class="text-center">
                            {{ link_to_route(
                                'products.show',
                                __('app.edit'),
                                [$product->id, 'action' => 'edit_stock_history', 'stock_history_id' => $stockHistory->id],
                                ['id' => 'edit_stock-'.$stockHistory->id]
                            ) }}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3">&nbsp;</th>
                    <th class="text-right">{{ __('product.current_stock') }}</th>
                    <th class="text-right">{{ $product->stockHistories->sum('amount') }}</th>
                    <th>&nbsp;</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
