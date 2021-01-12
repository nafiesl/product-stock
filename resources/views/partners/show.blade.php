@extends('layouts.app')

@section('title', __('partner.detail'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{ __('partner.detail') }}</div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr><td>{{ __('partner.name') }}</td><td>{{ $partner->name }}</td></tr>
                        <tr><td>{{ __('partner.description') }}</td><td>{{ $partner->description }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                @can('update', $partner)
                    {{ link_to_route('partners.edit', __('partner.edit'), [$partner], ['class' => 'btn btn-warning', 'id' => 'edit-partner-'.$partner->id]) }}
                @endcan
                {{ link_to_route('partners.index', __('partner.back_to_index'), [], ['class' => 'btn btn-link']) }}
            </div>
        </div>
    </div>
</div>
@endsection
