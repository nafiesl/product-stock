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
                        <th>{{ __('app.date_time') }}</th>
                        <th class="text-right">{{ __('product.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($product->stockHistories as $stockHistory)
                        <tr>
                            <td>{{ $stockHistory->created_at }}</td>
                            <td class="text-right">{{ $stockHistory->amount }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th>{{ __('product.current_stock') }}</th>
                        <th class="text-right">{{ $product->stockHistories->sum('amount') }}</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
