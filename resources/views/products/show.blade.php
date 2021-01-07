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
            <div class="form-group">
                <label for="amount" class="form-label">{{ __('product.amount') }} <span class="form-required">*</span></label>
                <input id="amount" type="number" min="1" class="form-control{{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{ old('amount') }}" required>
                {!! $errors->first('amount', '<span class="invalid-feedback" role="alert">:message</span>') !!}
            </div>
            <div class="form-group">
                <label for="transaction_type_id" class="control-label">{{ __('product_stock.transaction_type') }}</label>
                <select id="transaction_type_id" name="transaction_type_id" class="form-control{{ $errors->has('transaction_type_id') ? ' is-invalid' : '' }}">
                    <option value="">-- {{ __('product_stock.transaction_type_select') }} --</option>
                    @foreach (config('product_stock.transaction_types') as $transactionTypeId => $transactionTypeName)
                        <option value="{{ $transactionTypeId }}" {{ old('transaction_type_id') == $transactionTypeId ? 'selected' : '' }}>
                            {{ $transactionTypeName }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('transaction_type_id', '<span class="small text-danger">:message</span>') !!}
            </div>
            <div class="form-group">
                <input type="submit" name="add_stock"  class="btn btn-success" value="{{ __('product.add_stock') }}">
                <input type="submit" name="subtract_stock"  class="btn btn-danger" value="{{ __('product.subtract_stock') }}">
            </div>
        </form>
        @endcan
        <div class="card">
            <div class="card-header">{{ __('product.stock_histories') }}</div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('product_stock.transaction_type') }}</th>
                        <th>{{ __('app.date_time') }}</th>
                        <th class="text-right">{{ __('product.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->stockHistories as $stockHistory)
                        <tr>
                            <td class="text-center">{{ $stockHistory->transaction_type_id }}</td>
                            <td>{{ $stockHistory->created_at }}</td>
                            <td class="text-right">{{ $stockHistory->amount }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>&nbsp;</th>
                        <th class="text-right">{{ __('product.current_stock') }}</th>
                        <th class="text-right">{{ $product->stockHistories->sum('amount') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
