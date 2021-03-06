@extends('layouts.app')

@section('title', __('product.list'))

@section('content')
<div class="mb-3">
    <div class="float-right">
        @can('create', new App\Models\Product)
            <a href="{{ route('products.create') }}" class="btn btn-success">{{ __('product.create') }}</a>
        @endcan
    </div>
    <h1 class="page-title">{{ __('product.list') }} <small>{{ __('app.total') }} : {{ $products->total() }} {{ __('product.product') }}</small></h1>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <form method="GET" action="" accept-charset="UTF-8" class="form-inline">
                    <div class="form-group">
                        <label for="q" class="form-label">{{ __('product.search') }}</label>
                        <input placeholder="{{ __('product.search_text') }}" name="q" type="text" id="q" class="form-control mx-sm-2" value="{{ request('q') }}">
                    </div>
                    <input type="submit" value="{{ __('product.search') }}" class="btn btn-secondary">
                    <a href="{{ route('products.index') }}" class="btn btn-link">{{ __('app.reset') }}</a>
                </form>
            </div>
            <table class="table table-sm table-responsive-sm table-hover">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('app.table_no') }}</th>
                        <th>{{ __('product.name') }}</th>
                        <th>{{ __('product.description') }}</th>
                        <th class="text-right">{{ __('product.current_stock') }}</th>
                        <th class="text-center">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $key => $product)
                    <tr>
                        <td class="text-center">{{ $products->firstItem() + $key }}</td>
                        <td>{!! $product->name_link !!}</td>
                        <td>{{ $product->description }}</td>
                        <td class="text-right">{{ $product->current_stock }} {{ $product->unit->title }}</td>
                        <td class="text-center">
                            @can('view', $product)
                                <a href="{{ route('products.show', $product) }}" id="show-product-{{ $product->id }}">{{ __('app.show') }}</a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="card-body">{{ $products->appends(Request::except('page'))->render() }}</div>
        </div>
    </div>
</div>
@endsection
