@extends('layouts.app')

@section('title', __('product.edit'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        @if (request('action') == 'delete' && $product)
        @can('delete', $product)
            <div class="card">
                <div class="card-header">{{ __('product.delete') }}</div>
                <div class="card-body">
                    <label class="form-label text-primary">{{ __('product.name') }}</label>
                    <p>{{ $product->name }}</p>
                    <label class="form-label text-primary">{{ __('product.description') }}</label>
                    <p>{{ $product->description }}</p>
                    {!! $errors->first('product_id', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                </div>
                <hr style="margin:0">
                <div class="card-body text-danger">{{ __('product.delete_confirm') }}</div>
                <div class="card-footer">
                    <form method="POST" action="{{ route('products.destroy', $product) }}" accept-charset="UTF-8" onsubmit="return confirm(&quot;{{ __('app.delete_confirm') }}&quot;)" class="del-form float-right" style="display: inline;">
                        {{ csrf_field() }} {{ method_field('delete') }}
                        <input name="product_id" type="hidden" value="{{ $product->id }}">
                        <button type="submit" class="btn btn-danger">{{ __('app.delete_confirm_button') }}</button>
                    </form>
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                </div>
            </div>
        @endcan
        @else
        <div class="card">
            <div class="card-header">{{ __('product.edit') }}</div>
            <form method="POST" action="{{ route('products.update', $product) }}" accept-charset="UTF-8">
                {{ csrf_field() }} {{ method_field('patch') }}
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('product.name') }} <span class="form-required">*</span></label>
                        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $product->name) }}" required>
                        {!! $errors->first('name', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                    {!! FormField::select('product_unit_id', $productUnits, ['value' => old('product_unit_id', $product->product_unit_id)]) !!}
                    <div class="form-group">
                        <label for="description" class="form-label">{{ __('product.description') }}</label>
                        <textarea id="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        {!! $errors->first('description', '<span class="invalid-feedback" role="alert">:message</span>') !!}
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" value="{{ __('product.update') }}" class="btn btn-success">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-link">{{ __('app.cancel') }}</a>
                    @can('delete', $product)
                        <a href="{{ route('products.edit', [$product, 'action' => 'delete']) }}" id="del-product-{{ $product->id }}" class="btn btn-danger float-right">{{ __('app.delete') }}</a>
                    @endcan
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
