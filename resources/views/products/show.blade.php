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
                        <tr><td>{{ __('product.current_stock') }}</td><td>{{ $product->getCurrentStock() }}</td></tr>
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
        <form action="{{ route('products.stocks.store', $product) }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    {!! FormField::text('amount', ['type' => 'number', 'min' => '0']) !!}
                </div>
                <div class="col-md-6">
                    {!! FormField::radios('transaction_type_id', config('product_stock.transaction_types')) !!}
                </div>
            </div>
            {!! FormField::select('partner_id', $partners) !!}
            <div class="form-group">
                {!! Form::submit(__('product.add_stock'), ['class' => 'btn btn-success', 'name' => 'add_stock']) !!}
                {!! Form::submit(__('product.subtract_stock'), ['class' => 'btn btn-danger', 'name' => 'subtract_stock']) !!}
            </div>
        </form>
        @endcan
        <div class="card">
            <div class="card-header">{{ __('product.stock_histories') }}</div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('product_stock.transaction_type') }}</th>
                        <th>{{ __('partner.partner') }}</th>
                        <th>{{ __('app.date_time') }}</th>
                        <th class="text-right">{{ __('product.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->stockHistories as $stockHistory)
                        <tr>
                            <td class="text-center">{{ $stockHistory->transaction_type }}</td>
                            <td>{{ $stockHistory->partner->name }}</td>
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
</div>
@endsection
